<?php

$call_background = get_field('call_background');
$phone           = get_field('phone', 'option');
$call_title      = get_field('call_title');
$call_text       = get_field('call_text');
$call_button     = get_field('call_button');


// =====================================================
// BACKGROUND IMAGE
// Podržava i ACF URL i Image Array return format
// =====================================================

if (is_array($call_background)) {
    $call_background = $call_background['url'] ?? '';
}


// =====================================================
// BUTTON
// =====================================================

$call_button_url    = '';
$call_button_title  = '';
$call_button_target = '_self';

if (is_array($call_button)) {

    $call_button_url = $call_button['url'] ?? '';

    $call_button_title = $call_button['title'] ?? '';

    $call_button_target = !empty($call_button['target'])
        ? $call_button['target']
        : '_self';
}

?>

<section class="m-call position-relative overlay-4">

    <?php if ($call_background) : ?>
        <div
            class="overlay-1"
            style="background-image: url('<?php echo esc_url($call_background); ?>');">
        </div>
    <?php endif; ?>


    <div class="container">

        <div class="row justify-content-between z-1 position-relative">


            <!-- PHONE -->

            <div class="col-md-5" data-aos="fade-right">

                <div class="text-center">

                    <div class="d-flex justify-content-center mb-3">

                        <i class="bi bg-white primary-text-color bi-telephone-fill fs-2 icon-box"></i>

                    </div>

                    <h4 class="text-uppercase text-white mb-2 lh-1">
                        PRVI KORAK DO NOVOG DOMA
                    </h4>

                    <?php if ($phone) : ?>

                        <h1 class="lh-1">

                            <a
                                class="text-white text-decoration-underline"
                                href="tel:<?php echo esc_attr($phone); ?>">

                                <?php echo esc_html($phone); ?>

                            </a>

                        </h1>

                    <?php endif; ?>

                </div>

            </div>


            <!-- CONTENT -->

            <div class="col-md-6 mt-5 mt-md-0 text-center text-md-start">

                <div>

                    <?php if ($call_title) : ?>

                        <h1
                            class="text-uppercase text-white lh-1 mb-4"
                            data-aos="fade-left">

                            <?php echo esc_html($call_title); ?>

                        </h1>

                    <?php endif; ?>


                    <?php if ($call_text) : ?>

                        <div
                            class="text-white mb-4 mb-md-5"
                            data-aos="fade-left">

                            <?php echo wp_kses_post($call_text); ?>

                        </div>

                    <?php endif; ?>


                    <?php if ($call_button_url && $call_button_title) : ?>

                        <a
                            href="<?php echo esc_url($call_button_url); ?>"
                            target="<?php echo esc_attr($call_button_target); ?>"
                            class="a-btn -secondary primary-text-color"
                            data-aos="fade-up">

                            <?php echo esc_html($call_button_title); ?>

                        </a>

                    <?php endif; ?>

                </div>

            </div>

        </div>

    </div>

</section>