<?php
$about_us_image = get_field('about_us_image');
$about_us_years = get_field('about_us_years');
$about_us_text = get_field('about_us_years_text');
$about_us_right_title = get_field('about_us_right_title');
$about_us_right_text = get_field('about_us_right_text');
$about_us_item_1_title = get_field('about_us_item_1_title');
$about_us_item_1_text = get_field('about_us_item_1_text');
$about_us_item_2_title = get_field('about_us_item_2_title');
$about_us_item_2_text = get_field('about_us_item_2_text');
$about_us_btn = get_field('about_us_btn');
?>

<!--<button id="button">Get my plan</button>-->

<section id="about-us" class="m-aboutUs counter-section">
    <div id="scroll"></div>
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6 col-lg-6 order-2 order-lg-1 mt-5 mt-lg-0">
                <div data-aos="fade-right"
                     data-aos-offset="100"
                     data-aos-easing="ease-in-sine">
                    <div class="m-aboutUs__image -bigger" style="background-image: url(<?php echo $about_us_image; ?>)">
                        <span class="corner bottom-left"></span>
                        <div class="m-aboutUs__image--whiteBox">
                            <h1 class="primary-text-color mb-2 counterOne"><?php echo $about_us_years ?></h1>
                            <p class="mb-0"><?php echo $about_us_text ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-lg-smaller order-1 order-lg-2" data-aos="fade-left"
                 data-aos-offset="100"
                 data-aos-easing="ease-in-sine">
                <div>
                    <p class="primary-text-color d-flex align-items-center fw-semibold mb-3 fst-italic text-nowrap"><span class="line-text me-3 dark-bg"></span> O nama</p>
                    <h1 class="text-uppercase lh-1 mb-4"><?php echo $about_us_right_title; ?></h1>
                    <p><?php echo $about_us_right_text; ?></p>

                    <div class="row d-flex mt-5 m-aboutUs__iconsBox flex-column flex-md-row">
                        <div class="col-md-6 d-flex border-right pe-md-4 pt-md-4 mb-3 mb-md-0">
                            <!-- <i class="bi bi-person-gear fs-2 icon-box me-3"></i> -->
                            <!-- <i class="bi bi-crosshair2 fs-2 icon-box me-3 text-white"></i> -->
                            <i class="bi bi-bullseye fs-2 icon-box me-3 text-white"></i>
                            <div>
                                <h3 class="text-uppercase mb-2"><?php echo $about_us_item_1_title ?></h3>
                                <p><?php echo $about_us_item_1_text ?></p>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex ps-md-4 pt-md-4">
                            <!-- <i class="bi bi-crosshair2 fs-2 icon-box me-3"></i> -->
                            <i class="bi bi-crosshair2 fs-2 icon-box me-3 text-white"></i>
                            <div>
                                <h3 class="text-uppercase mb-2"><?php echo $about_us_item_2_title ?></h3>
                                <p><?php echo $about_us_item_2_text ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 mt-md-5 text-center">
                        <a href="<?php echo $about_us_btn['url'] ?>" class="a-btn -primary p-smaller"><?php echo $about_us_btn['title'] ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>



