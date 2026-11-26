(() => {
    const base = window.TRIPORA_BASE || '';
    const apiRoot = `${base}/api/index.php`;

    function escapeHtml(value) {
        return String(value ?? '').replace(/[&<>"']/g, char => ({
            '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'
        }[char]));
    }

    function imageUrl(value) {
        const image = String(value || '').trim();
        if (/^https?:\/\//i.test(image)) return image;
        if (image.startsWith('assets/')) return `${base}/${image}`;
        return `${base}/assets/images/placeholder.svg`;
    }

    function money(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency', currency: 'IDR', maximumFractionDigits: 0
        }).format(Number(value || 0));
    }

    async function api(path, options = {}) {
        const method = (options.method || 'GET').toUpperCase();
        const headers = { 'Accept': 'application/json', ...(options.headers || {}) };
        const config = { method, credentials: 'same-origin', headers };
        if (['POST', 'PUT', 'PATCH', 'DELETE'].includes(method)) {
            headers['Content-Type'] = 'application/json';
            headers['X-CSRF-Token'] = window.TRIPORA_CSRF || '';
        }
        if (options.body !== undefined) config.body = JSON.stringify(options.body);
        const response = await fetch(`${apiRoot}/${path.replace(/^\//, '')}`, config);
        let payload;
        try { payload = await response.json(); }
        catch { throw new Error('Server returned an invalid response. Check PHP or Apache configuration.'); }
        if (!response.ok || payload.success === false) {
            throw new Error(payload.message || `Request failed (${response.status}).`);
        }
        return payload;
    }

    function toast(message, type = 'success') {
        const container = document.getElementById('toastContainer');
        if (!container || !window.bootstrap) { window.alert(message); return; }
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-bg-${type} border-0`;
        toastEl.role = 'status';
        toastEl.innerHTML = `<div class="d-flex"><div class="toast-body">${escapeHtml(message)}</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button></div>`;
        container.appendChild(toastEl);
        const instance = new bootstrap.Toast(toastEl, { delay: 3200 });
        instance.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }

    function packageCard(item) {
        const image = imageUrl(item.image_url);
        const destination = [item.destination_name, item.country].filter(Boolean).join(', ');
        return `<div class="col-md-6 col-lg-4"><article class="package-card h-100">
            <a class="package-image-wrap" href="${base}/detail.php?id=${Number(item.package_id)}">
                <img class="package-image" src="${escapeHtml(image)}" alt="${escapeHtml(item.package_name)}" loading="lazy" onerror="this.src='${base}/assets/images/placeholder.svg'">
                <span class="image-tag">${escapeHtml(item.category_name || 'Tripora selection')}</span>
            </a>
            <div class="package-card-body">
                <div class="package-location"><i class="bi bi-geo-alt"></i> ${escapeHtml(destination || 'Explore with Tripora')}</div>
                <h3><a href="${base}/detail.php?id=${Number(item.package_id)}">${escapeHtml(item.package_name)}</a></h3>
                <div class="package-meta"><span><i class="bi bi-clock"></i> ${escapeHtml(item.duration || 'Flexible trip')}</span><span class="package-price">${money(item.price)}</span></div>
                <a class="package-link" href="${base}/detail.php?id=${Number(item.package_id)}">Explore package <i class="bi bi-arrow-up-right"></i></a>
            </div>
        </article></div>`;
    }

    window.Tripora = { base, api, escapeHtml, imageUrl, money, toast, packageCard };
})();
