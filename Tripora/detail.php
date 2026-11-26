<?php
require_once __DIR__ . '/config/app.php';
$pageTitle = 'Package details';
$pageScripts = ['assets/js/detail.js'];
require __DIR__ . '/includes/header.php';
?>
<section class="detail-page section-space">
    <div class="container" id="detailContent">
        <div class="loading-state">Loading package details...</div>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
