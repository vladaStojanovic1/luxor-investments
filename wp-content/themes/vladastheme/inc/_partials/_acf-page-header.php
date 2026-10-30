<?php
/**
 * Header for pages
 * Custom header image functionality
 * @package WordPress
 */

/**
 * @return mixed|string in case there is a image array in field, returns random image from it, otherwise returns random.jpg from images
 *
 */
function randomHeaderImage() {

    $images = array();

    if( have_rows('imgs', 'options') ):
        while( have_rows('imgs', 'options')): the_row();
            $image = get_sub_field('img');

            array_push($images, $image);
        endwhile;
        $random_counter = rand(0, count($images)-1);

    endif;
    if( !empty($images) ) return $images[$random_counter];
    else return get_template_directory_uri() . '/src/images/random.jpg';
}

/**
 * Custom header for home page
 * Change name of images that show up by default if nothing is selected (from random.jpg)
 */
function headerHomePage() {
    $header_image = get_field('header_image');
    $header_image2 = get_field('header_image_2');
    $header_title = get_field('header_title');
    $header_smallTitle = get_field('header_small_title');
    $header_text = get_field('header_text');
    $header_button_1 = get_field('header_button_1');
    $header_button_2 = get_field('header_button_2');
   // $image = get_ftheme_first([$header_image, get_the_post_thumbnail_url(), randomHeaderImage()]);
    //$title = get_ftheme_first([$header_title, get_the_title(), get_the_archive_title()]);
    ?>

   <header class="m-headerHome">
       <div class="hero-swiper">
           <!-- Fixed Text -->
           <div class="container">
               <div class="row">
                   <div class="col-md-5" >
                       <div class="m-headerHome__fixedText">
                           <p class="text-white d-flex align-items-center fw-semibold mb-3 fst-italic text-nowrap"><span class="line-text me-3 bg-white"></span> <?php echo $header_smallTitle; ?></p>
                           <h1 class="m-headerHome__fixedText--bigTitle text-uppercase text-white mb-4"><?php echo esc_html( $header_title ?: get_the_title() ); ?></h1>
                           <p class="text-white"><?php echo wp_kses_post( $header_text ); ?></p>
                            <div class="mt-5 d-flex align-items-center">
                                <a class="a-btn -primary me-3" href="<?php echo $header_button_1['url']; ?>"><?php echo $header_button_1['title']; ?></a>
                                <a class="a-btn -secondary" href="<?php echo $header_button_2['url']; ?>"><?php echo $header_button_2['title']; ?></a>
                            </div>
                       </div>
                   </div>
               </div>
           </div>

           <!-- Slides -->
           <div class="swiper-wrapper">
               <div class="swiper-slide"><?php luxor_picture( $header_image, 'full', array( 'class' => 'luxor-hero-image', 'alt' => '', 'sizes' => '100vw', 'loading' => 'eager', 'fetchpriority' => 'high' ) ); ?></div>
               <div class="swiper-slide"><?php luxor_picture( $header_image2, 'full', array( 'class' => 'luxor-hero-image', 'alt' => '', 'sizes' => '100vw', 'loading' => 'lazy', 'fetchpriority' => 'low' ) ); ?></div>
           </div>
       </div>



       <a href="#scroll" class="scroll-btn" aria-label="Pređi na sadržaj">
           <div class="chevron"></div>
           <div class="chevron"></div>
           <div class="chevron"></div>
       </a>
   </header>
    <?php
}

/**
 * Custom header for all other pages
 * Change name of images that show up by default if nothing is selected (from random.jpg)
 */
function headerPage() {
    $header_image = get_field('header_image');
    $header_title = get_field('header_title');
    $header_text = get_field('header_text');
//    $image = get_ftheme_first([$header_image, get_the_post_thumbnail_url(), randomHeaderImage()]);
//    $title = get_ftheme_first([$header_title, get_the_title(), get_the_archive_title()]);

    ?>

    <header class="m-headerHome -otherPages position-relative sections-spacing overlay-3" style="background-image: url(<?php echo esc_url( luxor_image_url( $header_image, 1600 ) ); ?>)">

        <div class="container">
            <div class="row">
                <div class="col-md-7" >
                    <div class="m-headerHome__fixedText">
                        <?php if ( function_exists('yoast_breadcrumb') ) {
                            yoast_breadcrumb( '<p id="breadcrumbs" class="fw-semibold mt-4">','</p>' );
                        }
                        ?>
                        <h1 class="text-uppercase text-white"><?php echo esc_html( $header_title ?: get_the_title() ); ?></h1>
                            <p class="text-white mt-3"><?php echo wp_kses_post( $header_text ); ?></p>
                        <!-- <span class="line-text-2 mt-4"></span> -->
                    </div>
                </div>
            </div>
        </div>

        <a href="#scroll" class="scroll-btn" aria-label="Pređi na sadržaj">
            <div class="chevron"></div>
            <div class="chevron"></div>
            <div class="chevron"></div>
        </a>
    </header>
    <?php
}
