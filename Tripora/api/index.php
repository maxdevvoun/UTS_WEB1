<?php
/** Tripora REST API. Endpoints use /api/index.php/{resource}. */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function api_response(int $status, array $payload): never
{
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function api_ok($data = null, string $message = 'Success', int $status = 200): never
{
    api_response($status, ['success' => true, 'message' => $message, 'data' => $data]);
}

function api_fail(int $status, string $message, array $errors = []): never
{
    $payload = ['success' => false, 'message' => $message];
    if ($errors) $payload['errors'] = $errors;
    api_response($status, $payload);
}

function request_data(): array
{
    $raw = file_get_contents('php://input');
    if (!$raw) return $_POST ?: [];
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function require_login_api(): array
{
    $user = current_user();
    if (!$user) api_fail(401, 'Please log in to continue.');
    return $user;
}

function require_admin_api(): array
{
    $user = require_login_api();
    if ($user['role'] !== 'admin') api_fail(403, 'Administrator access is required.');
    return $user;
}

function clean_text($value, int $max = 10000): string
{
    $value = trim((string) ($value ?? ''));
    return function_exists('mb_substr') ? mb_substr($value, 0, $max, 'UTF-8') : substr($value, 0, $max);
}

function clean_image($value): ?string
{
    $value = trim((string) ($value ?? ''));
    if ($value === '') return null;
    if (preg_match('#^https?://#i', $value)) return $value;
    if (preg_match('#^assets/[A-Za-z0-9_./-]+$#', $value) && !str_contains($value, '..')) return $value;
    api_fail(422, 'Image must be an HTTP(S) URL or a path inside assets/.');
}

function positive_id($value, string $label): int
{
    $id = filter_var($value, FILTER_VALIDATE_INT);
    if (!$id || $id < 1) api_fail(422, $label . ' is invalid.');
    return (int) $id;
}

function validate_category(array $data): array
{
    $name = clean_text($data['category_name'] ?? '', 100);
    if ($name === '') api_fail(422, 'Category name is required.');
    return [$name, clean_text($data['description'] ?? ''), clean_image($data['image_url'] ?? '')];
}

function validate_destination(array $data): array
{
    $name = clean_text($data['destination_name'] ?? '', 150);
    $country = clean_text($data['country'] ?? '', 100);
    if ($name === '' || $country === '') api_fail(422, 'Destination name and country are required.');
    return [$name, $country, clean_text($data['city'] ?? '', 100), clean_text($data['description'] ?? ''), clean_image($data['image_url'] ?? '')];
}

function validate_package(array $data): array
{
    $categoryId = positive_id($data['category_id'] ?? null, 'Category');
    $destinationId = positive_id($data['destination_id'] ?? null, 'Destination');
    $name = clean_text($data['package_name'] ?? '', 150);
    $priceRaw = $data['price'] ?? null;
    if ($name === '') api_fail(422, 'Package name is required.');
    if (!is_numeric($priceRaw) || (float) $priceRaw < 0) api_fail(422, 'Price must be zero or greater.');
    $status = (string) ($data['status'] ?? 'active');
    if (!in_array($status, ['active', 'inactive'], true)) api_fail(422, 'Package status is invalid.');
    return [
        $categoryId,
        $destinationId,
        $name,
        clean_text($data['description'] ?? ''),
        number_format((float) $priceRaw, 2, '.', ''),
        clean_text($data['duration'] ?? '', 50),
        clean_text($data['facilities'] ?? ''),
        clean_text($data['itinerary'] ?? ''),
        clean_image($data['image_url'] ?? ''),
        $status,
    ];
}

// Resolve the route from PATH_INFO (standard XAMPP) or REQUEST_URI fallback.
$route = trim((string) ($_SERVER['PATH_INFO'] ?? ''), '/');
if ($route === '') {
    $requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
    $scriptPath = rtrim(BASE_URL, '/') . '/api/index.php';
    if (str_starts_with($requestPath, $scriptPath)) {
        $route = trim(substr($requestPath, strlen($scriptPath)), '/');
    }
}
$segments = $route === '' ? [] : explode('/', $route);
$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($method === 'OPTIONS') {
    api_response(204, []);
}
if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
    $sentToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $sentToken)) {
        api_fail(419, 'Session token is missing or expired. Refresh the page and try again.');
    }
}

try {
    $db = (new Database())->connect();
    $resource = $segments[0] ?? '';
    $id = (isset($segments[1]) && ctype_digit($segments[1])) ? positive_id($segments[1], 'ID') : null;
    $data = in_array($method, ['POST', 'PUT', 'PATCH'], true) ? request_data() : [];

    // CSRF bootstrap for external API clients such as Postman.
    if ($resource === 'auth' && ($segments[1] ?? '') === 'csrf' && $method === 'GET') {
        api_ok(['csrf_token' => $_SESSION['csrf_token']], 'CSRF token issued.');
    }

    // ---------------------------- AUTH ----------------------------
    if ($resource === 'auth' && ($segments[1] ?? '') === 'register' && $method === 'POST') {
        $name = clean_text($data['full_name'] ?? '', 100);
        $email = strtolower(clean_text($data['email'] ?? '', 150));
        $phone = clean_text($data['phone'] ?? '', 25);
        $password = (string) ($data['password'] ?? '');
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
            api_fail(422, 'Please provide a name, valid email, and password with at least 8 characters.');
        }
        $stmt = $db->prepare('INSERT INTO users (full_name, email, phone, password, role) VALUES (?, ?, ?, ?, \'user\')');
        try {
            $stmt->execute([$name, $email, $phone ?: null, password_hash($password, PASSWORD_DEFAULT)]);
        } catch (PDOException $exception) {
            if ($exception->getCode() === '23000') api_fail(409, 'This email address is already registered.');
            throw $exception;
        }
        api_ok(['user_id' => (int) $db->lastInsertId()], 'Registration successful. You can now log in.', 201);
    }

    if ($resource === 'auth' && ($segments[1] ?? '') === 'login' && $method === 'POST') {
        $email = strtolower(clean_text($data['email'] ?? '', 150));
        $password = (string) ($data['password'] ?? '');
        if ($email === '' || $password === '') api_fail(422, 'Email and password are required.');
        $stmt = $db->prepare('SELECT user_id, full_name, email, password, role FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        $account = $stmt->fetch();
        if (!$account || !password_verify($password, $account['password'])) api_fail(401, 'Email or password is incorrect.');
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int) $account['user_id'];
        $_SESSION['full_name'] = $account['full_name'];
        $_SESSION['email'] = $account['email'];
        $_SESSION['role'] = $account['role'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        api_ok([
            'user_id' => (int) $account['user_id'],
            'full_name' => $account['full_name'],
            'email' => $account['email'],
            'role' => $account['role'],
            'csrf_token' => $_SESSION['csrf_token'],
        ], 'Login successful.');
    }

    if ($resource === 'auth' && ($segments[1] ?? '') === 'profile' && $method === 'GET') {
        $user = require_login_api();
        $stmt = $db->prepare('SELECT user_id, full_name, email, phone, role, created_at FROM users WHERE user_id = ?');
        $stmt->execute([$user['user_id']]);
        api_ok($stmt->fetch(), 'Profile retrieved.');
    }

    if ($resource === 'auth' && ($segments[1] ?? '') === 'logout' && $method === 'POST') {
        require_login_api();
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'] ?? '', $params['secure'], $params['httponly']);
        }
        session_destroy();
        api_ok(null, 'You have been logged out.');
    }

    // -------------------------- DASHBOARD -------------------------
    if ($resource === 'dashboard' && ($segments[1] ?? '') === 'stats' && $method === 'GET') {
        require_admin_api();
        $stats = [
            'packages' => (int) $db->query('SELECT COUNT(*) FROM packages')->fetchColumn(),
            'active_packages' => (int) $db->query("SELECT COUNT(*) FROM packages WHERE status = 'active'")->fetchColumn(),
            'destinations' => (int) $db->query('SELECT COUNT(*) FROM destinations')->fetchColumn(),
            'categories' => (int) $db->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
            'users' => (int) $db->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn(),
        ];
        api_ok($stats, 'Dashboard statistics retrieved.');
    }

    // -------------------------- PACKAGES ---------------------------
    if ($resource === 'packages') {
        if ($method === 'GET' && $id === null) {
            $isAdmin = current_user() && current_user()['role'] === 'admin';
            $sql = 'SELECT p.*, c.category_name, d.destination_name, d.country, d.city
                    FROM packages p
                    INNER JOIN categories c ON c.category_id = p.category_id
                    INNER JOIN destinations d ON d.destination_id = p.destination_id';
            if (!$isAdmin) $sql .= " WHERE p.status = 'active'";
            $sql .= ' ORDER BY p.created_at DESC, p.package_id DESC';
            api_ok($db->query($sql)->fetchAll(), 'Packages retrieved.');
        }
        if ($method === 'GET' && $id !== null) {
            $sql = 'SELECT p.*, c.category_name, d.destination_name, d.country, d.city
                    FROM packages p
                    INNER JOIN categories c ON c.category_id = p.category_id
                    INNER JOIN destinations d ON d.destination_id = p.destination_id
                    WHERE p.package_id = ?';
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            if (!$item) api_fail(404, 'Package not found.');
            if ($item['status'] !== 'active' && !(current_user() && current_user()['role'] === 'admin')) api_fail(404, 'Package not found.');
            api_ok($item, 'Package retrieved.');
        }
        if ($method === 'POST') {
            require_admin_api();
            $values = validate_package($data);
            $stmt = $db->prepare('INSERT INTO packages (category_id,destination_id,package_name,description,price,duration,facilities,itinerary,image_url,status) VALUES (?,?,?,?,?,?,?,?,?,?)');
            $stmt->execute($values);
            api_ok(['package_id' => (int) $db->lastInsertId()], 'Package created.', 201);
        }
        if (in_array($method, ['PUT', 'PATCH'], true) && $id !== null) {
            require_admin_api();
            $values = validate_package($data);
            $values[] = $id;
            $stmt = $db->prepare('UPDATE packages SET category_id=?,destination_id=?,package_name=?,description=?,price=?,duration=?,facilities=?,itinerary=?,image_url=?,status=? WHERE package_id=?');
            $stmt->execute($values);
            if (!$stmt->rowCount()) {
                $check = $db->prepare('SELECT package_id FROM packages WHERE package_id=?');
                $check->execute([$id]);
                if (!$check->fetch()) api_fail(404, 'Package not found.');
            }
            api_ok(null, 'Package updated.');
        }
        if ($method === 'DELETE' && $id !== null) {
            require_admin_api();
            $stmt = $db->prepare('DELETE FROM packages WHERE package_id=?');
            $stmt->execute([$id]);
            if (!$stmt->rowCount()) api_fail(404, 'Package not found.');
            api_ok(null, 'Package deleted.');
        }
    }

    // ------------------------- CATEGORIES --------------------------
    if ($resource === 'categories') {
        if ($method === 'GET' && $id === null) {
            $sql = 'SELECT c.*, COUNT(p.package_id) AS package_count FROM categories c LEFT JOIN packages p ON p.category_id=c.category_id GROUP BY c.category_id ORDER BY c.category_name';
            api_ok($db->query($sql)->fetchAll(), 'Categories retrieved.');
        }
        if ($method === 'GET' && $id !== null) {
            $stmt = $db->prepare('SELECT * FROM categories WHERE category_id=?');
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            if (!$item) api_fail(404, 'Category not found.');
            api_ok($item, 'Category retrieved.');
        }
        if ($method === 'POST') {
            require_admin_api();
            $values = validate_category($data);
            $stmt = $db->prepare('INSERT INTO categories (category_name,description,image_url) VALUES (?,?,?)');
            $stmt->execute($values);
            api_ok(['category_id' => (int) $db->lastInsertId()], 'Category created.', 201);
        }
        if (in_array($method, ['PUT', 'PATCH'], true) && $id !== null) {
            require_admin_api();
            $values = validate_category($data);
            $values[] = $id;
            $stmt = $db->prepare('UPDATE categories SET category_name=?,description=?,image_url=? WHERE category_id=?');
            $stmt->execute($values);
            $check = $db->prepare('SELECT category_id FROM categories WHERE category_id=?');
            $check->execute([$id]);
            if (!$check->fetch()) api_fail(404, 'Category not found.');
            api_ok(null, 'Category updated.');
        }
        if ($method === 'DELETE' && $id !== null) {
            require_admin_api();
            $stmt = $db->prepare('DELETE FROM categories WHERE category_id=?');
            $stmt->execute([$id]);
            if (!$stmt->rowCount()) api_fail(404, 'Category not found.');
            api_ok(null, 'Category deleted.');
        }
    }

    // ------------------------ DESTINATIONS -------------------------
    if ($resource === 'destinations') {
        if ($method === 'GET' && $id === null) {
            $sql = 'SELECT d.*, COUNT(p.package_id) AS package_count FROM destinations d LEFT JOIN packages p ON p.destination_id=d.destination_id GROUP BY d.destination_id ORDER BY d.destination_name';
            api_ok($db->query($sql)->fetchAll(), 'Destinations retrieved.');
        }
        if ($method === 'GET' && $id !== null) {
            $stmt = $db->prepare('SELECT * FROM destinations WHERE destination_id=?');
            $stmt->execute([$id]);
            $item = $stmt->fetch();
            if (!$item) api_fail(404, 'Destination not found.');
            api_ok($item, 'Destination retrieved.');
        }
        if ($method === 'POST') {
            require_admin_api();
            $values = validate_destination($data);
            $stmt = $db->prepare('INSERT INTO destinations (destination_name,country,city,description,image_url) VALUES (?,?,?,?,?)');
            $stmt->execute($values);
            api_ok(['destination_id' => (int) $db->lastInsertId()], 'Destination created.', 201);
        }
        if (in_array($method, ['PUT', 'PATCH'], true) && $id !== null) {
            require_admin_api();
            $values = validate_destination($data);
            $values[] = $id;
            $stmt = $db->prepare('UPDATE destinations SET destination_name=?,country=?,city=?,description=?,image_url=? WHERE destination_id=?');
            $stmt->execute($values);
            $check = $db->prepare('SELECT destination_id FROM destinations WHERE destination_id=?');
            $check->execute([$id]);
            if (!$check->fetch()) api_fail(404, 'Destination not found.');
            api_ok(null, 'Destination updated.');
        }
        if ($method === 'DELETE' && $id !== null) {
            require_admin_api();
            $stmt = $db->prepare('DELETE FROM destinations WHERE destination_id=?');
            $stmt->execute([$id]);
            if (!$stmt->rowCount()) api_fail(404, 'Destination not found.');
            api_ok(null, 'Destination deleted.');
        }
    }

    api_fail(404, 'API endpoint not found. Check the HTTP method and route.');
} catch (PDOException $exception) {
    error_log('[Tripora API] ' . $exception->getMessage());
    if ($exception->getCode() === '23000') {
        api_fail(409, 'This record is still related to another record or a unique value already exists.');
    }
    api_fail(500, 'A database error occurred. Check the MySQL service and database setup.');
} catch (Throwable $exception) {
    error_log('[Tripora API] ' . $exception->getMessage());
    api_fail(500, 'An unexpected server error occurred.');
}
