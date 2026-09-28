<?php
/**
 * Featured apartments - Home page
 */

$apartments = new WP_Query([
    'post_type'      => 'stanovi',
    'post_status'    => 'publish',
    'posts_per_page' => 12,

    'meta_query' => [
        [
            'key'     => 'izdvojeni_stan',
            'value'   => '1',
            'compare' => '=',
        ],
    ],

    'orderby' => 'date',
    'order'   => 'DESC',
]);
?>

<?php if ($apartments->have_posts()) : ?>

<section class="m-featuredApartments sections-spacing">

    <div class="container">

        <!-- =========================
             SECTION HEADER
        ========================== -->
        <div class="m-featuredApartments__header">

            <div>
                <p class="m-featuredApartments__eyebrow">
                    Luxor Investments
                </p>

                <h2 class="m-featuredApartments__title">
                    Stanovi po vašoj meri
                </h2>

                <p class="m-featuredApartments__description">
                    Pažljivo odabrani stanovi iz naše aktuelne ponude.
                    Pronađite prostor koji odgovara vašim potrebama i načinu života.
                </p>
            </div>


            <!-- Swiper navigation -->
            <div class="m-featuredApartments__navigation">

                <button
                    type="button"
                    class="m-featuredApartments__prev"
                    aria-label="Prethodni stan"
                >
                    <i class="bi bi-arrow-left"></i>
                </button>

                <button
                    type="button"
                    class="m-featuredApartments__next"
                    aria-label="Sledeći stan"
                >
                    <i class="bi bi-arrow-right"></i>
                </button>

            </div>

        </div>


        <!-- =========================
             SWIPER
        ========================== -->
        <div class="swiper featuredApartmentsSwiper">

            <div class="swiper-wrapper">

                <?php while ($apartments->have_posts()) : $apartments->the_post(); ?>

                    <?php
                    /*
                     * ACF fields
                     */
                    $lokacija   = get_field('naslov_lokacije');
                    $sprat      = get_field('sprat');
                    $kvadratura = get_field('kvadratura');
                    $broj_soba  = get_field('broj_soba');
                    $status     = get_field('status');


                    /*
                     * Status can sometimes be returned
                     * as array depending on ACF field settings.
                     */
                    if (is_array($status)) {

                        if (isset($status['label'])) {
                            $status_label = $status['label'];
                        } elseif (isset($status['value'])) {
                            $status_label = $status['value'];
                        } else {
                            $status_label = '';
                        }

                    } else {
                        $status_label = $status;
                    }


                    /*
                     * CSS class for status
                     */
                    $status_class = sanitize_title($status_label);
                    ?>


                    <div class="swiper-slide">

                        <article class="featured-apartment">


                            <!-- =========================
                                 IMAGE
                            ========================== -->
                            <a
                                href="<?php the_permalink(); ?>"
                                class="featured-apartment__image"
                            >

                                <?php if (has_post_thumbnail()) : ?>

                                    <?php
                                    the_post_thumbnail(
                                        'large',
                                        [
                                            'class'   => 'featured-apartment__img',
                                            'loading' => 'lazy',
                                            'alt'     => esc_attr(get_the_title()),
                                        ]
                                    );
                                    ?>

                                <?php else : ?>

                                    <div class="featured-apartment__no-image">
                                        <span>Luxor Investments</span>
                                    </div>

                                <?php endif; ?>


                                <!-- STATUS -->
                                <?php if ($status_label) : ?>

                                    <span class="
                                        featured-apartment__status
                                        featured-apartment__status--<?php echo esc_attr($status_class); ?>
                                    ">
                                        <?php echo esc_html($status_label); ?>
                                    </span>

                                <?php endif; ?>

                            </a>


                            <!-- =========================
                                 CONTENT
                            ========================== -->
                            <div class="featured-apartment__content">


                                <!-- LOCATION -->
                                <?php if ($lokacija) : ?>

                                    <div class="featured-apartment__location">

                                        <i class="bi bi-geo-alt"></i>

                                        <span>
                                            <?php echo esc_html($lokacija); ?>
                                        </span>

                                    </div>

                                <?php endif; ?>


                                <!-- TITLE -->
                                <div class="featured-apartment__heading">

                                    <h3 class="featured-apartment__title">

                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>

                                    </h3>


                                    <a
                                        href="<?php the_permalink(); ?>"
                                        class="featured-apartment__arrow"
                                        aria-label="<?php echo esc_attr(
                                            'Pogledaj stan ' . get_the_title()
                                        ); ?>"
                                    >
                                        <i class="bi bi-arrow-right"></i>
                                    </a>

                                </div>


                                <!-- =========================
                                     APARTMENT DETAILS
                                ========================== -->
                                <div class="featured-apartment__details">


                                    <!-- KVADRATURA -->
                                    <?php if ($kvadratura) : ?>

                                        <div class="featured-apartment__detail">

                                            <i class="bi bi-aspect-ratio"></i>

                                            <div>

                                                <span>
                                                    Kvadratura
                                                </span>

                                                <strong>
                                                    <?php echo esc_html($kvadratura); ?> m²
                                                </strong>

                                            </div>

                                        </div>

                                    <?php endif; ?>


                                    <!-- BROJ SOBA -->
                                    <?php if ($broj_soba) : ?>

                                        <div class="featured-apartment__detail">

                                            <i class="bi bi-door-open"></i>

                                            <div>

                                                <span>
                                                    Broj soba
                                                </span>

                                                <strong>
                                                    <?php echo esc_html($broj_soba); ?>
                                                </strong>

                                            </div>

                                        </div>

                                    <?php endif; ?>


                                    <!-- SPRAT -->
                                    <?php if ($sprat !== '' && $sprat !== null) : ?>

                                        <div class="featured-apartment__detail">

                                            <i class="bi bi-building"></i>

                                            <div>

                                                <span>
                                                    Sprat
                                                </span>

                                                <strong>
                                                    <?php echo esc_html($sprat); ?>
                                                </strong>

                                            </div>

                                        </div>

                                    <?php endif; ?>


                                </div>

                            </div>

                        </article>

                    </div>

                <?php endwhile; ?>

            </div>

        </div>


        <!-- =========================
             BOTTOM LINK
        ========================== -->
        <div class="m-featuredApartments__bottom">

            <a
                href="<?php echo esc_url(home_url('/prodaja-stanova/')); ?>"
                class="m-featuredApartments__all"
            >
                Pogledajte sve stanove

                <i class="bi bi-arrow-right"></i>
            </a>

        </div>

    </div>

</section>

<?php endif; ?>

<?php wp_reset_postdata(); ?>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const slider = document.querySelector('.featuredApartmentsSwiper');

    if (!slider) {
        return;
    }

    new Swiper('.featuredApartmentsSwiper', {

        slidesPerView: 1,
        spaceBetween: 20,

        speed: 600,

        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        },

        navigation: {
            nextEl: '.m-featuredApartments__next',
            prevEl: '.m-featuredApartments__prev'
        },

        breakpoints: {

            // >= 576px
            576: {
                slidesPerView: 2,
                spaceBetween: 20
            },

            // >= 992px
            992: {
                slidesPerView: 3,
                spaceBetween: 20
            },

            // >= 1200px
            1200: {
                slidesPerView: 4,
                spaceBetween: 20
            }

        }

    });

});
</script>