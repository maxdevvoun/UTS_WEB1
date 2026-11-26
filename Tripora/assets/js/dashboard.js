document.addEventListener('DOMContentLoaded', async () => {
    const T = window.Tripora;
    const navItems = document.querySelectorAll('[data-dashboard-nav]');
    const panels = document.querySelectorAll('[data-dashboard-panel]');
    const modalEl = document.getElementById('recordModal');
    const modal = modalEl && window.bootstrap ? new bootstrap.Modal(modalEl) : null;
    const modalTitle = document.getElementById('recordModalTitle');
    const modalForm = document.getElementById('recordForm');
    const modalFields = document.getElementById('recordModalFields');
    let user = null;
    let categoryCache = [];
    let destinationCache = [];
    let editRecord = null;

    const field = (label, name, value = '', type = 'text', extra = '') => `
        <div class="col-12 ${extra}"><label class="form-label" for="field_${name}">${label}</label>
        <input class="form-control" id="field_${name}" name="${name}" type="${type}" value="${T.escapeHtml(value)}" ${name === 'price' ? 'min="0" step="1000"' : ''} ${['category_id','destination_id','package_name','category_name','destination_name','country','price'].includes(name) ? 'required' : ''}></div>`;
    const textarea = (label, name, value = '') => `<div class="col-12"><label class="form-label" for="field_${name}">${label}</label><textarea class="form-control" id="field_${name}" name="${name}" rows="3">${T.escapeHtml(value)}</textarea></div>`;
    const select = (label, name, options, value) => `<div class="col-md-6"><label class="form-label" for="field_${name}">${label}</label><select class="form-select" id="field_${name}" name="${name}" required>${options.map(o => `<option value="${o.id}" ${String(o.id) === String(value) ? 'selected' : ''}>${T.escapeHtml(o.name)}</option>`).join('')}</select></div>`;

    function setPanel(name) {
        panels.forEach(panel => panel.classList.toggle('d-none', panel.dataset.dashboardPanel !== name));
        navItems.forEach(item => item.classList.toggle('active', item.dataset.dashboardNav === name));
        const title = document.getElementById('dashboardPageTitle');
        if (title) title.textContent = ({ overview: 'Overview', packages: 'Travel packages', destinations: 'Destinations', categories: 'Categories', account: 'My account' })[name] || 'Dashboard';
        if (name === 'packages') loadPackagesTable();
        if (name === 'destinations') loadDestinationsTable();
        if (name === 'categories') loadCategoriesTable();
    }

    async function loadReferenceData() {
        const [categories, destinations] = await Promise.all([T.api('categories'), T.api('destinations')]);
        categoryCache = categories.data || [];
        destinationCache = destinations.data || [];
    }

    function renderTable(targetId, headers, rows, emptyText) {
        const target = document.getElementById(targetId);
        if (!target) return;
        target.innerHTML = rows.length ? `<div class="table-responsive"><table class="table align-middle tripora-table"><thead><tr>${headers.map(h => `<th>${h}</th>`).join('')}</tr></thead><tbody>${rows.join('')}</tbody></table></div>` : `<div class="empty-state">${emptyText}</div>`;
    }

    async function loadPackagesTable() {
        const target = document.getElementById('packagesTableArea');
        target.innerHTML = '<div class="loading-state">Loading packages...</div>';
        try {
            const result = await T.api('packages');
            renderTable('packagesTableArea', ['Package', 'Destination', 'Price', 'Status', 'Actions'], (result.data || []).map(item => `
                <tr><td><div class="table-main">${T.escapeHtml(item.package_name)}</div><small>${T.escapeHtml(item.category_name)}</small></td>
                <td>${T.escapeHtml(item.destination_name)}, ${T.escapeHtml(item.country)}</td><td>${T.money(item.price)}</td>
                <td><span class="status-pill ${item.status === 'active' ? 'status-active' : 'status-inactive'}">${T.escapeHtml(item.status)}</span></td>
                <td class="text-nowrap"><button class="btn btn-sm btn-light" data-edit="package" data-id="${item.package_id}"><i class="bi bi-pencil"></i></button> <button class="btn btn-sm btn-light text-danger" data-delete="package" data-id="${item.package_id}"><i class="bi bi-trash"></i></button></td></tr>`), 'No packages found. Add your first package.');
        } catch (error) { target.innerHTML = `<div class="alert alert-danger">${T.escapeHtml(error.message)}</div>`; }
    }

    async function loadCategoriesTable() {
        const target = document.getElementById('categoriesTableArea');
        target.innerHTML = '<div class="loading-state">Loading categories...</div>';
        try {
            const result = await T.api('categories');
            categoryCache = result.data || [];
            renderTable('categoriesTableArea', ['Category', 'Description', 'Packages', 'Actions'], categoryCache.map(item => `
                <tr><td><div class="table-main">${T.escapeHtml(item.category_name)}</div></td><td>${T.escapeHtml(item.description || '—')}</td><td>${Number(item.package_count || 0)}</td>
                <td class="text-nowrap"><button class="btn btn-sm btn-light" data-edit="category" data-id="${item.category_id}"><i class="bi bi-pencil"></i></button> <button class="btn btn-sm btn-light text-danger" data-delete="category" data-id="${item.category_id}"><i class="bi bi-trash"></i></button></td></tr>`), 'No categories found.');
        } catch (error) { target.innerHTML = `<div class="alert alert-danger">${T.escapeHtml(error.message)}</div>`; }
    }

    async function loadDestinationsTable() {
        const target = document.getElementById('destinationsTableArea');
        target.innerHTML = '<div class="loading-state">Loading destinations...</div>';
        try {
            const result = await T.api('destinations');
            destinationCache = result.data || [];
            renderTable('destinationsTableArea', ['Destination', 'Country', 'City', 'Packages', 'Actions'], destinationCache.map(item => `
                <tr><td><div class="table-main">${T.escapeHtml(item.destination_name)}</div></td><td>${T.escapeHtml(item.country)}</td><td>${T.escapeHtml(item.city || '—')}</td><td>${Number(item.package_count || 0)}</td>
                <td class="text-nowrap"><button class="btn btn-sm btn-light" data-edit="destination" data-id="${item.destination_id}"><i class="bi bi-pencil"></i></button> <button class="btn btn-sm btn-light text-danger" data-delete="destination" data-id="${item.destination_id}"><i class="bi bi-trash"></i></button></td></tr>`), 'No destinations found.');
        } catch (error) { target.innerHTML = `<div class="alert alert-danger">${T.escapeHtml(error.message)}</div>`; }
    }

    async function loadUserPackages() {
        const target = document.getElementById('userPackageGrid');
        try {
            const result = await T.api('packages');
            target.innerHTML = (result.data || []).slice(0, 3).map(T.packageCard).join('') || '<div class="empty-state">No packages are available.</div>';
        } catch (error) { target.innerHTML = `<div class="alert alert-warning">${T.escapeHtml(error.message)}</div>`; }
    }

    async function loadStats() {
        try {
            const result = await T.api('dashboard/stats');
            const values = result.data;
            Object.entries(values).forEach(([key, value]) => {
                const element = document.querySelector(`[data-stat="${key}"]`);
                if (element) element.textContent = value;
            });
        } catch (error) { T.toast(error.message, 'warning'); }
    }

    function openRecordModal(type, record = null) {
        editRecord = record;
        modalForm.dataset.type = type;
        modalForm.dataset.id = record ? record[`${type}_id`] : '';
        modalTitle.textContent = `${record ? 'Edit' : 'Add'} ${type}`;
        let html = '';
        if (type === 'category') {
            html = field('Category name', 'category_name', record?.category_name || '') + textarea('Description', 'description', record?.description || '') + field('Image URL or local asset path', 'image_url', record?.image_url || '');
        } else if (type === 'destination') {
            html = field('Destination name', 'destination_name', record?.destination_name || '') + field('Country', 'country', record?.country || '') + field('City', 'city', record?.city || '') + textarea('Description', 'description', record?.description || '') + field('Image URL or local asset path', 'image_url', record?.image_url || '');
        } else {
            const categoryOptions = categoryCache.map(x => ({ id: x.category_id, name: x.category_name }));
            const destinationOptions = destinationCache.map(x => ({ id: x.destination_id, name: `${x.destination_name} — ${x.country}` }));
            html = `<div class="row g-3">${field('Package name', 'package_name', record?.package_name || '')}${select('Category', 'category_id', categoryOptions, record?.category_id)}${select('Destination', 'destination_id', destinationOptions, record?.destination_id)}${field('Price (IDR)', 'price', record?.price || '', 'number')}${field('Duration', 'duration', record?.duration || '')}${field('Image URL or local asset path', 'image_url', record?.image_url || '')}${textarea('Description', 'description', record?.description || '')}${textarea('Facilities (separate each item with semicolon)', 'facilities', record?.facilities || '')}${textarea('Itinerary (one item per line)', 'itinerary', record?.itinerary || '')}<div class="col-md-6"><label class="form-label" for="field_status">Status</label><select class="form-select" id="field_status" name="status"><option value="active" ${record?.status !== 'inactive' ? 'selected' : ''}>Active</option><option value="inactive" ${record?.status === 'inactive' ? 'selected' : ''}>Inactive</option></select></div></div>`;
        }
        modalFields.innerHTML = type === 'package' ? html : `<div class="row g-3">${html}</div>`;
        modal?.show();
    }

    async function findRecord(type, id) {
        const resource = type === 'package' ? 'packages' : type === 'category' ? 'categories' : 'destinations';
        const result = await T.api(`${resource}/${id}`);
        return result.data;
    }

    document.querySelectorAll('.admin-table-card').forEach(table => table.addEventListener('click', async event => {
        const editButton = event.target.closest('[data-edit]');
        const deleteButton = event.target.closest('[data-delete]');
        if (editButton) {
            try {
                const record = await findRecord(editButton.dataset.edit, editButton.dataset.id);
                if (editButton.dataset.edit === 'package') await loadReferenceData();
                openRecordModal(editButton.dataset.edit, record);
            } catch (error) { T.toast(error.message, 'danger'); }
        }
        if (deleteButton) {
            const type = deleteButton.dataset.delete;
            const id = deleteButton.dataset.id;
            if (!window.confirm(`Delete this ${type}? This action cannot be undone.`)) return;
            const resource = type === 'package' ? 'packages' : type === 'category' ? 'categories' : 'destinations';
            try {
                await T.api(`${resource}/${id}`, { method: 'DELETE' });
                T.toast(`${type[0].toUpperCase() + type.slice(1)} deleted.`);
                if (type === 'package') loadPackagesTable();
                if (type === 'category') loadCategoriesTable();
                if (type === 'destination') loadDestinationsTable();
                loadStats();
            } catch (error) { T.toast(error.message, 'danger'); }
        }
    }));

    document.querySelectorAll('[data-add-type]').forEach(button => button.addEventListener('click', async () => {
        const type = button.dataset.addType;
        try {
            if (type === 'package') await loadReferenceData();
            openRecordModal(type);
        } catch (error) { T.toast(error.message, 'danger'); }
    }));

    modalForm?.addEventListener('submit', async event => {
        event.preventDefault();
        const type = modalForm.dataset.type;
        const id = modalForm.dataset.id;
        const resource = type === 'package' ? 'packages' : type === 'category' ? 'categories' : 'destinations';
        const body = Object.fromEntries(new FormData(modalForm).entries());
        if (type === 'package') {
            body.category_id = Number(body.category_id);
            body.destination_id = Number(body.destination_id);
            body.price = Number(body.price);
        }
        try {
            await T.api(id ? `${resource}/${id}` : resource, { method: id ? 'PUT' : 'POST', body });
            modal?.hide();
            T.toast(`${type[0].toUpperCase() + type.slice(1)} ${id ? 'updated' : 'created'} successfully.`);
            if (type === 'package') loadPackagesTable();
            if (type === 'category') loadCategoriesTable();
            if (type === 'destination') loadDestinationsTable();
            loadStats();
        } catch (error) { T.toast(error.message, 'danger'); }
    });

    navItems.forEach(item => item.addEventListener('click', () => setPanel(item.dataset.dashboardNav)));
    document.getElementById('dashboardLogout')?.addEventListener('click', async event => {
        event.preventDefault();
        try { await T.api('auth/logout', { method: 'POST', body: {} }); }
        catch { /* logout.php is a local fallback */ }
        window.location.href = `${T.base}/login.php?logged_out=1`;
    });

    try {
        const profile = await T.api('auth/profile');
        user = profile.data;
        document.getElementById('dashboardGreeting').textContent = user.full_name.split(' ')[0];
        document.getElementById('dashboardRole').textContent = user.role === 'admin' ? 'Administrator' : 'Tripora member';
        document.getElementById('accountName').textContent = user.full_name;
        document.getElementById('accountEmail').textContent = user.email;
        document.getElementById('accountPhone').textContent = user.phone || 'Not added';
        document.getElementById('accountRole').textContent = user.role;

        if (user.role === 'admin') {
            document.querySelectorAll('[data-admin-only]').forEach(el => el.classList.remove('d-none'));
            document.getElementById('userWelcomePanel').classList.add('d-none');
            await loadStats();
            await loadPackagesTable();
        } else {
            document.querySelectorAll('[data-admin-only]').forEach(el => el.classList.add('d-none'));
            document.getElementById('adminOverviewPanel').classList.add('d-none');
            document.getElementById('userWelcomePanel').classList.remove('d-none');
            document.querySelectorAll('[data-dashboard-nav="overview"], [data-dashboard-nav="account"]').forEach(el => el.classList.remove('d-none'));
            document.querySelectorAll('[data-dashboard-nav="overview"], [data-dashboard-nav="account"]').forEach(el => el.classList.toggle('active', el.dataset.dashboardNav === 'overview'));
            await loadUserPackages();
        }
    } catch (error) {
        T.toast(error.message, 'danger');
        window.setTimeout(() => { window.location.href = `${T.base}/login.php`; }, 900);
    }
});
