<?php

$about_image   = get_field('about_values_image');
$about_image_2 = get_field('about_values_image_2');

/*
 * Ako ACF Image field vraća ARRAY
 */
if (is_array($about_image)) {
    $about_image_url = $about_image['url'];
    $about_image_alt = $about_image['alt'];
} else {
    $about_image_url = $about_image;
    $about_image_alt = 'Luxor Investments';
}

if (is_array($about_image_2)) {
    $about_image_2_url = $about_image_2['url'];
    $about_image_2_alt = $about_image_2['alt'];
} else {
    $about_image_2_url = $about_image_2;
    $about_image_2_alt = 'Luxor Investments';
}
?>

<section class="m-aboutValues">

    <div class="container">

        <div class="row align-items-center">

            <!-- =========================================
                 LEFT
            ========================================== -->

            <div class="col-lg-6">

                <div class="m-aboutValues__content" data-aos="fade-right"
     data-aos-duration="800"
     data-aos-offset="50"
     data-aos-anchor-placement="top-bottom"
     data-aos-mirror="true"
     data-aos-once="false">

                    <!-- SMALL TITLE -->
                    <p class="primary-text-color d-flex align-items-center fw-semibold mb-3 fst-italic text-nowrap"><span class="line-text me-3 dark-bg"></span> O  NAMA</p>



                    <!-- TITLE -->
                    <h2 class="m-aboutValues__title">
                        Pouzdan partner za sigurnu budućnost
                    </h2>


                    <!-- TEXT -->
                    <p class="m-aboutValues__text">
                        Luxor Investments je investitor sa jasnom vizijom –
                        da stvara savremene stambene objekte koji kombinuju
                        kvalitet, funkcionalnost i dugoročnu vrednost.
                        Svaki projekat razvijamo sa fokusom na potrebe
                        savremenog života, oslanjajući se na proverene
                        materijale, stručnost i pažljivo odabrane lokacije.
                    </p>


                    <!-- =================================
                         BENEFITS
                    ================================== -->

                    <div class="m-aboutValues__benefits">

                        <!-- ITEM -->
                        <div class="m-aboutValues__benefit">

                            <div class="m-aboutValues__benefitIcon">
                                <i class="bi bi-shield-check"></i>
                            </div>

                            <div>
                                <h4>Sigurnost</h4>

                                <p>
                                    Pouzdan investitor sa jasnim rokovima
                                    i transparentnim procesom.
                                </p>
                            </div>

                        </div>


                        <!-- ITEM -->
                        <div class="m-aboutValues__benefit">

                            <div class="m-aboutValues__benefitIcon">
                                <i class="bi bi-geo-alt"></i>
                            </div>

                            <div>
                                <h4>Atraktivne lokacije</h4>

                                <p>
                                    Stanovi na pažljivo odabranim
                                    lokacijama u Novom Sadu.
                                </p>
                            </div>

                        </div>


                        <!-- ITEM -->
                        <div class="m-aboutValues__benefit">

                            <div class="m-aboutValues__benefitIcon">
                                <i class="bi bi-house-check"></i>
                            </div>

                            <div>
                                <h4>Kvalitet</h4>

                                <p>
                                    Savremena gradnja i pažljivo
                                    odabrani materijali.
                                </p>
                            </div>

                        </div>


                        <!-- ITEM -->
                        <div class="m-aboutValues__benefit">

                            <div class="m-aboutValues__benefitIcon">
                                <i class="bi bi-people"></i>
                            </div>

                            <div>
                                <h4>Podrška kupcima</h4>

                                <p>
                                    Pratimo vas od prvog koraka
                                    do useljenja i dalje.
                                </p>
                            </div>

                        </div>

                    </div>


                    <!-- BUTTON -->
                    <a
                        href="<?php echo esc_url(home_url('/projekti-stranica/')); ?>"
                        class="m-aboutValues__button"
                    >
                        <span>Pogledajte naše projekte</span>

                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>


            <!-- =========================================
                 RIGHT / IMAGES
            ========================================== -->

            <div class="col-lg-6">

                <div class="m-aboutValues__images">

                    <?php if ($about_image_url) : ?>

                        <div class="m-aboutValues__imageMain">

                            <img
                                src="<?php echo esc_url($about_image_url); ?>"
                                alt="<?php echo esc_attr($about_image_alt); ?>"
                                data-aos="fade-down"
                                data-aos-easing="ease-in-sine"
                                data-aos-duration="800"
                                data-aos-offset="50"
                                data-aos-anchor-placement="top-bottom"
                                data-aos-mirror="true"
                                data-aos-once="false"
                            >

                        </div>

                    <?php endif; ?>


                    <?php if ($about_image_2_url) : ?>

                        <div class="m-aboutValues__imageSecondary">

                            <img
                                src="<?php echo esc_url($about_image_2_url); ?>"
                                alt="<?php echo esc_attr($about_image_2_alt); ?>"
                                data-aos="fade-up"
                                data-aos-easing="ease-in-sine"
                                data-aos-duration="800"
                                data-aos-offset="50"
                                data-aos-anchor-placement="top-bottom"
                                data-aos-mirror="true"
                                data-aos-once="false"
                            >

                        </div>

                    <?php endif; ?>

                </div>

            </div>

        </div>


        <!-- =============================================
             BOTTOM VALUES
        ============================================== -->

        <div class="m-aboutValues__values">

            <!-- VALUE -->
            <div class="m-aboutValues__value">

                <div class="m-aboutValues__valueIcon">
                    <i class="bi bi-gem"></i>
                </div>

                <h3>Kvalitet</h3>

                <p>U svakom detalju</p>

            </div>


            <!-- VALUE -->
            <div class="m-aboutValues__value">

                <div class="m-aboutValues__valueIcon">
                    <i class="bi bi-hand-thumbs-up"></i>
                </div>

                <h3>Pouzdanost</h3>

                <p>Od prvog koraka</p>

            </div>


            <!-- VALUE -->
            <div class="m-aboutValues__value">

                <div class="m-aboutValues__valueIcon">
                    <i class="bi bi-geo-alt"></i>
                </div>

                <h3>Funkcionalnost</h3>

                <p>Za savremen život</p>

            </div>


            <!-- VALUE -->
            <div class="m-aboutValues__value">

                <div class="m-aboutValues__valueIcon">
                    <i class="bi bi-tree"></i>
                </div>

                <h3>Dugoročna vrednost</h3>

                <p>Za sledeće generacije</p>

            </div>

        </div>

    </div>

</section>