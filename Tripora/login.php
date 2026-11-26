<?php
require_once __DIR__ . '/config/app.php';
if (current_user()) { header('Location: ' . app_url('dashboard.php')); exit; }
$pageTitle = 'Log in';
$pageScripts = ['assets/js/auth.js'];
require __DIR__ . '/includes/header.php';
?>
<section class="auth-page">
    <div class="container-fluid px-0"><div class="row g-0 min-vh-auth">
        <div class="col-lg-6 auth-visual auth-visual-login"><div class="auth-visual-content"><a class="auth-mark" href="<?= e(app_url('index.php')) ?>">tripora<span>.</span></a><div><span class="eyebrow eyebrow-light">A WORLD OF POSSIBILITIES</span><h1>Let the journey<br><em>continue.</em></h1><p>Sign in and pick up where your curiosity left off.</p></div><span class="auth-visual-caption">DISCOVER MORE. TRAVEL BEYOND.</span></div></div>
        <div class="col-lg-6 auth-form-side"><div class="auth-form-wrap">
            <span class="eyebrow">WELCOME BACK</span><h2>Log in to Tripora</h2><p class="auth-subtitle">Enter your account details to continue exploring.</p>
            <?php if (isset($_GET['registered'])): ?><div class="alert alert-success">Your account has been created. Please log in.</div><?php endif; ?>
            <?php if (isset($_GET['logged_out'])): ?><div class="alert alert-info">You have been logged out safely.</div><?php endif; ?>
            <form id="loginForm" novalidate>
                <div class="mb-3"><label class="form-label" for="email">Email address</label><input class="form-control form-control-lg" id="email" name="email" type="email" placeholder="you@example.com" autocomplete="email" required></div>
                <div class="mb-4"><label class="form-label" for="password">Password</label><div class="password-field"><input class="form-control form-control-lg" id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required><button type="button" data-toggle-password="password" aria-label="Show password"><i class="bi bi-eye"></i></button></div></div>
                <button class="btn btn-tripora btn-lg w-100" type="submit">Log in <i class="bi bi-arrow-right ms-2"></i></button>
            </form>
            <p class="auth-switch">Don't have an account? <a href="<?= e(app_url('register.php')) ?>">Create one</a></p>
            <div class="demo-credential"><i class="bi bi-info-circle"></i><div><strong>Demo administrator</strong><br><span>admin@tripora.local · TriporaAdmin!2026</span></div></div>
        </div></div>
    </div></div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
