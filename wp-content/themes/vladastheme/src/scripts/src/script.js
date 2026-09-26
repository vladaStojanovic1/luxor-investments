/* eslint-disable no-console */

(function ($) {
    'use strict';
    /**
     * Global Variables
     */
    const isMobile = {
        Android: function () {
            return navigator.userAgent.match(/Android/i);
        },
        BlackBerry: function () {
            return navigator.userAgent.match(/BlackBerry/i);
        },
        iOS: function () {
            return navigator.userAgent.match(/iPhone|iPad|iPod/i);
        },
        Opera: function () {
            return navigator.userAgent.match(/Opera Mini/i);
        },
        Windows: function () {
            return navigator.userAgent.match(/IEMobile/i);
        },
        any: function () {
            return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
        }
    };
    /**
     * Returns a function, that, as long as it continues to be invoked, will not
     * be triggered. The function will be called after it stops being called for
     * N milliseconds. If `immediate` is passed, trigger the function on the
     * leading edge, instead of the trailing.
     * @param {fn} func - function to debounce
     * @param {number} wait - time to wait
     * @param {bool} immediate
     * @returns {Function}
     */
    const debounce = function (func, wait, immediate) {
        let timeout;
        let waitTime = wait || 100;
        return function () {
            let context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                timeout = null;
                if (!immediate) {
                    func.apply(context, args);
                }
            }, waitTime);
            if (immediate && !timeout) {
                func.apply(context, args);
            }
        };
    };
    /**
     * Collection of useful site functions
     * @type {{init: init, smoothScroll: smoothScroll}}
     */
    const siteFunctions  = {
        init: function () {
            siteFunctions.smoothScroll();
        },
        /**
         * Smooth Scroll function for anchor clicks
         */
        smoothScroll: function () {
            $('a[href*="#"]').click(function () {
                let target = $(this.hash);
                if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    if (target.length) {
                        $('html, body').stop().animate({
                            scrollTop: target.offset().top - 75
                        }, 1000);
                        return false;
                    }
                }
            });
        }
    };

    const siteHeader = {
        init: function () {

        },
        /**
         * Handle on scroll header functionality
         */
        scrollChange: function () {
            let $body = $('body');
            $(document).scrollTop() > 50 ? $body.addClass('scroll') : $body.removeClass('scroll'); //jshint ignore:line
        }
    };

    const siteMenu = {
        prevent: false,
        init: function () {
            /**
             * Toggle menu with clicking on hamburger menu and overlay
             */
            $('#menu-button, .m-overlay').click(function (e) {
                e.preventDefault();
                siteMenu.toggle();
            });
        },
        toggle: function () {
            if (!siteMenu.prevent) {
                $('body').toggleClass('m-open');
                siteMenu.prevent = !siteMenu.prevent;

                setTimeout(function () {
                    siteMenu.prevent = !siteMenu.prevent;
                }, 400);
            }
        }
    };

    $(document).ready(function () {
        siteFunctions.init();
        siteMenu.init();

        $(window).scroll(debounce(function () {
            siteHeader.scrollChange();
        }));


        const swiper = new Swiper('.hero-swiper', {
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            effect: 'slide',
        });

         // GSAP Counter animation
        if (document.body.classList.contains('home') || document.body.classList.contains('page-template-template-home') || document.body.classList.contains('page-template-template-o-investitoru')) {
            gsap.registerPlugin(ScrollTrigger);

            let mm = gsap.matchMedia();

            // gsap.from(".counterOne", {
            //     textContent: "0",
            //     duration: 4,
            //     modifiers: {
            //         textContent: value => formatNumber(value, 0) + "+"
            //     },
            //     // snap: { textContent: 1 },
            //     scrollTrigger: {
            //         trigger: ".m-aboutUs",
            //         start: "top bottom",
            //     }
            // });

            gsap.utils.toArray(".counter-section").forEach(section => {
                const counters = section.querySelectorAll(".counterOne");

                counters.forEach(counter => {
                    gsap.from(counter, {
                            textContent: "0",
                            duration: 4,
                            modifiers: {
                                textContent: value => formatNumber(value, 0) + "+"
                            },
                            scrollTrigger: {
                                trigger: section,
                                start: "top bottom",
                            }
                        }
                    );
                });
            });


            function formatNumber(value, decimals) {
                let s = (value).toLocaleString('en-US').split(".");
                return decimals ? s[0] + "." + ((s[1] || "") + "00000000").substr(0, decimals) : s[0];
            }
        }

        gsap.registerPlugin(ScrollTrigger);
        document.querySelectorAll('.bar-fill').forEach(bar => {
            const percent = bar.getAttribute('data-percent');

            if (!bar.querySelector('.bar-text')) {
                const span = document.createElement('span');
                span.classList.add('bar-text');
                span.textContent = percent;
                bar.appendChild(span);
            }

            gsap.to(bar, {
                scrollTrigger: {
                    trigger: bar,
                    start: 'top bottom',
                    toggleActions: 'play none none none'
                },
                width: percent,
                duration: 3,
                ease: 'power2.out'
            });
        });


        AOS.init();


    });

    $(window).on('load', function () {

    });
}(jQuery));
