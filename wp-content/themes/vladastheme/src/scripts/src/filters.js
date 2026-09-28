document.addEventListener('DOMContentLoaded', function () {

    const filters = document.querySelectorAll('.apartment-filter');
    const results = document.querySelector('#apartments-results');
    const loader = document.querySelector('#apartments-loader');
    const count = document.querySelector('#apartments-count');
    const summaryText = document.querySelector('#apartments-summary-text');
    const resetButton = document.querySelector('#reset-apartment-filters');

    const storageKey = 'luxor_apartment_filters';


    function getCheckedValues(name) {

        return Array.from(
            document.querySelectorAll(`input[name="${name}"]:checked`)
        ).map(input => input.value);

    }


    function saveFilters() {

        const savedFilters = {
            tip_stana: getCheckedValues('tip_stana[]'),
            sprat: getCheckedValues('sprat[]'),
            projekat: getCheckedValues('projekat[]'),
            status: getCheckedValues('status[]')
        };

        localStorage.setItem(
            storageKey,
            JSON.stringify(savedFilters)
        );

    }


    function restoreFilters() {

        const saved = localStorage.getItem(storageKey);

        if (!saved) {
            return false;
        }

        let savedFilters;

        try {
            savedFilters = JSON.parse(saved);
        } catch (error) {
            console.error('Greška pri čitanju filtera:', error);
            localStorage.removeItem(storageKey);
            return false;
        }


        // Prvo poništi sve checkboxove
        filters.forEach(filter => {
            filter.checked = false;
        });


        // Tip stana
        if (savedFilters.tip_stana) {

            savedFilters.tip_stana.forEach(value => {

                const input = document.querySelector(
                    `input[name="tip_stana[]"][value="${value}"]`
                );

                if (input) {
                    input.checked = true;
                }

            });

        }


        // Sprat
        if (savedFilters.sprat) {

            savedFilters.sprat.forEach(value => {

                const input = document.querySelector(
                    `input[name="sprat[]"][value="${value}"]`
                );

                if (input) {
                    input.checked = true;
                }

            });

        }


        // Projekat
        if (savedFilters.projekat) {

            savedFilters.projekat.forEach(value => {

                const input = document.querySelector(
                    `input[name="projekat[]"][value="${value}"]`
                );

                if (input) {
                    input.checked = true;
                }

            });

        }


        // Status
        if (savedFilters.status) {

            savedFilters.status.forEach(value => {

                const input = document.querySelector(
                    `input[name="status[]"][value="${value}"]`
                );

                if (input) {
                    input.checked = true;
                }

            });

        }


        return true;

    }


    function loadApartments() {

        const tipStana = getCheckedValues('tip_stana[]');
        const sprat = getCheckedValues('sprat[]');
        const projekat = getCheckedValues('projekat[]');
        const status = getCheckedValues('status[]');


        const formData = new FormData();

        formData.append('action', 'filter_stanovi');
        formData.append('nonce', stanoviAjax.nonce);


        tipStana.forEach(value => {
            formData.append('tip_stana[]', value);
        });


        sprat.forEach(value => {
            formData.append('sprat[]', value);
        });


        projekat.forEach(value => {
            formData.append('projekat[]', value);
        });


        status.forEach(value => {
            formData.append('status[]', value);
        });


        // loader.style.display = 'block';
        // results.style.opacity = '0.4';
        loader.style.display = 'flex';
        results.style.display = 'none';


        fetch(stanoviAjax.ajax_url, {
            method: 'POST',
            body: formData
        })

        .then(response => response.json())

        .then(response => {

            // loader.style.display = 'none';
            // results.style.opacity = '1';
            loader.style.display = 'none';
            results.style.display = '';


            if (response.success) {

                results.innerHTML = response.data.html;

                count.innerHTML =
                    `Pronađeno: <strong>${response.data.count}</strong>`;
                    
                summaryText.innerHTML =
                    `Prikazano <strong>${response.data.count}</strong> od <strong>${response.data.count}</strong> stanova`;    

            } else {

                results.innerHTML = `
                    <div class="col-12">
                        <p>Nema pronađenih stanova.</p>
                    </div>
                `;

                count.innerHTML = 'Pronađeno: 0';

            }

        })

        .catch(error => {

            console.error(error);

            loader.style.display = 'none';
            results.style.opacity = '1';

        });

    }


    /*
     * Kada se promeni bilo koji filter
     */
    filters.forEach(filter => {

        filter.addEventListener('change', function () {

            saveFilters();

            loadApartments();

        });

    });


    /*
     * Poništi filtere
     */
    if (resetButton) {

        resetButton.addEventListener('click', function () {

            filters.forEach(filter => {
                filter.checked = false;
            });

            localStorage.removeItem(storageKey);

            loadApartments();

        });

    }


    /*
     * Pri prvom učitavanju stranice
     */
    restoreFilters();

    loadApartments();

});