<?php
/** Shared application bootstrap and URL/session helpers. */
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/');
if (preg_match('#/api/index\.php$#', $scriptName)) {
    $basePath = preg_replace('#/api/index\.php$#', '', $scriptName);
} else {
    $basePath = rtrim(dirname($scriptName), '/');
}
$basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;
if (!defined('BASE_URL')) {
    define('BASE_URL', $basePath);
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function app_url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return BASE_URL . ($path !== '' ? '/' . $path : '/');
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function current_user(): ?array
{
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    return [
        'user_id' => (int) $_SESSION['user_id'],
        'full_name' => (string) ($_SESSION['full_name'] ?? ''),
        'email' => (string) ($_SESSION['email'] ?? ''),
        'role' => (string) ($_SESSION['role'] ?? 'user'),
    ];
}

function require_page_login(): void
{
    if (!current_user()) {
        header('Location: ' . app_url('login.php'));
        exit;
    }
}
