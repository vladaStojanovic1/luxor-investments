<?php

$current_post = get_queried_object();
include(get_template_directory() . '/inc/_partials/_navigation.php');

get_header();

if ($current_post instanceof WP_Post) :

    setup_postdata($current_post);

    $post_id = $current_post->ID;

    /*
    |--------------------------------------------------------------------------
    | ACF POLJA
    |--------------------------------------------------------------------------
    */

    $project    = get_field('projekat', $post_id);
    $floor      = get_field('sprat', $post_id);
    $area       = get_field('kvadratura', $post_id);
    $status     = get_field('status', $post_id);
    $gallery    = get_field('galerija', $post_id);
    $floor_plan = get_field('osnova_stana', $post_id);

    $orijentacija = get_field('orijentacija', $post_id);
    $terasa       = get_field('terasa', $post_id);
    $parking      = get_field('parking', $post_id);
    $grejanje     = get_field('grejanje', $post_id);

    /*
     * Lokacija
     */
    $location_map         = get_field('lokacija_stana_mapa', $post_id);
    $location_description = get_field('opis_lokacije', $post_id);
    $location_title       = get_field('naslov_lokacije', $post_id);

    if (!$location_title) {
        $location_title = 'Idealna lokacija';
    }


    /*
    |--------------------------------------------------------------------------
    | PROJEKAT
    |--------------------------------------------------------------------------
    */

    $project_id = 0;

    if ($project instanceof WP_Post) {
        $project_id = $project->ID;
    } elseif ($project) {
        $project_id = (int) $project;
    }


    /*
    |--------------------------------------------------------------------------
    | TIP STANA
    |--------------------------------------------------------------------------
    */

    $types = get_the_terms($post_id, 'tip_stana');

    $type_name = '';
    $type_slug = '';
    $broj_soba = '';

    if ($types && !is_wp_error($types)) {

        $type_name = $types[0]->name;
        $type_slug = $types[0]->slug;

        switch ($type_slug) {

            case 'jednosoban':
                $broj_soba = 1;
                break;

            case 'dvosoban':
                $broj_soba = 2;
                break;

            case 'trosoban':
                $broj_soba = 3;
                break;

            case 'cetvorosoban':
                $broj_soba = 4;
                break;

            case 'petosoban':
                $broj_soba = 5;
                break;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    $status_label = $status ? ucfirst($status) : '';
    $status_class = '';

    switch ($status) {

        case 'slobodan':
            $status_class = 'status-free';
            break;

        case 'rezervisan':
            $status_class = 'status-reserved';
            break;

        case 'prodat':
            $status_class = 'status-sold';
            break;
    }


    /*
    |--------------------------------------------------------------------------
    | GALERIJA
    |--------------------------------------------------------------------------
    */

    $gallery_ids = [];

    if (has_post_thumbnail($post_id)) {
        $gallery_ids[] = get_post_thumbnail_id($post_id);
    }

    if (is_array($gallery)) {

        foreach ($gallery as $image) {

            if (is_array($image) && !empty($image['ID'])) {
                $gallery_ids[] = (int) $image['ID'];
            }

            elseif ($image instanceof WP_Post) {
                $gallery_ids[] = (int) $image->ID;
            }

            elseif (is_numeric($image)) {
                $gallery_ids[] = (int) $image;
            }
        }
    }

    $gallery_ids = array_values(
        array_unique(
            array_filter($gallery_ids)
        )
    );

    $gallery_count = count($gallery_ids);


    /*
    |--------------------------------------------------------------------------
    | OSNOVA STANA
    |--------------------------------------------------------------------------
    */

    $floor_plan_id = 0;

    if (is_array($floor_plan) && !empty($floor_plan['ID'])) {
        $floor_plan_id = (int) $floor_plan['ID'];
    }

    elseif ($floor_plan instanceof WP_Post) {
        $floor_plan_id = $floor_plan->ID;
    }

    elseif (is_numeric($floor_plan)) {
        $floor_plan_id = (int) $floor_plan;
    }

?>

<section class="single-apartment">

    <div class="container py-5">


        <!-- BREADCRUMB -->
        <div class="apartment-breadcrumb mb-5">

            <a href="<?php echo esc_url(home_url('/')); ?>">
                Početna
            </a>

            <span>/</span>

            <a href="<?php echo esc_url(home_url('/prodaja-stanova/')); ?>">
                Prodaja stanova
            </a>

            <span>/</span>

            <span>
                <?php echo esc_html(get_the_title($post_id)); ?>
            </span>

        </div>


        <!-- TOP SECTION -->
        <div class="row g-5 align-items-start">

            <!-- LEVO -->
            <div class="col-lg-4">

                <div class="apartment-intro">

                    <?php if ($type_name) : ?>

                        <div class="apartment-type d-lg-none">
                            <?php echo esc_html($type_name); ?>
                        </div>

                    <?php endif; ?>


                    <h1 class="apartment-title d-lg-none">
                        <?php echo esc_html(get_the_title($post_id)); ?>
                    </h1>


                    <div class="row g-4 apartment-main-info">

                        <?php if ($project_id) : ?>

                            <div class="col-6">
                                <div class="apartment-info-item">

                                    <i class="bi bi-buildings"></i>

                                    <div>
                                        <span>Projekat</span>
                                        <strong>
                                            <?php echo esc_html(get_the_title($project_id)); ?>
                                        </strong>
                                    </div>

                                </div>
                            </div>

                        <?php endif; ?>


                        <?php if ($floor !== '' && $floor !== null) : ?>

                            <div class="col-6">
                                <div class="apartment-info-item">

                                    <i class="bi bi-bar-chart-steps"></i>

                                    <div>
                                        <span>Sprat</span>
                                        <strong><?php echo esc_html($floor); ?></strong>
                                    </div>

                                </div>
                            </div>

                        <?php endif; ?>


                        <?php if ($area) : ?>

                            <div class="col-6">
                                <div class="apartment-info-item">

                                    <i class="bi bi-arrows-fullscreen"></i>

                                    <div>
                                        <span>Kvadratura</span>
                                        <strong><?php echo esc_html($area); ?> m²</strong>
                                    </div>

                                </div>
                            </div>

                        <?php endif; ?>


                        <?php if ($status) : ?>

                            <div class="col-6">
                                <div class="apartment-info-item">

                                    <i class="bi bi-check-circle"></i>

                                    <div>
                                        <span>Status</span>

                                        <strong class="<?php echo esc_attr($status_class); ?>">
                                            <?php echo esc_html($status_label); ?>
                                        </strong>
                                    </div>

                                </div>
                            </div>

                        <?php endif; ?>

                    </div>


                    <div class="d-flex flex-column flex-sm-row gap-3 mt-5">

                        <a
                            href="#apartment-content"
                            class="btn btn-dark apartment-main-btn js-open-contact btn-dark-blue"
                        >
                            Pošalji upit
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>

                        <a
                            href="tel:+381601234567"
                            class="btn btn-outline-dark apartment-main-btn btn-border-primary"
                        >
                            <i class="bi bi-telephone me-2"></i>
                            Pozovi
                        </a>

                    </div>

                </div>

            </div>


            <!-- DESNO - GALERIJA -->
            <div class="col-lg-8">

                <?php if ($gallery_count > 0) : ?>

                    <div class="apartment-gallery">

                        <!-- GLAVNI SWIPER -->
                        <div class="swiper apartment-main-swiper">

                            <div class="swiper-wrapper">

                                <?php foreach ($gallery_ids as $image_id) : ?>

                                    <?php
                                    $full_image = wp_get_attachment_image_url(
                                        $image_id,
                                        'full'
                                    );
                                    ?>

                                    <div class="swiper-slide">

                                        <a
                                            href="<?php echo esc_url($full_image); ?>"
                                            data-fancybox="apartment-gallery"
                                            class="apartment-gallery-main-link"
                                        >

                                            <?php
                                            echo wp_get_attachment_image(
                                                $image_id,
                                                'large',
                                                false,
                                                [
                                                    'class' => 'apartment-gallery-main-image'
                                                ]
                                            );
                                            ?>

                                            <span class="gallery-zoom">
                                                <i class="bi bi-arrows-fullscreen"></i>
                                            </span>

                                        </a>

                                    </div>

                                <?php endforeach; ?>

                            </div>


                            <?php if ($gallery_count > 1) : ?>

                                <button
                                    type="button"
                                    class="apartment-swiper-prev"
                                    aria-label="Prethodna fotografija"
                                >
                                    <i class="bi bi-chevron-left"></i>
                                </button>

                                <button
                                    type="button"
                                    class="apartment-swiper-next"
                                    aria-label="Sledeća fotografija"
                                >
                                    <i class="bi bi-chevron-right"></i>
                                </button>

                            <?php endif; ?>

                        </div>


                        <!-- BROJ FOTOGRAFIJA -->
                        <div class="gallery-count">

                            <i class="bi bi-images"></i>

                            <span>
                                <?php echo esc_html($gallery_count); ?>
                                fotografija
                            </span>

                        </div>


                        <!-- THUMBNAILS -->
                        <?php if ($gallery_count > 1) : ?>

                            <div class="apartment-gallery-thumbs">

                                <?php foreach ($gallery_ids as $index => $image_id) : ?>

                                    <button
                                        type="button"
                                        class="apartment-gallery-thumb-item <?php echo $index === 0 ? 'active' : ''; ?>"
                                        data-slide="<?php echo esc_attr($index); ?>"
                                    >

                                        <?php
                                        echo wp_get_attachment_image(
                                            $image_id,
                                            'medium',
                                            false,
                                            [
                                                'class' => 'apartment-gallery-thumb'
                                            ]
                                        );
                                        ?>

                                    </button>

                                <?php endforeach; ?>

                            </div>

                        <?php endif; ?>

                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- TABOVI + CONTENT -->
        <section
            id="apartment-content"
            class="apartment-content-section"
        >

            <div class="apartment-tabs">

                <button
                    type="button"
                    class="apartment-tab active"
                    data-tab="osnovne"
                >
                    Osnovne informacije
                </button>

                <button
                    type="button"
                    class="apartment-tab"
                    data-tab="opis"
                >
                    Opis stana
                </button>


                <?php if ($floor_plan_id) : ?>

                    <button
                        type="button"
                        class="apartment-tab"
                        data-tab="osnova"
                    >
                        Osnova
                    </button>

                <?php endif; ?>


                <?php if ($location_map || $location_description) : ?>

                    <button
                        type="button"
                        class="apartment-tab"
                        data-tab="lokacija"
                    >
                        Lokacija
                    </button>

                <?php endif; ?>

            </div>


            <div class="row g-4 g-xl-5 mt-2">


                <!-- LEVO - MENJA SE -->
                <div class="col-lg-8">


                    <!-- OSNOVNE INFORMACIJE -->
                    <div
                        class="apartment-tab-panel active"
                        data-panel="osnovne"
                    >

                        <h2 class="section-title">
                            Osnovne informacije
                        </h2>


                        <div class="apartment-details-card">

                            <div class="row g-4">

                                <?php if ($type_name) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-house-door"></i>

                                            <div>
                                                <span>Tip stana</span>
                                                <strong><?php echo esc_html($type_name); ?></strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <?php if ($area) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-arrows-fullscreen"></i>

                                            <div>
                                                <span>Površina</span>
                                                <strong><?php echo esc_html($area); ?> m²</strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <?php if ($floor !== '' && $floor !== null) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-bar-chart-steps"></i>

                                            <div>
                                                <span>Sprat</span>
                                                <strong><?php echo esc_html($floor); ?></strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <?php if ($broj_soba) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-door-open"></i>

                                            <div>
                                                <span>Broj soba</span>
                                                <strong><?php echo esc_html($broj_soba); ?></strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <?php if ($orijentacija) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-compass"></i>

                                            <div>
                                                <span>Orijentacija</span>
                                                <strong><?php echo esc_html($orijentacija); ?></strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <?php if ($grejanje) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-thermometer-half"></i>

                                            <div>
                                                <span>Grejanje</span>
                                                <strong><?php echo esc_html($grejanje); ?></strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <?php if ($terasa !== '' && $terasa !== null) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-columns-gap"></i>

                                            <div>
                                                <span>Terasa</span>
                                                <strong><?php echo $terasa ? 'Da' : 'Ne'; ?></strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <?php if ($parking !== '' && $parking !== null) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-car-front"></i>

                                            <div>
                                                <span>Parking</span>
                                                <strong><?php echo $parking ? 'Da' : 'Ne'; ?></strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>


                                <?php if ($status) : ?>

                                    <div class="col-md-6 col-xl-4">

                                        <div class="detail-item">

                                            <i class="bi bi-check-circle"></i>

                                            <div>
                                                <span>Status</span>

                                                <strong class="<?php echo esc_attr($status_class); ?>">
                                                    <?php echo esc_html($status_label); ?>
                                                </strong>
                                            </div>

                                        </div>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>


                    <!-- OPIS -->
                    <div
                        class="apartment-tab-panel"
                        data-panel="opis"
                    >

                        <h2 class="section-title">
                            Opis stana
                        </h2>


                        <div class="apartment-description">

                            <?php
                            echo apply_filters(
                                'the_content',
                                $current_post->post_content
                            );
                            ?>

                        </div>


                        <div class="row g-3 mt-4">

                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="bi bi-stars"></i>
                                    Moderan dizajn
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="bi bi-tree"></i>
                                    Mirna lokacija
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="bi bi-lightning-charge"></i>
                                    Energetski efikasna gradnja
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="feature-item">
                                    <i class="bi bi-window"></i>
                                    Kvalitetna stolarija
                                </div>
                            </div>

                        </div>

                    </div>


                    <!-- OSNOVA -->
                    <?php if ($floor_plan_id) : ?>

                        <div
                            class="apartment-tab-panel"
                            data-panel="osnova"
                        >

                            <h2 class="section-title">
                                Osnova stana
                            </h2>


                            <div class="floor-plan-card">

                                <a
                                    href="<?php echo esc_url(wp_get_attachment_image_url($floor_plan_id, 'full')); ?>"
                                    data-fancybox="floor-plan"
                                >

                                    <?php
                                    echo wp_get_attachment_image(
                                        $floor_plan_id,
                                        'large',
                                        false,
                                        [
                                            'class' => 'img-fluid w-100'
                                        ]
                                    );
                                    ?>

                                </a>

                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- LOKACIJA -->
                    <?php if ($location_map || $location_description) : ?>

                        <div
                            class="apartment-tab-panel"
                            data-panel="lokacija"
                        >

                            <h2 class="section-title">
                                Lokacija
                            </h2>


                            <div class="apartment-location">

                                <?php if ($location_map) : ?>

                                    <div class="apartment-location-map">

                                        <?php

                                        $allowed_map_html = [

                                            'iframe' => [
                                                'src'             => true,
                                                'width'           => true,
                                                'height'          => true,
                                                'style'           => true,
                                                'allowfullscreen' => true,
                                                'loading'         => true,
                                                'referrerpolicy'  => true,
                                                'class'           => true,
                                                'frameborder'     => true,
                                            ],

                                            'p' => [
                                                'class' => true,
                                            ],

                                            'br' => [],
                                        ];

                                        echo wp_kses(
                                            html_entity_decode($location_map),
                                            $allowed_map_html
                                        );

                                        ?>

                                    </div>

                                <?php endif; ?>


                                <?php if ($location_description) : ?>

                                    <div class="apartment-location-description">

                                        <h3>
                                            <?php echo esc_html($location_title); ?>
                                        </h3>

                                        <p>
                                            <?php
                                            echo nl2br(
                                                esc_html($location_description)
                                            );
                                            ?>
                                        </p>

                                    </div>

                                <?php endif; ?>

                            </div>

                        </div>

                    <?php endif; ?>

                </div>


                <!-- DESNO - UVEK OSTANE -->
                <div class="col-lg-4">

                    <div
                        id="kontakt-stan"
                        class="apartment-contact-card"
                    >

                        <span class="contact-eyebrow">
                            Zainteresovani ste?
                        </span>

                        <h3>
                            Cena na upit
                        </h3>

                        <p>
                            Za više informacija o ovom stanu kontaktirajte naš prodajni tim.
                        </p>

                        <a
                            href="mailto:luxor-investments@gmail.com?subject=<?php echo rawurlencode('Upit za stan: ' . get_the_title($post_id)); ?>"
                            class="btn btn-dark w-100 py-3 mb-3 btn-dark-blue"
                        >
                            <i class="bi bi-envelope me-2"></i>
                            Pošalji upit
                        </a>

                        <a
                            href="tel:+381601234567"
                            class="btn btn-outline-dark w-100 py-3 btn-border-primary"
                        >
                            <i class="bi bi-telephone me-2"></i>
                            060 123 45 67
                        </a>

                    </div>

                </div>

            </div>

        </section>


        <!-- SLIČNI STANOVI -->
        <?php

        $similar_args = [

            'post_type'      => 'stanovi',
            'post_status'    => 'publish',
            'posts_per_page' => 4,
            'post__not_in'   => [$post_id],
            'orderby'        => 'date',
            'order'          => 'DESC',

        ];

        $similar_query = new WP_Query($similar_args);

        ?>


        <section class="similar-apartments-section">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="section-title mb-0">
                    Slični stanovi
                </h2>


                <a
                    href="<?php echo esc_url(home_url('/projekti-stranica/')); ?>"
                    class="similar-apartments-all"
                >
                    Pogledaj sve stanove

                    <i class="bi bi-arrow-right ms-2"></i>
                </a>

            </div>


            <?php if ($similar_query->have_posts()) : ?>

                <div class="row g-4">

                    <?php

                    while ($similar_query->have_posts()) :

                        $similar_query->the_post();

                        $similar_id = get_the_ID();

                        $similar_status = get_field(
                            'status',
                            $similar_id
                        );

                        $similar_area = get_field(
                            'kvadratura',
                            $similar_id
                        );

                        $similar_floor = get_field(
                            'sprat',
                            $similar_id
                        );

                        $similar_types = get_the_terms(
                            $similar_id,
                            'tip_stana'
                        );

                        $similar_type_name = '';

                        if (
                            $similar_types &&
                            !is_wp_error($similar_types)
                        ) {

                            $similar_type_name =
                                $similar_types[0]->name;
                        }


                        $similar_status_class = '';

                        switch ($similar_status) {

                            case 'slobodan':
                                $similar_status_class =
                                    'similar-status-free';
                                break;

                            case 'rezervisan':
                                $similar_status_class =
                                    'similar-status-reserved';
                                break;

                            case 'prodat':
                                $similar_status_class =
                                    'similar-status-sold';
                                break;
                        }

                    ?>

                        <div class="col-sm-6 col-lg-3">

                            <article class="similar-apartment-card">

                                <div class="similar-apartment-image">

                                    <a href="<?php the_permalink(); ?>">

                                        <?php if (has_post_thumbnail()) : ?>

                                            <?php

                                            the_post_thumbnail(
                                                'large',
                                                [
                                                    'class' => 'w-100'
                                                ]
                                            );

                                            ?>

                                        <?php endif; ?>

                                    </a>


                                    <?php if ($similar_status) : ?>

                                        <span
                                            class="similar-status <?php echo esc_attr($similar_status_class); ?>"
                                        >

                                            <span></span>

                                            <?php
                                            echo esc_html(
                                                ucfirst($similar_status)
                                            );
                                            ?>

                                        </span>

                                    <?php endif; ?>

                                </div>


                                <div class="similar-apartment-content">

                                    <h3>

                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>

                                    </h3>


                                    <?php if ($similar_type_name) : ?>

                                        <div class="similar-type">

                                            <?php
                                            echo esc_html(
                                                $similar_type_name
                                            );
                                            ?>

                                        </div>

                                    <?php endif; ?>


                                    <div class="similar-meta">

                                        <?php if ($similar_area) : ?>

                                            <span>
                                                <?php echo esc_html($similar_area); ?> m²
                                            </span>

                                        <?php endif; ?>


                                        <?php if ($similar_floor !== '' && $similar_floor !== null) : ?>

                                            <span>
                                                Sprat <?php echo esc_html($similar_floor); ?>
                                            </span>

                                        <?php endif; ?>

                                    </div>

                                </div>

                            </article>

                        </div>

                    <?php endwhile; ?>

                </div>

            <?php else : ?>

                <p class="text-muted">
                    Trenutno nema drugih stanova.
                </p>

            <?php endif; ?>


            <?php wp_reset_postdata(); ?>

        </section>

    </div>

</section>


   <!-- =====================================================
         BOTTOM CONTACT
    ====================================================== -->
    <?php
$phone = get_field('phone', 'options');
$email = get_field('email', 'options');

$phone_link = $phone ? preg_replace('/[^0-9+]/', '', $phone) : '';
?>

<section class="projects-contact">
    <div class="container">

        <div class="row align-items-center gy-5">

            <!-- LEVA STRANA -->
            <div class="col-12 col-lg-6">

                <div class="projects-contact__content">

                    <span class="projects-contact__eyebrow">
                        Kontakt
                    </span>

                    <h2>
                        Zainteresovani ste za neki od stanova?
                    </h2>

                    <p>
                        Naš prodajni tim vam je na raspolaganju za sve informacije
                        o projektima, dostupnim stanovima i uslovima kupovine.
                    </p>

                </div>

            </div>

            <!-- DESNA STRANA -->
            <div class="col-12 col-lg-6">

                <div class="projects-contact__info">

                    <?php if ($phone) : ?>

                        <a
                            href="tel:<?php echo esc_attr($phone_link); ?>"
                            class="projects-contact__item"
                        >

                            <span class="projects-contact__icon">
                                <!-- <i class="fa-solid fa-phone"></i> -->
                                    <i class="bi bi-telephone"></i>

                            </span>

                            <span class="projects-contact__item-content">

                                <small>Pozovite nas</small>

                                <strong>
                                    <?php echo esc_html($phone); ?>
                                </strong>

                            </span>

                            <span class="projects-contact__arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </a>

                    <?php endif; ?>


                    <?php if ($email) : ?>

                        <a
                            href="mailto:<?php echo esc_attr($email); ?>"
                            class="projects-contact__item"
                        >

                            <span class="projects-contact__icon">
                                    <i class="bi bi-envelope"></i>
                            </span>

                            <span class="projects-contact__item-content">

                                <small>Pišite nam</small>

                                <strong>
                                    <?php echo esc_html($email); ?>
                                </strong>

                            </span>

                            <span class="projects-contact__arrow">
                                <i class="bi bi-arrow-right"></i>
                            </span>

                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>
</section>



<?php

    setup_postdata($current_post);
    wp_reset_postdata();

endif;

get_footer();
