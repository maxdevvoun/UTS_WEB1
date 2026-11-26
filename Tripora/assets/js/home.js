document.addEventListener('DOMContentLoaded', async () => {
    const T = window.Tripora;
    const packageGrid = document.getElementById('packageGrid');
    const destinationGrid = document.getElementById('destinationGrid');
    const categoryFilters = document.getElementById('categoryFilters');
    const searchInput = document.getElementById('packageSearch');
    let packages = [];

    try {
        const [packageResult, destinationResult, categoryResult] = await Promise.all([
            T.api('packages'), T.api('destinations'), T.api('categories')
        ]);
        packages = packageResult.data || [];
        const destinations = destinationResult.data || [];
        const categories = categoryResult.data || [];

        packageGrid.innerHTML = packages.length
            ? packages.slice(0, 6).map(T.packageCard).join('')
            : '<div class="col-12"><div class="empty-state">No travel packages are available yet.</div></div>';

        destinationGrid.innerHTML = destinations.slice(0, 4).map(item => `
            <a class="destination-card" href="${T.base}/index.php#packages">
                <img src="${T.escapeHtml(T.imageUrl(item.image_url))}" alt="${T.escapeHtml(item.destination_name)}" loading="lazy" onerror="this.src='${T.base}/assets/images/placeholder.svg'">
                <span class="destination-shade"></span>
                <span class="destination-caption"><small>${T.escapeHtml(item.country)}</small><strong>${T.escapeHtml(item.destination_name)}</strong></span>
            </a>`).join('');

        categoryFilters.innerHTML = `<button class="filter-chip active" data-category="all">All trips</button>` +
            categories.map(item => `<button class="filter-chip" data-category="${Number(item.category_id)}">${T.escapeHtml(item.category_name)}</button>`).join('');
    } catch (error) {
        packageGrid.innerHTML = `<div class="col-12"><div class="alert alert-warning">${T.escapeHtml(error.message)} Please check that MySQL is running and the database has been imported.</div></div>`;
        destinationGrid.innerHTML = '';
    }

    function filterPackages() {
        const active = categoryFilters.querySelector('.filter-chip.active')?.dataset.category || 'all';
        const term = (searchInput.value || '').trim().toLowerCase();
        const filtered = packages.filter(item => {
            const matchCategory = active === 'all' || String(item.category_id) === active;
            const haystack = `${item.package_name} ${item.destination_name} ${item.country} ${item.category_name}`.toLowerCase();
            return matchCategory && haystack.includes(term);
        });
        packageGrid.innerHTML = filtered.length
            ? filtered.map(T.packageCard).join('')
            : '<div class="col-12"><div class="empty-state">No packages match your search. Try another keyword or category.</div></div>';
    }

    categoryFilters?.addEventListener('click', event => {
        const button = event.target.closest('.filter-chip');
        if (!button) return;
        categoryFilters.querySelectorAll('.filter-chip').forEach(item => item.classList.remove('active'));
        button.classList.add('active');
        filterPackages();
    });
    searchInput?.addEventListener('input', filterPackages);
});
