document.addEventListener('DOMContentLoaded', function () {

    // =====================================================
    // CHECK SWIPER
    // =====================================================

    if (typeof Swiper === 'undefined') {
        console.error(
            'Swiper nije učitan pre single-project.js fajla.'
        );
        return;
    }


    // =====================================================
    // PROJECT TABS
    // =====================================================

    const tabs = document.querySelectorAll('.project-tab');
    const contents = document.querySelectorAll('.project-tab-content');

    tabs.forEach(function (tab) {

        tab.addEventListener('click', function () {

            const target = this.getAttribute('data-project-tab');

            tabs.forEach(function (item) {
                item.classList.remove('active');
            });

            contents.forEach(function (item) {
                item.classList.remove('active');
            });

            this.classList.add('active');

            const targetContent = document.querySelector(
                '[data-project-content="' + target + '"]'
            );

            if (targetContent) {
                targetContent.classList.add('active');
            }

        });

    });


    // =====================================================
    // PROJECT GALLERY
    // =====================================================

    const mainGallery = document.querySelector(
        '.project-gallery-slider'
    );

    let projectSwiper = null;


    if (mainGallery) {

        // =================================================
        // DESTROY OLD INSTANCE IF EXISTS
        // =================================================

        if (
            mainGallery.swiper &&
            typeof mainGallery.swiper.destroy === 'function'
        ) {
            mainGallery.swiper.destroy(true, true);
        }


        // =================================================
        // GALLERY THUMBNAILS
        // =================================================

        const thumbnails = Array.from(
            document.querySelectorAll(
                '.project-gallery-thumbnail'
            )
        );


        // =================================================
        // MAIN GALLERY SWIPER
        // =================================================

        projectSwiper = new Swiper(
            mainGallery,
            {
                slidesPerView: 1,

                slidesPerGroup: 1,

                spaceBetween: 0,

                speed: 500,

                loop: false,

                rewind: false,

                autoplay: false,

                watchOverflow: true,

                observer: true,

                observeParents: true,

                resizeObserver: true,

                navigation: {
                    nextEl: '.project-gallery-arrow-next',
                    prevEl: '.project-gallery-arrow-prev'
                },

                keyboard: {
                    enabled: true,
                    onlyInViewport: true
                },

                on: {

                    init: function () {
                        updateGallery(this.realIndex);
                    },

                    slideChange: function () {
                        updateGallery(this.realIndex);
                    }

                }
            }
        );


        // =================================================
        // UPDATE GALLERY
        // =================================================

        function updateGallery(index) {

            updateCounter(index);

            updateThumbnails(index);

        }


        // =================================================
        // COUNTER
        // =================================================

        function updateCounter(index) {

            const current = document.querySelector(
                '.project-gallery-current'
            );

            if (current) {
                current.textContent = index + 1;
            }

        }


        // =================================================
        // ACTIVE THUMBNAIL
        // =================================================

        function updateThumbnails(index) {

            thumbnails.forEach(function (thumbnail) {
                thumbnail.classList.remove('active');
            });

            const activeThumbnail = thumbnails[index];

            if (!activeThumbnail) {
                return;
            }

            activeThumbnail.classList.add('active');


            // Pomeri thumbnail strip samo horizontalno
            // ako aktivna fotografija nije vidljiva.

            const thumbnailsContainer =
                activeThumbnail.parentElement;

            if (thumbnailsContainer) {

                const containerRect =
                    thumbnailsContainer.getBoundingClientRect();

                const thumbnailRect =
                    activeThumbnail.getBoundingClientRect();


                if (
                    thumbnailRect.left < containerRect.left ||
                    thumbnailRect.right > containerRect.right
                ) {

                    activeThumbnail.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'nearest'
                    });

                }

            }

        }


        // =================================================
        // THUMBNAIL CLICK
        // =================================================

        thumbnails.forEach(function (thumbnail) {

            thumbnail.addEventListener(
                'click',
                function () {

                    const index = parseInt(
                        this.getAttribute('data-slide'),
                        10
                    );

                    if (isNaN(index)) {
                        return;
                    }

                    projectSwiper.slideTo(index);

                }
            );

        });


        // =================================================
        // FULLSCREEN
        // =================================================

        const fullscreenButton = document.querySelector(
            '.project-gallery-fullscreen'
        );


        if (fullscreenButton) {

            fullscreenButton.addEventListener(
                'click',
                function () {

                    const activeImage =
                        mainGallery.querySelector(
                            '.swiper-slide-active .project-gallery-image'
                        );

                    if (!activeImage) {
                        return;
                    }


                    if (activeImage.requestFullscreen) {

                        activeImage.requestFullscreen();

                    } else if (
                        activeImage.webkitRequestFullscreen
                    ) {

                        activeImage.webkitRequestFullscreen();

                    }

                }
            );

        }

    }


    // =====================================================
    // APARTMENTS SWIPER
    // =====================================================

    const apartmentsSlider = document.querySelector(
        '.project-apartments-slider'
    );

    let apartmentsSwiper = null;


    if (apartmentsSlider) {

        // =================================================
        // DESTROY OLD SWIPER INSTANCE
        // =================================================

        if (
            apartmentsSlider.swiper &&
            typeof apartmentsSlider.swiper.destroy === 'function'
        ) {

            apartmentsSlider.swiper.destroy(true, true);

        }


        // =================================================
        // REMOVE OLD LOOP DUPLICATES
        // =================================================

        const oldDuplicates =
            apartmentsSlider.querySelectorAll(
                '.swiper-slide-duplicate'
            );

        oldDuplicates.forEach(function (slide) {
            slide.remove();
        });


        // =================================================
        // GET REAL APARTMENTS
        // =================================================

        const apartmentsWrapper =
            apartmentsSlider.querySelector(
                '.swiper-wrapper'
            );

        let apartmentSlides = [];


        if (apartmentsWrapper) {

            apartmentSlides = Array.from(
                apartmentsWrapper.children
            ).filter(function (slide) {

                return slide.classList.contains(
                    'swiper-slide'
                );

            });

        }


        const apartmentsCount =
            apartmentSlides.length;


        // =================================================
        // NAVIGATION
        // =================================================

        const apartmentsPrev = document.querySelector(
            '.project-apartments-prev'
        );

        const apartmentsNext = document.querySelector(
            '.project-apartments-next'
        );


        // =================================================
        // INIT ONLY IF WE HAVE APARTMENTS
        // =================================================

        if (
            apartmentsCount > 0 &&
            apartmentsWrapper
        ) {

            apartmentsSwiper = new Swiper(
                apartmentsSlider,
                {

                    // =====================================
                    // BASIC
                    // =====================================

                    speed: 500,

                    spaceBetween: 24,

                    slidesPerGroup: 1,


                    // =====================================
                    // IMPORTANT
                    // =====================================

                    loop: false,

                    rewind: false,

                    autoplay: true,

                    centeredSlides: false,

                    centeredSlidesBounds: false,

                    centerInsufficientSlides: false,

                    watchOverflow: true,

                    normalizeSlideIndex: true,

                    roundLengths: true,


                    // =====================================
                    // INTERACTION
                    // =====================================

                    allowTouchMove: true,

                    simulateTouch: true,

                    grabCursor: apartmentsCount > 4,


                    // =====================================
                    // OBSERVERS
                    // =====================================

                    observer: true,

                    observeParents: true,

                    observeSlideChildren: false,

                    resizeObserver: true,


                    // =====================================
                    // NAVIGATION
                    // =====================================

                    navigation: {

                        nextEl: apartmentsNext,

                        prevEl: apartmentsPrev

                    },


                    // =====================================
                    // RESPONSIVE
                    // =====================================

                    breakpoints: {

                        0: {
                            slidesPerView: 1.15,
                            slidesPerGroup: 1,
                            spaceBetween: 16
                        },

                        576: {
                            slidesPerView: 2,
                            slidesPerGroup: 1,
                            spaceBetween: 18
                        },

                        768: {
                            slidesPerView: 2,
                            slidesPerGroup: 1,
                            spaceBetween: 20
                        },

                        992: {
                            slidesPerView: 3,
                            slidesPerGroup: 1,
                            spaceBetween: 22
                        },

                        1200: {
                            slidesPerView: 4,
                            slidesPerGroup: 1,
                            spaceBetween: 24,
                        }

                    },


                    // =====================================
                    // EVENTS
                    // =====================================

                    on: {

                        init: function () {

                            updateApartmentNavigation(this);

                        },

                        slideChange: function () {

                            updateApartmentNavigation(this);

                        },

                        reachBeginning: function () {

                            updateApartmentNavigation(this);

                        },

                        reachEnd: function () {

                            updateApartmentNavigation(this);

                        },

                        fromEdge: function () {

                            updateApartmentNavigation(this);

                        },

                        resize: function () {

                            updateApartmentNavigation(this);

                        },

                        breakpoint: function () {

                            updateApartmentNavigation(this);

                        },

                        lock: function () {

                            updateApartmentNavigation(this);

                        },

                        unlock: function () {

                            updateApartmentNavigation(this);

                        }

                    }

                }
            );

        } else {

            // Ako nema stanova, sakrij strelice.

            if (apartmentsPrev) {
                apartmentsPrev.style.display = 'none';
            }

            if (apartmentsNext) {
                apartmentsNext.style.display = 'none';
            }

        }


        // =================================================
        // UPDATE APARTMENT NAVIGATION
        // =================================================

        function updateApartmentNavigation(swiper) {

            if (
                !apartmentsPrev ||
                !apartmentsNext
            ) {
                return;
            }


            // =============================================
            // ALL APARTMENTS FIT ON SCREEN
            // =============================================

            if (swiper.isLocked) {

                apartmentsPrev.style.display = 'none';

                apartmentsNext.style.display = 'none';

                return;

            }


            // =============================================
            // SHOW NAVIGATION
            // =============================================

            apartmentsPrev.style.display = '';

            apartmentsNext.style.display = '';


            // =============================================
            // PREVIOUS
            // =============================================

            if (swiper.isBeginning) {

                apartmentsPrev.classList.add(
                    'swiper-button-disabled'
                );

                apartmentsPrev.setAttribute(
                    'aria-disabled',
                    'true'
                );

            } else {

                apartmentsPrev.classList.remove(
                    'swiper-button-disabled'
                );

                apartmentsPrev.setAttribute(
                    'aria-disabled',
                    'false'
                );

            }


            // =============================================
            // NEXT
            // =============================================

            if (swiper.isEnd) {

                apartmentsNext.classList.add(
                    'swiper-button-disabled'
                );

                apartmentsNext.setAttribute(
                    'aria-disabled',
                    'true'
                );

            } else {

                apartmentsNext.classList.remove(
                    'swiper-button-disabled'
                );

                apartmentsNext.setAttribute(
                    'aria-disabled',
                    'false'
                );

            }

        }

    }


    // =====================================================
    // WINDOW LOAD
    // =====================================================

    window.addEventListener(
        'load',
        function () {

            if (
                projectSwiper &&
                !projectSwiper.destroyed
            ) {
                projectSwiper.update();
            }


            if (
                apartmentsSwiper &&
                !apartmentsSwiper.destroyed
            ) {

                apartmentsSwiper.update();

                apartmentsSwiper.slideTo(
                    apartmentsSwiper.activeIndex,
                    0,
                    false
                );

            }

        }
    );


    // =====================================================
    // WINDOW RESIZE
    // =====================================================

    let resizeTimer = null;


    window.addEventListener(
        'resize',
        function () {

            clearTimeout(resizeTimer);


            resizeTimer = setTimeout(
                function () {

                    if (
                        projectSwiper &&
                        !projectSwiper.destroyed
                    ) {
                        projectSwiper.update();
                    }


                    if (
                        apartmentsSwiper &&
                        !apartmentsSwiper.destroyed
                    ) {

                        apartmentsSwiper.update();

                    }

                },
                150
            );

        }
    );

});