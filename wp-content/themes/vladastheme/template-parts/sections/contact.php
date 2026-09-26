<?php
$phone = get_field('phone','options');
$email = get_field('email','options');
$address = get_field('address','options');
$contact_small_title = get_field('contact_small_title');
$contact_title = get_field('contact_title');
$contact_text = get_field('contact_text');
$contact_google_map = get_field('contact_google_map');

?>

<section class="m-contact sections-spacing">
    <div id="scroll"></div>
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5 col-lg-smaller" data-aos="fade-up"
                 data-aos-offset="100"
                 data-aos-easing="ease-in-sine">
                <p class="primary-text-color d-flex align-items-center fw-semibold mb-2 fst-italic text-nowrap"><span class="line-text me-3 dark-bg"></span> <?php echo $contact_small_title; ?></p>
                <h1 class="text-uppercase lh-1 mb-4"><?php echo $contact_title; ?></h1>
                <p><?php echo $contact_text; ?></p>

                <div class="m-contact__form">
                    <?php echo do_shortcode('[contact-form-7 id="4abfa8b" title="Contact form 1"]') ?>
                </div>
            </div>

            <div class="col-lg-6 mt-4 mt-lg-0" data-aos="fade-down"
                 data-aos-offset="100"
                 data-aos-easing="ease-in-sine">
                <div>
                    <?php echo html_entity_decode($contact_google_map); ?>
                </div>

                <div class="d-flex mt-4 mt-md-5 row">
                    <div class="col-md-6 d-flex align-items-start mt-4 mt-md-0">
                        <i class="bi bi-geo-alt-fill primary-bg text-white me-3 fs-3 yellow-bg m-contact__icon"></i>
                        <div>
                            <h4 class="text-uppercase lh-1 mb-2">Adresa</h4>
                            <a class="primary-text-color" target="_blank" href="<?php echo $address; ?>"><?php echo $address; ?></a>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-start mt-4 mt-md-0">
                        <i class="bi bi-telephone-fill primary-bg text-white me-3 fs-3 fs-3 m-contact__icon"></i>
                        <div>
                            <h4 class="text-uppercase lh-1 mb-2">Telefon</h4>
                            <a class="primary-text-color" href="tel:<?php echo $phone; ?>"><?php echo $phone; ?></a>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-start mt-4 mt-md-5">
                        <i class="bi bi-envelope-at-fill primary-bg text-white me-3 fs-3 fs-3 yellow-bg m-contact__icon"></i>
                        <div>
                            <h4 class="text-uppercase lh-1 mb-2">Email</h4>
                            <a class="primary-text-color" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>