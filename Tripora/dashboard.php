<?php
require_once __DIR__ . '/config/app.php';
require_page_login();
$pageTitle = 'Dashboard';
$pageScripts = ['assets/js/dashboard.js'];
require __DIR__ . '/includes/header.php';
?>
<section class="dashboard-page">
    <div class="container py-4 py-lg-5">
        <div class="dashboard-topline mb-4"><div><span class="eyebrow">YOUR TRIPORA SPACE</span><h1 id="dashboardPageTitle">Overview</h1></div><div class="dashboard-user-chip"><span class="user-avatar"><i class="bi bi-person"></i></span><div><strong id="dashboardGreeting">Traveler</strong><small id="dashboardRole">Member</small></div></div></div>
        <div class="dashboard-layout">
            <aside class="dashboard-sidebar">
                <div class="sidebar-label">MENU</div>
                <button class="dashboard-nav-link active" data-dashboard-nav="overview"><i class="bi bi-grid-1x2"></i> Overview</button>
                <button class="dashboard-nav-link d-none" data-admin-only data-dashboard-nav="packages"><i class="bi bi-suitcase-lg"></i> Packages</button>
                <button class="dashboard-nav-link d-none" data-admin-only data-dashboard-nav="destinations"><i class="bi bi-geo-alt"></i> Destinations</button>
                <button class="dashboard-nav-link d-none" data-admin-only data-dashboard-nav="categories"><i class="bi bi-tags"></i> Categories</button>
                <button class="dashboard-nav-link" data-dashboard-nav="account"><i class="bi bi-person-circle"></i> My account</button>
                <div class="sidebar-divider"></div>
                <a class="dashboard-nav-link logout-nav" href="<?= e(app_url('logout.php')) ?>" id="dashboardLogout"><i class="bi bi-box-arrow-left"></i> Log out</a>
            </aside>
            <div class="dashboard-content">
                <section data-dashboard-panel="overview">
                    <div id="adminOverviewPanel">
                        <div class="dashboard-welcome-card"><div><span class="eyebrow eyebrow-light">TRIPORA CONTROL ROOM</span><h2>Keep every journey<br><em>in motion.</em></h2><p>Manage the travel information that visitors discover on Tripora.</p></div><div class="welcome-icon"><i class="bi bi-globe-asia-australia"></i></div></div>
                        <div class="row g-3 stats-row">
                            <div class="col-6 col-xl"><div class="stat-card"><span>Total packages</span><strong data-stat="packages">—</strong><i class="bi bi-suitcase"></i></div></div>
                            <div class="col-6 col-xl"><div class="stat-card"><span>Active packages</span><strong data-stat="active_packages">—</strong><i class="bi bi-check-circle"></i></div></div>
                            <div class="col-6 col-xl"><div class="stat-card"><span>Destinations</span><strong data-stat="destinations">—</strong><i class="bi bi-geo"></i></div></div>
                            <div class="col-6 col-xl"><div class="stat-card"><span>Categories</span><strong data-stat="categories">—</strong><i class="bi bi-tags"></i></div></div>
                            <div class="col-6 col-xl"><div class="stat-card"><span>Members</span><strong data-stat="users">—</strong><i class="bi bi-people"></i></div></div>
                        </div>
                        <div class="dashboard-shortcuts"><div><h3>Manage your content</h3><p>Keep destination and package information accurate and up to date.</p></div><div class="d-flex flex-wrap gap-2"><button class="btn btn-outline-tripora" data-dashboard-nav="packages">Manage packages</button><a class="btn btn-tripora" href="<?= e(app_url('index.php')) ?>" target="_blank">View website <i class="bi bi-arrow-up-right ms-1"></i></a></div></div>
                    </div>
                    <div id="userWelcomePanel" class="d-none">
                        <div class="dashboard-welcome-card"><div><span class="eyebrow eyebrow-light">WELCOME TO TRIPORA</span><h2>Your next chapter<br><em>starts out there.</em></h2><p>Browse travel ideas and find a place that speaks to you.</p><a class="btn btn-sand mt-2" href="<?= e(app_url('index.php#packages')) ?>">Explore packages <i class="bi bi-arrow-up-right ms-1"></i></a></div><div class="welcome-icon"><i class="bi bi-compass"></i></div></div>
                        <div class="dashboard-section-head mt-5"><div><span class="eyebrow">A LITTLE INSPIRATION</span><h3>Explore our packages</h3></div></div>
                        <div class="row g-3" id="userPackageGrid"><div class="loading-state">Loading recommendations...</div></div>
                    </div>
                </section>
                <section class="dashboard-panel d-none" data-dashboard-panel="packages">
                    <div class="dashboard-section-head"><div><span class="eyebrow">CONTENT MANAGEMENT</span><h3>Travel packages</h3><p>Manage the packages displayed on the public website.</p></div><button class="btn btn-tripora" data-add-type="package"><i class="bi bi-plus-lg me-1"></i> Add package</button></div>
                    <div id="packagesTableArea" class="admin-table-card"><div class="loading-state">Loading packages...</div></div>
                </section>
                <section class="dashboard-panel d-none" data-dashboard-panel="destinations">
                    <div class="dashboard-section-head"><div><span class="eyebrow">CONTENT MANAGEMENT</span><h3>Destinations</h3><p>Maintain destination information and images.</p></div><button class="btn btn-tripora" data-add-type="destination"><i class="bi bi-plus-lg me-1"></i> Add destination</button></div>
                    <div id="destinationsTableArea" class="admin-table-card"><div class="loading-state">Loading destinations...</div></div>
                </section>
                <section class="dashboard-panel d-none" data-dashboard-panel="categories">
                    <div class="dashboard-section-head"><div><span class="eyebrow">CONTENT MANAGEMENT</span><h3>Categories</h3><p>Organize packages by travel experience.</p></div><button class="btn btn-tripora" data-add-type="category"><i class="bi bi-plus-lg me-1"></i> Add category</button></div>
                    <div id="categoriesTableArea" class="admin-table-card"><div class="loading-state">Loading categories...</div></div>
                </section>
                <section class="dashboard-panel d-none" data-dashboard-panel="account">
                    <div class="dashboard-section-head"><div><span class="eyebrow">YOUR DETAILS</span><h3>My account</h3><p>Your Tripora account information.</p></div></div>
                    <div class="account-card"><div class="account-avatar"><i class="bi bi-person"></i></div><div class="account-detail"><span>Full name</span><strong id="accountName">—</strong></div><div class="account-detail"><span>Email address</span><strong id="accountEmail">—</strong></div><div class="account-detail"><span>Phone number</span><strong id="accountPhone">—</strong></div><div class="account-detail"><span>Account role</span><strong id="accountRole">—</strong></div></div>
                </section>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="recordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable"><div class="modal-content tripora-modal">
        <form id="recordForm"><div class="modal-header"><div><span class="eyebrow">TRIPORA CONTENT</span><h2 class="modal-title fs-4" id="recordModalTitle">Add record</h2></div><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
            <div class="modal-body"><div id="recordModalFields"></div></div>
            <div class="modal-footer"><button type="button" class="btn btn-outline-tripora" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-tripora">Save changes</button></div>
        </form>
    </div></div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
