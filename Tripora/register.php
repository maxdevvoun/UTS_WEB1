<?php
require_once __DIR__ . '/config/app.php';
if (current_user()) { header('Location: ' . app_url('dashboard.php')); exit; }
$pageTitle = 'Create account';
$pageScripts = ['assets/js/auth.js'];
require __DIR__ . '/includes/header.php';
?>
<section class="auth-page">
    <div class="container-fluid px-0"><div class="row g-0 min-vh-auth">
        <div class="col-lg-6 auth-visual auth-visual-register"><div class="auth-visual-content"><a class="auth-mark" href="<?= e(app_url('index.php')) ?>">tripora<span>.</span></a><div><span class="eyebrow eyebrow-light">MAKE THE WORLD YOURS</span><h1>Every great story<br><em>starts somewhere.</em></h1><p>Create an account and start discovering new places to go.</p></div><span class="auth-visual-caption">YOUR NEXT ADVENTURE STARTS HERE.</span></div></div>
        <div class="col-lg-6 auth-form-side"><div class="auth-form-wrap">
            <span class="eyebrow">JOIN THE JOURNEY</span><h2>Create an account</h2><p class="auth-subtitle">A few details are all it takes to get started.</p>
            <form id="registerForm" novalidate>
                <div class="mb-3"><label class="form-label" for="full_name">Full name</label><input class="form-control" id="full_name" name="full_name" placeholder="Your full name" autocomplete="name" required></div>
                <div class="mb-3"><label class="form-label" for="email">Email address</label><input class="form-control" id="email" name="email" type="email" placeholder="you@example.com" autocomplete="email" required></div>
                <div class="mb-3"><label class="form-label" for="phone">Phone number <span class="optional-label">(optional)</span></label><input class="form-control" id="phone" name="phone" type="tel" placeholder="+62 8xx xxxx xxxx" autocomplete="tel"></div>
                <div class="mb-3"><label class="form-label" for="password">Password</label><div class="password-field"><input class="form-control" id="password" name="password" type="password" placeholder="At least 8 characters" autocomplete="new-password" required><button type="button" data-toggle-password="password" aria-label="Show password"><i class="bi bi-eye"></i></button></div></div>
                <div class="mb-4"><label class="form-label" for="confirm_password">Confirm password</label><input class="form-control" id="confirm_password" name="confirm_password" type="password" placeholder="Enter your password again" autocomplete="new-password" required></div>
                <button class="btn btn-tripora btn-lg w-100" type="submit">Create account <i class="bi bi-arrow-right ms-2"></i></button>
            </form>
            <p class="auth-switch">Already have an account? <a href="<?= e(app_url('login.php')) ?>">Log in</a></p>
        </div></div>
    </div></div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
