document.addEventListener('DOMContentLoaded', function () {

    const statusFilter = document.getElementById('project-status-filter');
    const projectCards = document.querySelectorAll('[data-project-card]');
    const projectCount = document.getElementById('projects-count');
    const emptyMessage = document.getElementById('projects-filter-empty');

    /*
     * Ako nismo na archive stranici projekata,
     * prekidamo izvršavanje.
     */
    if (!statusFilter || !projectCards.length) {
        return;
    }


    /*
     * Filtriranje projekata.
     */
    function filterProjects() {

        const selectedStatus = statusFilter.value;

        let visibleProjects = 0;

        projectCards.forEach(function (card) {

            const projectStatus = card.dataset.status;

            const shouldShow =
                selectedStatus === 'all' ||
                projectStatus === selectedStatus;


            if (shouldShow) {

                card.classList.remove('is-filter-hidden');

                visibleProjects++;

            } else {

                card.classList.add('is-filter-hidden');

            }

        });


        /*
         * Menjamo broj pronađenih projekata.
         */
        if (projectCount) {
            projectCount.textContent = visibleProjects;
        }


        /*
         * Ako nema rezultata prikazujemo poruku.
         */
        if (emptyMessage) {

            if (visibleProjects === 0) {

                emptyMessage.hidden = false;

            } else {

                emptyMessage.hidden = true;

            }

        }

    }


    /*
     * Filter reaguje ODMAH na promenu dropdowna.
     */
    statusFilter.addEventListener('change', filterProjects);

});