/**
 * posModule.js
 * Handle category switching & product search tanpa reload halaman.
 */
const posModule = (() => {

    let _currentCategory = document.querySelector('.category-tab.active-tab')?.dataset.category || 'food';

    function switchCategory(category) {
        _currentCategory = category;

        // Update tampilan tab aktif
        document.querySelectorAll('.category-tab').forEach(btn => {
            const isActive = btn.dataset.category === category;
            btn.classList.toggle('text-[#1C1917]', isActive);
            btn.classList.toggle('text-[#78716C]', !isActive);
            // Toggle underline aktif
            if (isActive) {
                btn.classList.add('after:absolute', 'after:bottom-0', 'after:left-0', 'after:right-0', 'after:h-0.5', 'after:bg-primary');
            } else {
                btn.classList.remove('after:absolute', 'after:bottom-0', 'after:left-0', 'after:right-0', 'after:h-0.5', 'after:bg-primary');
            }
        });

        loadProducts({ category });
    }

    function loadProducts(params = {}) {
        const grid = document.getElementById('product-grid');
        if (!grid) return;

        // Merge dengan category saat ini
        const query = Object.assign({ category: _currentCategory }, params);
        const url = new URL(window.location.href);
        Object.entries(query).forEach(([k, v]) => {
            if (v) url.searchParams.set(k, v);
            else url.searchParams.delete(k);
        });

        // Loading state
        grid.style.opacity = '0.5';
        grid.style.pointerEvents = 'none';

        fetch(url.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            // Parse HTML response dan ambil product-grid saja
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newGrid = doc.getElementById('product-grid');
            if (newGrid) {
                grid.innerHTML = newGrid.innerHTML;
            }

            // Update item count label
            const countEl = document.querySelector('h2.text-xl + span');
            const newCount = doc.querySelector('h2.text-xl + span');
            if (countEl && newCount) countEl.textContent = newCount.textContent;

            // Update URL tanpa reload
            window.history.pushState({}, '', url.toString());
        })
        .catch(err => console.error('Load products error:', err))
        .finally(() => {
            grid.style.opacity = '1';
            grid.style.pointerEvents = 'auto';
        });
    }

    return { switchCategory, loadProducts };
})();

window.posModule = posModule;