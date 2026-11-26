<?php
require_once __DIR__ . '/config/app.php';
$pageTitle = 'Discover More. Travel Beyond.';
$activePage = 'home';
$pageScripts = ['assets/js/home.js'];
require __DIR__ . '/includes/header.php';
?>
<section class="hero-section">
    <div class="hero-backdrop"></div>
    <div class="container hero-content">
        <div class="row align-items-center min-vh-hero">
            <div class="col-lg-7 col-xl-6">
                <span class="eyebrow eyebrow-light"><span></span> GO BEYOND THE ORDINARY</span>
                <h1>Your next adventure<br><em>starts here.</em></h1>
                <p class="hero-lead">Discover inspiring places, meaningful experiences, and journeys worth remembering.</p>
                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a class="btn btn-sand btn-lg" href="#packages">Explore packages <i class="bi bi-arrow-up-right ms-2"></i></a>
                    <a class="btn btn-hero-link btn-lg" href="#about">Get to know us <i class="bi bi-arrow-down-right ms-2"></i></a>
                </div>
                <div class="hero-footnote"><i class="bi bi-globe-americas"></i> Curated journeys across Indonesia and beyond</div>
            </div>
        </div>
    </div>
    <div class="hero-index">01 <span>/</span> EXPLORE</div>
</section>

<section class="intro-strip section-space-sm">
    <div class="container">
        <div class="row align-items-center g-3">
            <div class="col-lg-4"><span class="eyebrow">THE TRIPORA WAY</span></div>
            <div class="col-lg-8"><p class="intro-statement">Travel is more than a destination. <em>It is everything you discover along the way.</em></p></div>
        </div>
    </div>
</section>

<section class="section-space" id="destinations">
    <div class="container">
        <div class="section-heading d-flex flex-column flex-md-row justify-content-between align-items-md-end gap-3">
            <div><span class="eyebrow">PLACES TO FALL IN LOVE WITH</span><h2>Find your <em>somewhere.</em></h2><p class="section-subtitle">A few beautiful places to start imagining your next story.</p></div>
            <a class="text-link" href="#packages">Explore all trips <i class="bi bi-arrow-up-right"></i></a>
        </div>
        <div class="destination-grid" id="destinationGrid"><div class="loading-state">Discovering destinations...</div></div>
    </div>
</section>

<section class="section-space packages-section" id="packages">
    <div class="container">
        <div class="section-heading">
            <span class="eyebrow">MADE FOR YOUR NEXT CHAPTER</span>
            <h2>Journeys worth <em>taking.</em></h2>
            <p class="section-subtitle">Explore thoughtfully selected travel packages for every kind of traveler.</p>
        </div>
        <div class="package-tools d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
            <div class="filter-list" id="categoryFilters"><button class="filter-chip active" data-category="all">All trips</button></div>
            <div class="search-box"><i class="bi bi-search"></i><input type="search" id="packageSearch" placeholder="Search a destination or trip..." aria-label="Search packages"></div>
        </div>
        <div class="row g-4" id="packageGrid"><div class="col-12"><div class="loading-state">Loading travel packages...</div></div></div>
    </div>
</section>

<section class="why-section section-space">
    <div class="container">
        <div class="row align-items-end g-4 mb-5">
            <div class="col-lg-7"><span class="eyebrow eyebrow-light">WHY TRAVEL WITH US</span><h2>Good journeys are<br><em>never accidental.</em></h2></div>
            <div class="col-lg-5"><p>We believe the best trips feel effortless. Tripora brings destinations, details, and inspiration together so you can focus on the experience.</p></div>
        </div>
        <div class="row g-4">
            <div class="col-md-4"><div class="benefit-card"><span>01</span><i class="bi bi-compass"></i><h3>Thoughtful discovery</h3><p>Find destinations and experiences selected to inspire your next journey.</p></div></div>
            <div class="col-md-4"><div class="benefit-card"><span>02</span><i class="bi bi-map"></i><h3>Clear information</h3><p>Explore package details, duration, facilities, and itinerary in one place.</p></div></div>
            <div class="col-md-4"><div class="benefit-card"><span>03</span><i class="bi bi-heart"></i><h3>Made for memories</h3><p>Spend less time wondering where to go and more time enjoying the journey.</p></div></div>
        </div>
    </div>
</section>

<section class="about-section section-space" id="about">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6"><div class="about-image"><img src="https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=1200" alt="A peaceful lake surrounded by mountains" loading="lazy" onerror="this.src='assets/images/placeholder.svg'"><span class="about-image-note">GO SOMEWHERE<br>THAT MOVES YOU.</span></div></div>
            <div class="col-lg-6"><span class="eyebrow">A LITTLE ABOUT US</span><h2>We make room for<br><em>the unexpected.</em></h2><p class="about-copy">Tripora is a travel company concept built around one simple idea: every journey should be easy to explore and exciting to imagine. From local escapes to international discoveries, we bring travel information together in a simple, welcoming experience.</p><a class="btn btn-tripora mt-3" href="<?= e(app_url('register.php')) ?>">Start exploring <i class="bi bi-arrow-up-right ms-2"></i></a></div>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-4">
        <div><span class="eyebrow eyebrow-light">YOUR NEXT STORY IS WAITING</span><h2>Where will you go <em>next?</em></h2></div>
        <a class="btn btn-sand btn-lg" href="<?= e(app_url('register.php')) ?>">Join Tripora <i class="bi bi-arrow-up-right ms-2"></i></a>
    </div>
</section>
<?php require __DIR__ . '/includes/footer.php'; ?>
