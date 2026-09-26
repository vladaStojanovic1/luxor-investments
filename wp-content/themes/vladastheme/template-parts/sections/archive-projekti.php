<?php
/**
 * Archive template za CPT: Projekti
 */

get_header();

/**
 * Učitavamo sve projekte.
 *
 * Ako je slug tvog CPT-a drugačiji od "projekti",
 * promeni post_type ispod.
 */
$projekti = new WP_Query([
    'post_type'      => 'projekti',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => [
        'menu_order' => 'ASC',
        'date'       => 'DESC',
    ],
]);

/**
 * Helper za status projekta.
 */
function luxor_project_status_label($status) {

    $statuses = [
        'u_izgradnji' => 'U izgradnji',
        'uskoro'      => 'Uskoro',
        'zavrsen'     => 'Završen',
    ];

    return $statuses[$status] ?? '';
}
?>


    <!-- ========================================
         GLAVNI SADRŽAJ
    ========================================= -->

<section class="projects-content">

        <div class="container">


            <!-- ========================================
                 FILTER
            ========================================= -->

            <div class="projects-filter">

                <div class="projects-filter__field">

                    <label for="project-status-filter">
                        Status projekta
                    </label>

                    <div class="projects-select-wrap">

                        <svg
                            class="projects-select-icon"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            aria-hidden="true"
                        >
                            <path d="M14.7 6.3a4 4 0 0 0-5.6 5.6l-6.8 6.8a2 2 0 0 0 2.8 2.8l6.8-6.8a4 4 0 0 0 5.6-5.6l-2.3 2.3-2.6-.7-.7-2.6 2.8-1.8z"/>
                        </svg>

                        <select id="project-status-filter">

                            <option value="all">
                                Svi projekti
                            </option>

                            <option value="u_izgradnji">
                                U izgradnji
                            </option>

                            <option value="uskoro">
                                Uskoro
                            </option>

                            <option value="zavrsen">
                                Završen
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            <!-- ========================================
                 NASLOV LISTE
            ========================================= -->

            <div class="projects-heading">

                <div class='mt-3'>
                    <span class="projects-eyebrow projects-eyebrow--dark">
                        Luxor Investments
                    </span>

                    <h2>
                        Aktuelni projekti
                    </h2>
                </div>

                <div class="projects-count">

                    Pronađeno:

                    <strong id="projects-count">
                        <?php echo esc_html($projekti->found_posts); ?>
                    </strong>

                </div>

            </div>


            <!-- ========================================
                 PROJEKTI
            ========================================= -->

            <div class="projects-list" id="projects-list">

                <?php if ($projekti->have_posts()) : ?>

                    <?php while ($projekti->have_posts()) : $projekti->the_post(); ?>

                        <?php

                        $project_id = get_the_ID();

                        /*
                         * ACF POLJA
                         */
                        $lokacija         = get_field('lokacija', $project_id);
                        $adresa           = get_field('adresa', $project_id);
                        $rok_zavrsetka    = get_field('rok_zavrsetka', $project_id);
                        $broj_stanova     = get_field('broj_stanova', $project_id);
                        $opis_projekta    = get_field('opis_projekta', $project_id);
                        $galerija         = get_field('galerija', $project_id);
                        $status_projekta  = get_field('status_projekta', $project_id);

                        $status_label = luxor_project_status_label($status_projekta);

                        /*
                         * Broj fotografija u galeriji.
                         */
                        $gallery_count = is_array($galerija)
                            ? count($galerija)
                            : 0;

                        /*
                         * Kraći opis za archive karticu.
                         *
                         * wp_strip_all_tags uklanja HTML iz WYSIWYG polja.
                         */
                        $short_description = '';

                        if ($opis_projekta) {

                            $short_description = wp_trim_words(
                                wp_strip_all_tags($opis_projekta),
                                32,
                                '...'
                            );
                        }

                        ?>

                        <article
                            class="project-card"
                            data-project-card
                            data-status="<?php echo esc_attr($status_projekta); ?>"
                        >

                            <!-- ========================================
                                 SLIKA
                            ========================================= -->

                            <div class="project-card__media">

                                <a
                                    href="<?php the_permalink(); ?>"
                                    class="project-card__image-link"
                                    aria-label="<?php echo esc_attr(get_the_title()); ?>"
                                >

                                    <?php if (has_post_thumbnail()) : ?>

                                        <?php
                                        the_post_thumbnail(
                                            'large',
                                            [
                                                'class'   => 'project-card__image',
                                                'loading' => 'lazy',
                                            ]
                                        );
                                        ?>

                                    <?php else : ?>

                                        <div class="project-card__placeholder">
                                            <span>Nema fotografije</span>
                                        </div>

                                    <?php endif; ?>

                                </a>


                                <!-- STATUS -->

                                <?php if ($status_label) : ?>

                                    <span
                                        class="
                                            project-status
                                            project-status--<?php echo esc_attr($status_projekta); ?>
                                        "
                                    >

                                        <?php echo esc_html($status_label); ?>

                                    </span>

                                <?php endif; ?>


                                <!-- BROJ FOTOGRAFIJA -->

                                <?php if ($gallery_count > 0) : ?>

                                    <div class="project-gallery-count">

                                        <svg
                                            width="18"
                                            height="18"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            aria-hidden="true"
                                        >
                                            <rect x="3" y="5" width="18" height="14" rx="2"/>
                                            <circle cx="8.5" cy="10" r="1.5"/>
                                            <path d="m21 15-5-5L5 19"/>
                                        </svg>

                                        <span>
                                            <?php echo esc_html($gallery_count); ?>

                                            <?php
                                            echo $gallery_count === 1
                                                ? ' fotografija'
                                                : ' fotografija';
                                            ?>
                                        </span>

                                    </div>

                                <?php endif; ?>

                            </div>


                            <!-- ========================================
                                 INFORMACIJE
                            ========================================= -->

                            <div class="project-card__content">

                                <div class="project-card__top">

                                    <div class="project-card__title-area">

                                        <?php if ($adresa) : ?>

                                            <span class="project-card__location">

                                                <svg
                                                    width="18"
                                                    height="18"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.8"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    aria-hidden="true"
                                                >
                                                    <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                                                    <circle cx="12" cy="10" r="2.5"/>
                                                </svg>

                                                <?php echo esc_html($adresa); ?>

                                            </span>

                                        <?php endif; ?>


                                        <h2 class="project-card__title">

                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_title(); ?>
                                            </a>

                                        </h2>

                                    </div>


                                    <!-- ROK ZAVRŠETKA -->

                                    <?php if ($rok_zavrsetka) : ?>

                                        <div class="project-card__deadline">

                                            <div class="project-card__deadline-icon">

                                                <svg
                                                    width="22"
                                                    height="22"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.7"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    aria-hidden="true"
                                                >
                                                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                                                    <path d="M16 3v4M8 3v4M3 11h18"/>
                                                </svg>

                                            </div>

                                            <div>

                                                <span>
                                                    Rok završetka
                                                </span>

                                                <strong>
                                                    <?php echo esc_html($rok_zavrsetka); ?>
                                                </strong>

                                            </div>

                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- ========================================
                                     META PODACI
                                ========================================= -->

                                <div class="project-card__meta">


                                    <!-- LOKACIJA -->

                                    <?php if ($adresa) : ?>

                                        <div class="project-meta-item">

                                            <div class="project-meta-item__icon">

                                                <svg
                                                    width="25"
                                                    height="25"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.6"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    aria-hidden="true"
                                                >
                                                    <path d="M4 21V10l8-4 8 4v11"/>
                                                    <path d="M9 21v-5h6v5"/>
                                                    <path d="M8 11h.01M12 11h.01M16 11h.01"/>
                                                </svg>

                                            </div>

                                            <div>

                                                <span>
                                                    Adresa
                                                </span>

                                                <strong>
                                                    <?php echo esc_html($adresa); ?>
                                                </strong>

                                            </div>

                                        </div>

                                    <?php endif; ?>


                                    <!-- BROJ STANOVA -->

                                    <?php if ($broj_stanova) : ?>

                                        <div class="project-meta-item">

                                            <div class="project-meta-item__icon">

                                                <svg
                                                    width="25"
                                                    height="25"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.6"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    aria-hidden="true"
                                                >
                                                    <rect x="4" y="3" width="16" height="18" rx="1"/>
                                                    <path d="M8 7h2M14 7h2M8 11h2M14 11h2M8 15h2M14 15h2"/>
                                                </svg>

                                            </div>

                                            <div>

                                                <span>
                                                    Broj stanova
                                                </span>

                                                <strong>
                                                    <?php echo esc_html($broj_stanova); ?>
                                                </strong>

                                            </div>

                                        </div>

                                    <?php endif; ?>


                                    <!-- STATUS -->

                                    <?php if ($status_label) : ?>

                                        <div class="project-meta-item">

                                            <div class="project-meta-item__icon">

                                                <svg
                                                    width="25"
                                                    height="25"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="1.6"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    aria-hidden="true"
                                                >
                                                    <circle cx="12" cy="12" r="9"/>
                                                    <path d="m8 12 2.5 2.5L16 9"/>
                                                </svg>

                                            </div>

                                            <div>

                                                <span>
                                                    Status
                                                </span>

                                                <strong>
                                                    <?php echo esc_html($status_label); ?>
                                                </strong>

                                            </div>

                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- ========================================
                                     OPIS
                                ========================================= -->

                                <?php if ($short_description) : ?>

                                    <div class="project-card__description">

                                        <p>
                                            <?php echo esc_html($short_description); ?>
                                        </p>

                                    </div>

                                <?php endif; ?>


                                <!-- ========================================
                                     FOOTER KARTICE
                                ========================================= -->

                                <div class="project-card__footer">

                                    <a
                                        href="<?php the_permalink(); ?>"
                                        class="project-card__link"
                                    >
                                        Pogledaj projekat

                                        <span aria-hidden="true">→</span>
                                    </a>


                                    <?php if ($gallery_count > 0) : ?>

                                        <a
                                            href="<?php the_permalink(); ?>#galerija"
                                            class="project-card__gallery-link"
                                        >
                                            Galerija

                                            <span aria-hidden="true">→</span>
                                        </a>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </article>

                    <?php endwhile; ?>

                    <?php wp_reset_postdata(); ?>


                <?php else : ?>

                    <div class="projects-empty projects-empty--initial">

                        <h3>
                            Trenutno nema projekata.
                        </h3>

                        <p>
                            Novi projekti će biti prikazani ovde čim budu objavljeni.
                        </p>

                    </div>

                <?php endif; ?>

            </div>


            <!-- ========================================
                 NEMA REZULTATA FILTERA
            ========================================= -->

            <div
                class="projects-empty"
                id="projects-filter-empty"
                hidden
            >

                <h3>
                    Nema projekata sa izabranim statusom.
                </h3>

                <p>
                    Izaberite drugi status ili prikažite sve projekte.
                </p>

            </div>

        </div>

</section>


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


<?php get_footer(); ?>