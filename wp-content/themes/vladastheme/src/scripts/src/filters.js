document.addEventListener('DOMContentLoaded', function () {
    'use strict';
    const results = document.querySelector('#apartments-results');
    if (!results || typeof stanoviAjax === 'undefined') { return; }
    const filters = document.querySelectorAll('.apartment-filter');
    const loader = document.querySelector('#apartments-loader');
    const count = document.querySelector('#apartments-count');
    const summary = document.querySelector('#apartments-summary-text');
    const pagination = document.querySelector('#apartments-pagination');
    const reset = document.querySelector('#reset-apartment-filters');
    const storageKey = 'luxor_apartment_filters';
    const keys = ['tip_stana', 'sprat', 'projekat', 'status'];
    let controller, sequence = 0;
    const feedback = document.createElement('p'); feedback.className = 'small text-muted'; feedback.setAttribute('role', 'status'); results.before(feedback);
    results.setAttribute('aria-live', 'polite');
    function values(key) { return Array.from(filters).filter(input => input.name === key + '[]' && input.checked).map(input => input.value); }
    function save() {
        const data = {}; keys.forEach(key => { data[key] = values(key); });
        try { localStorage.setItem(storageKey, JSON.stringify(data)); } catch (error) { /* Session remains usable when storage is blocked. */ }
    }
    function busy(active) {
        results.setAttribute('aria-busy', String(active)); results.style.opacity = active ? '0.5' : '1'; results.style.display = '';
        if (loader) { loader.style.display = active ? 'flex' : 'none'; }
    }
    function load(page) {
        if (controller) { controller.abort(); }
        controller = new AbortController(); const request = ++sequence;
        const data = new FormData(); data.append('action', 'filter_stanovi'); data.append('nonce', stanoviAjax.nonce); data.append('page', String(page || 1));
        keys.forEach(key => values(key).forEach(value => data.append(key + '[]', value)));
        busy(true); feedback.textContent = 'Učitavanje stanova…';
        let timedOut = false; const activeController = controller; const timer = setTimeout(function () { timedOut = true; activeController.abort(); }, 15000);
        fetch(stanoviAjax.ajax_url, {method: 'POST', body: data, signal: controller.signal, cache: 'no-store'})
            .then(response => { if (!response.ok) { throw new Error('HTTP ' + response.status); } return response.json(); })
            .then(response => {
                if (request !== sequence) { return; }
                if (!response.success || !response.data || typeof response.data.html !== 'string') { throw new Error('Neispravan odgovor'); }
                results.innerHTML = response.data.html;
                if (count) { count.textContent = 'Pronađeno: ' + response.data.count; }
                if (summary) { summary.textContent = 'Prikazano ' + response.data.shown + ' od ' + response.data.count + ' stanova'; }
                if (pagination) { pagination.innerHTML = response.data.pagination || ''; }
                feedback.textContent = response.data.count ? '' : 'Nema stanova za izabrane kriterijume.';
                busy(false);
            })
            .catch(error => {
                if (request !== sequence || (error.name === 'AbortError' && !timedOut)) { return; }
                busy(false); feedback.textContent = 'Filter trenutno nije dostupan. Prethodni rezultati su sačuvani; pokušajte ponovo ili osvežite stranicu.';
            }).finally(function () { clearTimeout(timer); });
    }
    filters.forEach(input => input.addEventListener('change', function () { save(); load(1); }));
    if (pagination) { pagination.addEventListener('click', function (event) {
        const link = event.target.closest('a[data-apartment-page]');
        if (!link || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.button !== 0) { return; }
        event.preventDefault(); load(Number(link.dataset.apartmentPage));
    }); }
    if (reset) { reset.addEventListener('click', function () { filters.forEach(input => { input.checked = false; }); try { localStorage.removeItem(storageKey); } catch (error) {} load(1); }); }
    // Explicit URL state (including pagination) takes priority over remembered filters.
    const query = new URLSearchParams(location.search);
    let restored = null, explicit = keys.some(key => query.has(key) || query.has(key + '[]')) || query.has('stanovi_strana');
    if (explicit) {
        restored = {}; keys.forEach(key => { restored[key] = query.getAll(key + '[]').concat(query.getAll(key));
            for (const [name, value] of query.entries()) { if (name.startsWith(key + '[') && name !== key + '[]') { restored[key].push(value); } }
        });
    } else { try { restored = JSON.parse(localStorage.getItem(storageKey)); } catch (error) { restored = null; } }
    if (restored && typeof restored === 'object' && !Array.isArray(restored)) {
        filters.forEach(input => { const key = input.name.replace('[]', ''); input.checked = Array.isArray(restored[key]) && restored[key].map(String).indexOf(input.value) !== -1; });
        if (!explicit && keys.some(key => values(key).length)) { load(1); }
    }
});
