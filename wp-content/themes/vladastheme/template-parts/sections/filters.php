<section class="apartments-section">
    <div class="container">

        <!-- MOBILE FILTER BUTTON -->
        <div class="d-lg-none mb-4 text-end apartments-filter-mobile">
            <button
                class="apartments-filter-trigger btn-dark-blue"
                type="button"
                data-bs-toggle="offcanvas"
                data-bs-target="#apartmentsFilters"
                aria-controls="apartmentsFilters"
            >
                <i class="bi bi-funnel"></i>
                <span>Filteri</span>
            </button>
        </div>


        <div class="row">

            <!-- FILTERI -->
            <div class="col-lg-3">

                <div
                    class="offcanvas-lg offcanvas-start"
                    tabindex="-1"
                    id="apartmentsFilters"
                    aria-labelledby="apartmentsFiltersLabel"
                >

                    <!-- MOBILE OFFCANVAS HEADER -->
                    <div class="offcanvas-header d-lg-none">

                        <div>
                            <span class="projects-eyebrow projects-eyebrow--dark">
                                Luxor Investments
                            </span>

                            <h4
                                class="offcanvas-title"
                                id="apartmentsFiltersLabel"
                            >
                                Filtriraj stanove
                            </h4>
                        </div>

                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="offcanvas"
                            data-bs-target="#apartmentsFilters"
                            aria-label="Zatvori"
                        ></button>

                    </div>


                    <div class="offcanvas-body">

                        <div class="apartments-filters bg-white border rounded-4 p-4 shadow-sm w-100 mb-5">

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

                                    <label
                                        class="form-check-label"
                                        for="status-free"
                                    >
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

                                    <label
                                        class="form-check-label"
                                        for="status-reserved"
                                    >
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

                                    <label
                                        class="form-check-label"
                                        for="status-sold"
                                    >
                                        Prodat
                                    </label>

                                </div>

                            </div>


                            <!-- RESET -->
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

                </div>

            </div>


            <!-- STANOVI -->
            <div class="col-lg-9 mt-lg-0 mb-5 mb-lg-0">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <span class="projects-eyebrow projects-eyebrow--dark">
                            Luxor Investments
                        </span>

                        <h2>Dostupni stanovi</h2>

                    </div>


                    <div
                        id="apartments-count"
                        class="badge bg-light text-dark rounded-pill px-3 py-2"
                    ></div>

                </div>


                <!-- LOADER -->
                <div
                    id="apartments-loader-wrapper"
                    class="apartments-loader-wrapper"
                >
                    <span
                        class="loader"
                        id="apartments-loader"
                        style="display:none;"
                    ></span>
                </div>


                <!-- AJAX RESULTS -->
                <div
                    id="apartments-results"
                    class="row g-4"
                >
                </div>


                <!-- SUMMARY -->
                <div class="apartments-summary d-flex align-items-center gap-3 mt-4">

                    <div class="flex-grow-1 border-top"></div>

                    <div
                        id="apartments-summary-text"
                        class="small text-muted text-nowrap"
                    >
                        Prikazano 0 od 0 stanova
                    </div>

                    <div class="flex-grow-1 border-top"></div>

                </div>

            </div>

        </div>

    </div>
</section>