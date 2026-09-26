<?php
/**
 * Single Projekat
 */

include(get_template_directory() . '/inc/_partials/_navigation.php');
get_header();

while (have_posts()) :
    the_post();

    $project_id = get_the_ID();

    // =========================================================
    // ACF PROJECT FIELDS
    // =========================================================

    $status_projekta = get_field('status_projekta');
    $lokacija        = get_field('lokacija');
    $adresa          = get_field('adresa');
    $rok_zavrsetka   = get_field('rok_zavrsetka');
    $broj_stanova    = get_field('broj_stanova');
    $opis_projekta   = get_field('opis_projekta');
    $galerija        = get_field('galerija');

    /*
     * Kratak opis u HERO delu.
     *
     * Ako koristiš excerpt - koristi njega.
     * Ako nema excerpt-a, uzimamo skraćeni opis projekta.
     */
    $short_description = get_the_excerpt();

    if (!$short_description && $opis_projekta) {
        $short_description = wp_trim_words(
            wp_strip_all_tags($opis_projekta),
            20
        );
    }

    // =========================================================
    // GALLERY
    // =========================================================

    $gallery_images = array();

    if (!empty($galerija) && is_array($galerija)) {

        foreach ($galerija as $image) {

            if (is_array($image)) {

                $image_id = isset($image['ID'])
                    ? (int) $image['ID']
                    : 0;

                $full_url = $image_id
                    ? wp_get_attachment_image_url($image_id, 'full')
                    : ($image['url'] ?? '');

                $thumb_url = $image_id
                    ? wp_get_attachment_image_url($image_id, 'medium_large')
                    : ($image['sizes']['medium_large'] ?? $full_url);

                $gallery_images[] = array(
                    'id'    => $image_id,
                    'url'   => $full_url,
                    'thumb' => $thumb_url,
                    'alt'   => $image['alt'] ?? get_the_title(),
                );

            } elseif (is_numeric($image)) {

                $image_id = (int) $image;

                $gallery_images[] = array(
                    'id'    => $image_id,
                    'url'   => wp_get_attachment_image_url($image_id, 'full'),
                    'thumb' => wp_get_attachment_image_url($image_id, 'medium_large'),
                    'alt'   => get_post_meta($image_id, '_wp_attachment_image_alt', true),
                );
            }
        }
    }

    /*
     * Ako galerija nije popunjena,
     * koristi featured image.
     */
    if (empty($gallery_images) && has_post_thumbnail()) {

        $thumbnail_id = get_post_thumbnail_id();

        $gallery_images[] = array(
            'id'    => $thumbnail_id,
            'url'   => get_the_post_thumbnail_url($project_id, 'full'),
            'thumb' => get_the_post_thumbnail_url($project_id, 'medium_large'),
            'alt'   => get_the_title(),
        );
    }

    // =========================================================
    // STANOVI KOJI PRIPADAJU PROJEKTU
    // =========================================================

    /*
     * Na CPT-u stanova postoji ACF Post Object:
     *
     * projekat
     *
     * Ovde tražimo stanove čije polje "projekat"
     * pokazuje na trenutni projekat.
     */

    $apartments_query = new WP_Query(array(
        'post_type'      => 'stanovi',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order date',
        'order'          => 'ASC',

        'meta_query' => array(
            array(
                'key'     => 'projekat',
                'value'   => '"' . $project_id . '"',
                'compare' => 'LIKE',
            ),
        ),
    ));

    /*
     * Ako ACF Post Object vraća samo ID,
     * LIKE sa navodnicima možda neće pronaći rezultat.
     *
     * Zato imamo fallback.
     */
    if (!$apartments_query->have_posts()) {

        wp_reset_postdata();

        $apartments_query = new WP_Query(array(
            'post_type'      => 'stanovi',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order date',
            'order'          => 'ASC',

            'meta_query' => array(
                array(
                    'key'     => 'projekat',
                    'value'   => $project_id,
                    'compare' => '=',
                ),
            ),
        ));
    }

?>


    <!-- =====================================================
         HERO
    ====================================================== -->

    <section class="project-hero">

        <div class="container">

            <!-- Breadcrumb -->
            <div class="project-breadcrumb mb-5">

                <a href="<?php echo esc_url(home_url('/')); ?>">
                    Početna
                </a>

                <span>/</span>

                <a href="<?php echo esc_url(get_post_type_archive_link('projekti')); ?>">
                    Projekti
                </a>

                <span>/</span>

                <span>
                    <?php the_title(); ?>
                </span>

            </div>


            <div class="row g-5">


                <!-- =========================================
                     LEFT
                ========================================== -->

                <div class="col-12 col-lg-5">

                    <div class="project-hero-content">

                        <h1 class="project-title">
                            <?php the_title(); ?>
                        </h1>


                        <?php if ($short_description) : ?>

                            <div class="project-short-description">
                                <?php echo wp_kses_post(
                                    wpautop($short_description)
                                ); ?>
                            </div>

                        <?php endif; ?>


                        <!-- META -->

                        <div class="row g-4 project-hero-meta">


                            <?php if ($adresa) : ?>

                                <div class="col-6">

                                    <div class="project-meta-item">

                                        <div class="project-meta-icon">
                                            <i class="bi bi-geo-alt-fill"></i>
                                        </div>

                                        <div>

                                            <span class="project-meta-label">
                                                Lokacija
                                            </span>

                                            <strong>
                                                <?php echo esc_html($adresa); ?>
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            <?php endif; ?>


                            <?php if ($broj_stanova) : ?>

                                <div class="col-6">

                                    <div class="project-meta-item">

                                        <div class="project-meta-icon">
                                            <i class="bi bi-buildings"></i>
                                        </div>

                                        <div>

                                            <span class="project-meta-label">
                                                Broj stanova
                                            </span>

                                            <strong>
                                                <?php echo esc_html($broj_stanova); ?>
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            <?php endif; ?>


                            <?php if ($rok_zavrsetka) : ?>

                                <div class="col-6">

                                    <div class="project-meta-item">

                                        <div class="project-meta-icon">
                                            <i class="bi bi-calendar3"></i>
                                        </div>

                                        <div>

                                            <span class="project-meta-label">
                                                Rok završetka
                                            </span>

                                            <strong>
                                                <?php echo esc_html($rok_zavrsetka); ?>
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            <?php endif; ?>


                            <?php if ($status_projekta) : ?>

                                <div class="col-6">

                                    <div class="project-meta-item">

                                        <div class="project-meta-icon">
                                            <i class="bi bi-check-circle"></i>
                                        </div>

                                        <div>

                                            <span class="project-meta-label">
                                                Status
                                            </span>

                                            <strong>
                                                <?php echo esc_html($status_projekta); ?>
                                            </strong>

                                        </div>

                                    </div>

                                </div>

                            <?php endif; ?>

                        </div>


                        <!-- BUTTONS -->

                        <div class="project-hero-actions">

                            <a
                                href="mailto:luxor.investments.doo@gmail.com"
                                class="btn btn-dark px-4 py-3 btn-dark-blue"
                            >
                                <!-- <i class="bi bi-envelope"></i> -->

                                <span class='me-2'>Pošalji upit</span>

                                <i class="bi bi-arrow-right"></i>
                            </a>


                            <a
                                href="tel:+38162371371"
                                class="btn btn-outline-dark px-4 py-3 btn-border-primary"
                            >
                                <i class="bi bi-telephone me-2"></i>

                                <span>Pozovi</span>

                                <!-- <i class="bi bi-arrow-right"></i> -->
                            </a>

                        </div>

                    </div>

                </div>


                <!-- =========================================
                     RIGHT - GALLERY
                ========================================== -->

                <div class="col-12 col-lg-7">

                    <?php if (!empty($gallery_images)) : ?>

                        <div class="project-gallery-v2">


                            <!-- MAIN SWIPER -->

                            <div class="swiper project-gallery-slider">

                                <div class="swiper-wrapper">

                                    <?php foreach ($gallery_images as $index => $image) : ?>

                                        <div class="swiper-slide">

                                            <img
                                                src="<?php echo esc_url($image['url']); ?>"
                                                alt="<?php echo esc_attr($image['alt']); ?>"
                                                class="project-gallery-image"
                                                loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>"
                                            >

                                        </div>

                                    <?php endforeach; ?>

                                </div>


                                <!-- Counter -->

                                <div class="project-gallery-counter">

                                    <i class="bi bi-images"></i>

                                    <span class="project-gallery-current">
                                        1
                                    </span>

                                    <span>/</span>

                                    <span>
                                        <?php echo count($gallery_images); ?>
                                    </span>

                                    <span class="project-gallery-counter-text">
                                        fotografija
                                    </span>

                                </div>


                                <?php if (count($gallery_images) > 1) : ?>

                                    <button
                                        type="button"
                                        class="project-gallery-arrow project-gallery-arrow-prev"
                                        aria-label="Prethodna fotografija"
                                    >
                                        <i class="bi bi-arrow-left"></i>
                                    </button>


                                    <button
                                        type="button"
                                        class="project-gallery-arrow project-gallery-arrow-next"
                                        aria-label="Sledeća fotografija"
                                    >
                                        <i class="bi bi-arrow-right"></i>
                                    </button>

                                <?php endif; ?>


                                <button
                                    type="button"
                                    class="project-gallery-fullscreen"
                                    aria-label="Otvori fotografiju"
                                >
                                    <i class="bi bi-arrows-fullscreen"></i>
                                </button>

                            </div>


                            <!-- THUMBNAILS -->

                           
                            <?php if (count($gallery_images) > 1) : ?>

                                <div class="project-gallery-thumbnails">

                                    <?php foreach ($gallery_images as $index => $image) : ?>

                                        <button
                                            type="button"
                                            class="project-gallery-thumbnail <?php echo $index === 0 ? 'active' : ''; ?>"
                                            data-slide="<?php echo esc_attr($index); ?>"
                                            aria-label="Prikaži fotografiju <?php echo esc_attr($index + 1); ?>"
                                        >

                                            <img
                                                src="<?php echo esc_url($image['thumb']); ?>"
                                                alt="<?php echo esc_attr($image['alt']); ?>"
                                                loading="lazy"
                                            >

                                        </button>

                                    <?php endforeach; ?>

                                </div>

                            <?php endif; ?>


                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         TABS
    ====================================================== -->

    <section class="project-tabs-section">

        <div class="container">

            <div class="project-tabs">

                <button
                    type="button"
                    class="project-tab active"
                    data-project-tab="info"
                >
                    Osnovne informacije
                </button>

                <button
                    type="button"
                    class="project-tab"
                    data-project-tab="description"
                >
                    Opis projekta
                </button>

                <button
                    type="button"
                    class="project-tab"
                    data-project-tab="location"
                >
                    Lokacija
                </button>

            </div>

        </div>

    </section>


    <!-- =====================================================
         TAB CONTENT + PERMANENT CONTACT BOX
    ====================================================== -->

    <section class="project-content-section">

        <div class="container">

            <div class="row g-4 align-items-start">


                <!-- TAB CONTENT -->

                <div class="col-12 col-lg-8">


                    <!-- BASIC INFO -->

                    <div
                        class="project-tab-content active"
                        data-project-content="info"
                    >

                        <h2 class="project-section-title">
                            Osnovne informacije
                        </h2>


                        <div class="project-information-grid">


                            <div class="project-information-item">

                                <div class="project-information-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>

                                <div>

                                    <span>Lokacija</span>

                                    <strong>
                                        <?php echo esc_html($adresa ?: '-'); ?>
                                    </strong>

                                </div>

                            </div>


                            <div class="project-information-item">

                                <div class="project-information-icon">
                                    <i class="bi bi-signpost-2"></i>
                                </div>

                                <div>

                                    <span>Adresa</span>

                                    <strong>
                                        <?php echo esc_html($adresa ?: '-'); ?>
                                    </strong>

                                </div>

                            </div>


                            <div class="project-information-item">

                                <div class="project-information-icon">
                                    <i class="bi bi-calendar3"></i>
                                </div>

                                <div>

                                    <span>Rok završetka</span>

                                    <strong>
                                        <?php echo esc_html($rok_zavrsetka ?: '-'); ?>
                                    </strong>

                                </div>

                            </div>


                            <div class="project-information-item">

                                <div class="project-information-icon">
                                    <i class="bi bi-buildings"></i>
                                </div>

                                <div>

                                    <span>Broj stanova</span>

                                    <strong>
                                        <?php echo esc_html($broj_stanova ?: '-'); ?>
                                    </strong>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- DESCRIPTION -->

                    <div
                        class="project-tab-content"
                        data-project-content="description"
                    >

                        <h2 class="project-section-title">
                            Opis projekta
                        </h2>

                        <div class="project-description">

                            <?php
                            if ($opis_projekta) {
                                echo wp_kses_post($opis_projekta);
                            } else {
                                the_content();
                            }
                            ?>

                        </div>

                    </div>


                    <!-- LOCATION -->

                    <div
                        class="project-tab-content"
                        data-project-content="location"
                    >

                        <h2 class="project-section-title">
                            Lokacija
                        </h2>


                        <?php if ($adresa) : ?>

                            <p class="project-location-address">

                                <i class="bi bi-geo-alt-fill"></i>

                                <?php echo esc_html($adresa); ?>

                            </p>

                        <?php endif; ?>


                        <?php if ($lokacija) : ?>

                            <div class="project-map">
                                <?php echo $lokacija; ?>
                            </div>

                        <?php else : ?>

                            <p>Lokacija nije uneta.</p>

                        <?php endif; ?>

                    </div>

                </div>


                <!-- =========================================
                     CONTACT CARD
                     UVEK JE VIDLJIV
                ========================================== -->

                <div class="col-12 col-lg-4">

                    <aside class="project-contact-card">

                        <span class="project-contact-eyebrow">
                            ZAINTERESOVANI STE?
                        </span>

                        <h3>
                            Cena na upit
                        </h3>

                        <p>
                            Za više informacija o ovom projektu
                            kontaktirajte naš prodajni tim.
                        </p>


                        <a
                            href="mailto:luxor.investments.doo@gmail.com"
                            class="btn btn-dark w-100 py-3 mb-3 btn-dark-blue"
                        >
                            <i class="bi bi-envelope me-2"></i>
                            Pošalji upit
                        </a>


                        <a
                            href="tel:+38162371371"
                            class="btn btn-outline-dark w-100 py-3 btn-border-primary"
                        >
                            <i class="bi bi-telephone me-2"></i>
                            062 371 371
                        </a>

                    </aside>

                </div>

            </div>

        </div>

    </section>


    <!-- =====================================================
         APARTMENTS
    ====================================================== -->

    <?php if ($apartments_query->have_posts()) : ?>

        <section class="project-apartments">
            <div class="container">

                <div class="project-section-header project-apartments-header">
                    <h2>Stanovi u ovom projektu</h2>

                    <div class="project-apartments-header-right">
                        <?php $stanovi_archive = home_url('/prodaja-stanova/'); ?>

                        <?php if ($stanovi_archive) : ?>
                            <a href="<?php echo esc_url($stanovi_archive); ?>" class="project-apartments-all">
                                Pogledaj sve stanove
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        <?php endif; ?>

                        <div class="project-apartments-navigation">
                            <button type="button" class="project-apartments-arrow project-apartments-prev" aria-label="Prethodni stanovi">
                                <i class="bi bi-arrow-left"></i>
                            </button>

                            <button type="button" class="project-apartments-arrow project-apartments-next" aria-label="Sledeći stanovi">
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="swiper project-apartments-slider">
                    <div class="swiper-wrapper">

                        <?php
                        while ($apartments_query->have_posts()) :
                            $apartments_query->the_post();

                            $stan_status = get_field('status');
                            $broj_soba   = get_field('broj_soba');
                            $kvadratura  = get_field('kvadratura');
                        ?>

                            <div class="swiper-slide">
                                <article class="project-apartment-card">

                                    <a href="<?php the_permalink(); ?>" class="project-apartment-image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php
                                            the_post_thumbnail(
                                                'medium_large',
                                                array('loading' => 'lazy')
                                            );
                                            ?>
                                        <?php endif; ?>

                                        <?php if ($stan_status) : ?>
                                            <?php $status_class = sanitize_title($stan_status); ?>
                                            <span class="project-apartment-status status-<?php echo esc_attr($status_class); ?>">
                                                <?php echo esc_html($stan_status); ?>
                                            </span>
                                        <?php endif; ?>
                                    </a>

                                    <div class="project-apartment-content">
                                        <div>
                                            <h3>
                                                <a href="<?php the_permalink(); ?>">
                                                    <?php the_title(); ?>
                                                </a>
                                            </h3>

                                            <?php if ($broj_soba) : ?>
                                                <span>
                                                    <?php echo esc_html($broj_soba); ?>
                                                    <?php echo ((float) $broj_soba == 1) ? 'soba' : 'sobe'; ?>
                                                </span>
                                            <?php endif; ?>

                                            <?php if ($kvadratura) : ?>
                                                <strong><?php echo esc_html($kvadratura); ?> m²</strong>
                                            <?php endif; ?>
                                        </div>

                                        <a href="<?php the_permalink(); ?>" class="project-apartment-arrow" aria-label="<?php the_title_attribute(); ?>">
                                            <i class="bi bi-arrow-right"></i>
                                        </a>
                                    </div>

                                </article>
                            </div>

                        <?php endwhile; ?>

                    </div>
                </div>

            </div>
        </section>

        <?php wp_reset_postdata(); ?>

    <?php endif; ?>


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
                        Zainteresovani ste za neki od projekata?
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
endwhile;

get_footer();
?>