document.addEventListener('DOMContentLoaded', async () => {
    const T = window.Tripora;
    const content = document.getElementById('detailContent');
    const id = new URLSearchParams(window.location.search).get('id');
    if (!id || !/^\d+$/.test(id)) {
        content.innerHTML = '<div class="empty-state">The package you are looking for could not be found. <a href="index.php#packages">Browse all packages</a>.</div>';
        return;
    }
    try {
        const result = await T.api(`packages/${id}`);
        const item = result.data;
        document.title = `${item.package_name} | Tripora`;
        const facilities = String(item.facilities || '').split(';').map(x => x.trim()).filter(Boolean);
        const itinerary = String(item.itinerary || '').split('\n').map(x => x.trim()).filter(Boolean);
        content.innerHTML = `
            <div class="detail-breadcrumb"><a href="index.php">Home</a><i class="bi bi-chevron-right"></i><a href="index.php#packages">Packages</a><i class="bi bi-chevron-right"></i><span>${T.escapeHtml(item.package_name)}</span></div>
            <div class="detail-hero-image"><img src="${T.escapeHtml(T.imageUrl(item.image_url))}" alt="${T.escapeHtml(item.package_name)}" onerror="this.src='${T.base}/assets/images/placeholder.svg'"></div>
            <div class="row g-5 mt-1">
                <div class="col-lg-8">
                    <span class="eyebrow">${T.escapeHtml(item.category_name)} · ${T.escapeHtml(item.destination_name)}, ${T.escapeHtml(item.country)}</span>
                    <h1 class="detail-title">${T.escapeHtml(item.package_name)}</h1>
                    <p class="detail-description">${T.escapeHtml(item.description || 'Discover this destination with a thoughtfully arranged Tripora experience.')}</p>
                    <section class="detail-section"><h2>What is included</h2><div class="facility-list">${facilities.length ? facilities.map(x => `<span><i class="bi bi-check-circle-fill"></i>${T.escapeHtml(x)}</span>`).join('') : '<span>Contact Tripora for package details.</span>'}</div></section>
                    <section class="detail-section"><h2>Trip itinerary</h2><div class="itinerary-list">${itinerary.length ? itinerary.map((x, i) => `<div class="itinerary-item"><span class="itinerary-number">${String(i + 1).padStart(2, '0')}</span><p>${T.escapeHtml(x)}</p></div>`).join('') : '<p>Itinerary information will be announced.</p>'}</div></section>
                </div>
                <div class="col-lg-4"><aside class="booking-card"><span class="eyebrow">PACKAGE PRICE</span><div class="detail-price">${T.money(item.price)}<small> / person</small></div><div class="booking-info"><span><i class="bi bi-clock"></i> Duration</span><strong>${T.escapeHtml(item.duration || 'Contact us')}</strong></div><div class="booking-info"><span><i class="bi bi-geo-alt"></i> Destination</span><strong>${T.escapeHtml(item.destination_name)}</strong></div><a class="btn btn-tripora w-100 mt-4" href="${T.base}/register.php">Create an account <i class="bi bi-arrow-up-right ms-1"></i></a><p class="booking-note">This is an informational project demo. Booking and payment are not enabled.</p></aside></div>
            </div>`;
    } catch (error) {
        content.innerHTML = `<div class="empty-state">${T.escapeHtml(error.message)} <a href="index.php#packages">Return to packages</a>.</div>`;
    }
});
