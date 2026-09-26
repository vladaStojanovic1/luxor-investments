<?php
$offer_small_title = get_field('offer_small_title');
$offer_title = get_field('offer_title');
$offer_text = get_field('offer_text');


?>
<section id="services" class="m-offers" style="background-image: url('<?php echo get_template_directory_uri(); ?>/src/images/bg-offers.png');">
    <div class="container">
        <div class="row justify-content-center mb-4 mb-md-5">
            <div class="col-lg-7" data-aos="fade-down">
                <div class="text-center">
                    <p class="primary-text-color fw-semibold mb-3 fst-italic "> <?php echo $offer_small_title ?></p>
                    <h1 class="text-uppercase lh-1 mb-4"><?php echo $offer_title ?></h1>
                    <p><?php echo $offer_text ?></p>
                </div>
            </div>
        </div>
        <div class="row">
            <?php if(have_rows('offers')): ?>
            <?php while(have_rows('offers')): the_row(); ?>
            <div class="col-lg-3 mb-4" data-aos="zoom-in">
                <div class="m-offers__item">
                    <img class="mb-3" src="<?php echo get_sub_field('item_icon'); ?>" alt="">
                    <h4 class="text-uppercase mb-3"><?php echo get_sub_field('item_title') ?></h4>
                    <p class="fs-14 light-text m-0"><?php echo get_sub_field('item_text') ?></p>
                </div>
            </div>
            
            <?php endwhile; ?>
            <?php endif; ?>

        </div>
    </div>
</section>