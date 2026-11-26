<?php
require_once __DIR__ . '/../config/app.php';
$pageTitle = $pageTitle ?? 'Discover More. Travel Beyond.';
$activePage = $activePage ?? '';
$user = current_user();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#173f35">
    <meta name="description" content="Discover thoughtful travel experiences with Tripora. Explore destinations and curated travel packages.">
    <title><?= e($pageTitle) ?> | Tripora</title>
    <link rel="icon" type="image/svg+xml" href="<?= e(app_url('assets/images/tripora-mark.svg')) ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= e(app_url('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-tripora sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?= e(app_url('index.php')) ?>">
            <img src="<?= e(app_url('assets/images/tripora-mark.svg')) ?>" alt="Tripora" width="36" height="36">
            <span>tripora<span class="brand-period">.</span></span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto align-items-lg-center gap-lg-2">
                <li class="nav-item"><a class="nav-link <?= $activePage === 'home' ? 'active' : '' ?>" href="<?= e(app_url('index.php')) ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('index.php#destinations')) ?>">Destinations</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('index.php#packages')) ?>">Packages</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= e(app_url('index.php#about')) ?>">About us</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2 nav-actions">
                <?php if ($user): ?>
                    <a class="btn btn-outline-tripora btn-sm" href="<?= e(app_url('dashboard.php')) ?>"><i class="bi bi-grid me-1"></i> Dashboard</a>
                    <a class="btn btn-tripora btn-sm" href="<?= e(app_url('logout.php')) ?>">Logout</a>
                <?php else: ?>
                    <a class="btn btn-link text-decoration-none login-link" href="<?= e(app_url('login.php')) ?>">Log in</a>
                    <a class="btn btn-tripora btn-sm px-3" href="<?= e(app_url('register.php')) ?>">Create account <i class="bi bi-arrow-up-right ms-1"></i></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<main>
