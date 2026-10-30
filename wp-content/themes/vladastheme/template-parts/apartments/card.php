<?php
$project = get_field('projekat');

            $floor = get_field('sprat');

            $area = get_field('kvadratura');

            $status_value = get_field('status');

            $gallery = get_field('galerija');
            $gallery_count = is_array($gallery) ? count($gallery) : 0;


            $types = get_the_terms(
                get_the_ID(),
                'tip_stana'
            );


            $type_name = '';

            if ($types && !is_wp_error($types)) {

                $type_name = $types[0]->name;

            }


            ?>



<div class="col-12 mb-4">

    <article class="apartment-card border rounded-4 overflow-hidden bg-white shadow-sm">

        <div class="row g-0 align-items-stretch">

            <!-- SLIKA -->
            <div class="col-lg-4 position-relative">

                <?php if (has_post_thumbnail()) : ?>

                    <a href="<?php the_permalink(); ?>" class="d-block h-100">

                        <?php
                        the_post_thumbnail(
                            'large',
                            [
                                'class' => 'img-fluid w-100 apartment-card-image'
                            ]
                        );
                        ?>

                        <?php if ($gallery_count > 0) : ?>

                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-pill">
                                    <i class="bi bi-images me-2"></i>
                                    <?php echo esc_html($gallery_count); ?>
                                    fotografija
                                </span>
                            </div>

                        <?php endif; ?>

                    </a>

                <?php endif; ?>


                <?php if ($status_value) : ?>

                    <?php
                    $status_class = '';

                    if ($status_value === 'slobodan') {
                        $status_class = 'bg-success';
                    } elseif ($status_value === 'rezervisan') {
                        $status_class = 'bg-warning text-dark';
                    } elseif ($status_value === 'prodat') {
                        $status_class = 'bg-danger';
                    }
                    ?>

                    <span class="position-absolute top-0 start-0 m-3 badge rounded-pill px-3 py-2 <?php echo esc_attr($status_class); ?>">
                        <?php echo esc_html(ucfirst($status_value)); ?>
                    </span>

                <?php endif; ?>

            </div>


            <!-- INFO -->
            <div class="col-lg-5">

                <div class="p-4 p-xl-5 h-100">

                    <?php if ($type_name) : ?>

                        <div class="text-uppercase text-muted small fw-semibold mb-2">
                            <?php echo esc_html($type_name); ?>
                        </div>

                    <?php endif; ?>


                    <h3 class="mb-4">

                        <a
                            href="<?php the_permalink(); ?>"
                            class="text-decoration-none text-dark"
                        >
                            <?php the_title(); ?>
                        </a>

                    </h3>


                    <div class="row g-4">

                        <?php if ($project) : ?>

                            <div class="col-6">

                                <div class="d-flex gap-3 align-items-start">

                                    <i class="bi bi-buildings fs-4 text-secondary"></i>

                                    <div>
                                        <div class="small text-muted">
                                            Projekat
                                        </div>

                                        <div class="fw-semibold">
                                            <?php
                                            echo esc_html(
                                                get_the_title($project)
                                            );
                                            ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>


                        <?php if ($floor !== '') : ?>

                            <div class="col-6">

                                <div class="d-flex gap-3 align-items-start">

                                    <i class="bi bi-bar-chart-steps fs-4 text-secondary"></i>

                                    <div>
                                        <div class="small text-muted">
                                            Sprat
                                        </div>

                                        <div class="fw-semibold">
                                            <?php echo esc_html($floor); ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>


                        <?php if ($area) : ?>

                            <div class="col-6">

                                <div class="d-flex gap-3 align-items-start">

                                    <i class="bi bi-arrows-fullscreen fs-4 text-secondary"></i>

                                    <div>
                                        <div class="small text-muted">
                                            Kvadratura
                                        </div>

                                        <div class="fw-semibold">
                                            <?php echo esc_html($area); ?> m²
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>


                        <?php if ($status_value) : ?>

                            <div class="col-6">

                                <div class="d-flex gap-3 align-items-start">

                                    <i class="bi bi-file-earmark-check fs-4 text-secondary"></i>

                                    <div>
                                        <div class="small text-muted">
                                            Status
                                        </div>

                                        <div class="fw-semibold">
                                            <?php echo esc_html(ucfirst($status_value)); ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <!-- CTA -->
            <div class="col-lg-3 border-start">

                <div class="p-4 h-100 d-flex flex-column justify-content-center align-items-center">

                    <a
                        href="<?php the_permalink(); ?>"
                        class="btn btn-dark py-3 btn-dark-blue"
                    >
                        Pogledaj stan
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

        </div>

    </article>

</div>
