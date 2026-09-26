<section class="apartments-section py-5">
    <div class="container">



        <div class="row">

            <!-- FILTERI -->
            <div class="col-lg-3">

                <div class="apartments-filters bg-white border rounded-4 p-4 shadow-sm">


                    <!-- PROJEKAT -->
                    <div class="filter-group border-bottom pb-4 mb-4">

                        <h5 class="mb-2">Projekat</h5>

                        <?php
                        $projects = get_posts([
                            'post_type'      => 'projekti',
                            'posts_per_page' => -1,
                            'post_status'    => 'publish',
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                        ]);

                        foreach ($projects as $project) :
                        ?>

                            <div class="form-check">

                                <input
                                    class="form-check-input apartment-filter"
                                    type="checkbox"
                                    name="projekat[]"
                                    value="<?php echo esc_attr($project->ID); ?>"
                                    id="project-<?php echo esc_attr($project->ID); ?>"
                                >

                                <label
                                    class="form-check-label"
                                    for="project-<?php echo esc_attr($project->ID); ?>"
                                >
                                    <?php echo esc_html($project->post_title); ?>
                                </label>

                            </div>

                        <?php endforeach; ?>

                    </div>



                    <!-- TIP STANA -->
                    <div class="filter-group border-bottom pb-4 mb-4">
                        <h5 class="mb-2">Tip stana</h5>

                        <?php
                        $types = get_terms([
                            'taxonomy'   => 'tip_stana',
                            'hide_empty' => false,
                        ]);

                        if (!is_wp_error($types)) :
                            foreach ($types as $type) :
                        ?>

                            <div class="form-check">
                                <input
                                    class="form-check-input apartment-filter"
                                    type="checkbox"
                                    name="tip_stana[]"
                                    value="<?php echo esc_attr($type->slug); ?>"
                                    id="type-<?php echo esc_attr($type->term_id); ?>"
                                >

                                <label
                                    class="form-check-label"
                                    for="type-<?php echo esc_attr($type->term_id); ?>"
                                >
                                    <?php echo esc_html($type->name); ?>
                                </label>
                            </div>

                        <?php
                            endforeach;
                        endif;
                        ?>

                    </div>


                    <!-- SPRAT -->
                    <div class="filter-group border-bottom pb-4 mb-4">

                        <h5 class="mb-2">Sprat</h5>

                        <?php for ($i = 1; $i <= 5; $i++) : ?>

                            <div class="form-check">

                                <input
                                    class="form-check-input apartment-filter"
                                    type="checkbox"
                                    name="sprat[]"
                                    value="<?php echo $i; ?>"
                                    id="floor-<?php echo $i; ?>"
                                >

                                <label
                                    class="form-check-label"
                                    for="floor-<?php echo $i; ?>"
                                >
                                    <?php echo $i; ?>. sprat
                                </label>

                            </div>

                        <?php endfor; ?>

                    </div>

                    <!-- STATUS -->
                    <div class="filter-group border-bottom pb-4 mb-4">

                        <h5 class="mb-2">Status</h5>

                        <div class="form-check">
                            <input
                                class="form-check-input apartment-filter"
                                type="checkbox"
                                name="status[]"
                                value="slobodan"
                                id="status-free"
                            >
                            <label class="form-check-label" for="status-free">
                                Slobodan
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input apartment-filter"
                                type="checkbox"
                                name="status[]"
                                value="rezervisan"
                                id="status-reserved"
                            >
                            <label class="form-check-label" for="status-reserved">
                                Rezervisan
                            </label>
                        </div>

                        <div class="form-check">
                            <input
                                class="form-check-input apartment-filter"
                                type="checkbox"
                                name="status[]"
                                value="prodat"
                                id="status-sold"
                            >
                            <label class="form-check-label" for="status-sold">
                                Prodat
                            </label>
                        </div>

                    </div>


                    <button
                        type="button"
                        id="reset-apartment-filters"
                        class="btn btn-dark w-100 py-3 d-flex align-items-center justify-content-center gap-2 btn-dark-blue"
                    >
                        <i class="bi bi-funnel"></i>
                        Poništi filtere
                    </button>

                </div>

            </div>


            <!-- STANOVI -->
            <div class="col-lg-9">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <!-- <h2>Dostupni stanovi</h2> -->
                     <div>
                        <span class="projects-eyebrow projects-eyebrow--dark">
                            Luxor Investments
                        </span>

                        <h2>Dostupni stanovi</h2>
                    </div>


                    <div id="apartments-count" class="badge bg-light text-dark rounded-pill px-3 py-2"></div>

                </div>


                <div id="apartments-loader" style="display:none;">
                    Učitavanje...
                </div>


                <div
                    id="apartments-results"
                    class="row g-4"
                >
                </div>

                <div class="apartments-summary d-flex align-items-center gap-3 mt-4">
                    <div class="flex-grow-1 border-top"></div>

                    <div id="apartments-summary-text" class="small text-muted text-nowrap">
                        Prikazano 0 od 0 stanova
                    </div>

                    <div class="flex-grow-1 border-top"></div>
                </div>

            </div>

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
