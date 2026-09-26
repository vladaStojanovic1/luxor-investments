<?php
$why_image = get_field('why_image');
$why_image2 = get_field('why_image_2');
$why_number_text = get_field('why_number_text');
$why_number = get_field('why_number');
$why_right_title = get_field('why_right_title');
$why_right_subtitle = get_field('why_right_small_title');
$why_right_text = get_field('why_right_text');
$why_item_1 = get_field('why_item_1');
$why_item_2 = get_field('why_item_2');
$why_item_3 = get_field('why_item_3');
$why_item_4 = get_field('why_item_4');
$why_item_5 = get_field('why_item_5');
$why_item_6 = get_field('why_item_6');
?>

<section class="m-aboutUs sections-spacing -why counter-section">
    <div id="scroll"></div>
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-7 order-2 order-lg-1 mt-5 mt-lg-0">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0" data-aos="fade-up"
                         data-aos-offset="100"
                         data-aos-easing="ease-in-sine">
                        <div class="m-aboutUs__image position-relative" style="background-image: url(<?php echo $why_image; ?>)">
                            <!-- <span class="corner bottom-left"></span> -->
                            <!-- <span class="corner top-right"></span> -->
                            <!-- <div class="overlay-2"></div> -->
                        </div>
                    </div>

                    <div class="col-lg-7" data-aos="fade-down"
                         data-aos-offset="100"
                         data-aos-easing="ease-in-sine">
                        <div class="m-aboutUs__image -second" style="background-image: url(<?php echo $why_image2; ?>)">
                            <div class="m-aboutUs__image--whiteBox">
                                <h1 class="text-yellow mb-2 counterOne"><?php echo $why_number; ?></h1>
                                <p class="mb-0"><?php echo $why_number_text; ?></p>
                            </div>
                            <!-- <div class="overlay-2"></div> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 order-1 order-lg-2 m-aboutUs__rightText" data-aos="fade-left"
                 data-aos-offset="100"
                 data-aos-easing="ease-in-sine">
                <div>
                    <p class="primary-text-color d-flex align-items-center fw-semibold mb-3 fst-italic text-nowrap"><span class="line-text me-3 dark-bg"></span> <?php echo $why_right_subtitle; ?></p>
                    <h1 class="text-uppercase lh-1 mb-4"><?php echo $why_right_title; ?></h1>
                    <p><?php echo $why_right_text; ?></p>

                    <div class="mt-4 mt-lg-5 d-flex justify-content-between border-light-lg pt-lg-5 column-gap-20">
                        <div>
                            <div class="d-flex align-items-center lh-1 mb-2 mb-md-3">
                                <i class="bi bi-check-circle-fill text-black fs-5 me-2"></i>
                                <p class="mb-0"><?php echo $why_item_1 ?></p>
                            </div>
                            <div class="d-flex align-items-center lh-1 mb-2 mb-md-3">
                                <i class="bi bi-check-circle-fill text-black fs-5 me-2"></i>
                                <p class="mb-0"><?php echo $why_item_2 ?></p>
                            </div>
                            <div class="d-flex align-items-center lh-1 mb-0">
                                <i class="bi bi-check-circle-fill text-black fs-5 me-2"></i>
                                <p class="mb-0"><?php echo $why_item_3 ?></p>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex align-items-center lh-1 mb-2 mb-md-3">
                                <i class="bi bi-check-circle-fill text-black fs-5 me-2"></i>
                                <p class="mb-0"><?php echo $why_item_4 ?></p>
                            </div>
                            <div class="d-flex align-items-center lh-1 mb-2 mb-md-3">
                                <i class="bi bi-check-circle-fill text-black fs-5 me-2"></i>
                                <p class="mb-0"><?php echo $why_item_5 ?></p>
                            </div>
                            <div class="d-flex align-items-center lh-1 mb-0">
                                <i class="bi bi-check-circle-fill text-black fs-5 me-2"></i>
                                <p class="mb-0"><?php echo $why_item_6 ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>



