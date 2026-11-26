</main>
<footer class="site-footer">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-5">
                <a class="footer-brand" href="<?= e(app_url('index.php')) ?>"><img src="<?= e(app_url('assets/images/tripora-mark.svg')) ?>" alt="" width="34" height="34"> tripora<span>.</span></a>
                <p class="footer-copy mt-3 mb-0">Thoughtful journeys, meaningful memories. Find your next story with Tripora.</p>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Explore</h6>
                <a href="<?= e(app_url('index.php#destinations')) ?>">Destinations</a>
                <a href="<?= e(app_url('index.php#packages')) ?>">Packages</a>
                <a href="<?= e(app_url('index.php#about')) ?>">About us</a>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Account</h6>
                <a href="<?= e(app_url('login.php')) ?>">Log in</a>
                <a href="<?= e(app_url('register.php')) ?>">Register</a>
                <a href="<?= e(app_url('dashboard.php')) ?>">Dashboard</a>
            </div>
            <div class="col-lg-3">
                <h6>Get in touch</h6>
                <p class="footer-copy mb-1"><i class="bi bi-envelope me-2"></i>hello@tripora.local</p>
                <p class="footer-copy"><i class="bi bi-geo-alt me-2"></i>Bandung, Indonesia</p>
            </div>
        </div>
        <div class="footer-bottom mt-4 pt-3 d-flex flex-column flex-md-row justify-content-between gap-2">
            <span>© <?= date('Y') ?> Tripora. All rights reserved.</span>
            <span>@Copyright by 24552011094_Max Devon_TIF RP 24 CID B</span>
        </div>
    </div>
</footer>
<div class="toast-container position-fixed bottom-0 end-0 p-3" id="toastContainer"></div>
<script>
window.TRIPORA_BASE = <?= json_encode(BASE_URL, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
window.TRIPORA_CSRF = <?= json_encode($_SESSION['csrf_token'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= e(app_url('assets/js/common.js')) ?>"></script>
<?php foreach (($pageScripts ?? []) as $script): ?>
<script src="<?= e(app_url($script)) ?>"></script>
<?php endforeach; ?>
</body>
</html>
