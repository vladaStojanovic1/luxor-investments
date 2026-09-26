document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | TABOVI
    |--------------------------------------------------------------------------
    */

    const tabButtons = document.querySelectorAll('.apartment-tab');
    const tabPanels = document.querySelectorAll('.apartment-tab-panel');

    function openApartmentTab(tabName) {

        tabButtons.forEach(function (button) {

            button.classList.toggle(
                'active',
                button.dataset.tab === tabName
            );

        });


        tabPanels.forEach(function (panel) {

            panel.classList.toggle(
                'active',
                panel.dataset.panel === tabName
            );

        });

    }


    tabButtons.forEach(function (button) {

        button.addEventListener('click', function () {

            openApartmentTab(
                this.dataset.tab
            );

        });

    });


    /*
    |--------------------------------------------------------------------------
    | GALERIJA
    |--------------------------------------------------------------------------
    */

    const gallery = document.querySelector('.apartment-gallery');

    if (gallery && typeof Swiper !== 'undefined') {

        const mainElement = gallery.querySelector(
            '.apartment-main-swiper'
        );

        const prevButton = gallery.querySelector(
            '.apartment-swiper-prev'
        );

        const nextButton = gallery.querySelector(
            '.apartment-swiper-next'
        );

        const thumbnails = gallery.querySelectorAll(
            '.apartment-gallery-thumb-item'
        );

        let mainSwiper = null;


        if (mainElement) {

            mainSwiper = new Swiper(mainElement, {

                slidesPerView: 1,
                spaceBetween: 0,
                speed: 450,

                navigation: {
                    prevEl: prevButton,
                    nextEl: nextButton
                }

            });

        }


        function setActiveThumbnail(index) {

            thumbnails.forEach(function (thumbnail) {
                thumbnail.classList.remove('active');
            });


            if (thumbnails[index]) {

                thumbnails[index].classList.add('active');

            }

        }


        thumbnails.forEach(function (thumbnail) {

            thumbnail.addEventListener('click', function () {

                if (!mainSwiper) {
                    return;
                }

                const index = parseInt(
                    this.dataset.slide,
                    10
                );

                mainSwiper.slideTo(index);

            });

        });


        if (mainSwiper) {

            mainSwiper.on('slideChange', function () {

                setActiveThumbnail(
                    mainSwiper.activeIndex
                );

            });

        }


        setActiveThumbnail(0);

    }


    /*
    |--------------------------------------------------------------------------
    | FANCYBOX
    |--------------------------------------------------------------------------
    */

    if (typeof Fancybox !== 'undefined') {

        Fancybox.bind(
            '[data-fancybox="apartment-gallery"]',
            {}
        );

        Fancybox.bind(
            '[data-fancybox="floor-plan"]',
            {}
        );

    }


    /*
    |--------------------------------------------------------------------------
    | GORNJE DUGME - POŠALJI UPIT
    |--------------------------------------------------------------------------
    */

    const contactButton = document.querySelector(
        '.js-open-contact'
    );

    const contentSection = document.querySelector(
        '#apartment-content'
    );


    if (contactButton && contentSection) {

        contactButton.addEventListener('click', function (event) {

            event.preventDefault();

            contentSection.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });

        });

    }

});