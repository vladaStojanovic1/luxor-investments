<?php
/** One versioned copy of each existing library; identical behavior in debug/production. */
add_action( 'wp_enqueue_scripts', function () {
    $root = get_template_directory(); $uri = get_template_directory_uri();
    $style = function ( $handle, $path, $deps = array() ) use ( $root, $uri ) {
        if ( is_file( $root . $path ) ) { wp_enqueue_style( $handle, $uri . $path, $deps, filemtime( $root . $path ) ); }
    };
    $script = function ( $handle, $path, $deps = array() ) use ( $root, $uri ) {
        if ( is_file( $root . $path ) ) { wp_enqueue_script( $handle, $uri . $path, $deps, filemtime( $root . $path ), true ); return true; }
        return false;
    };
    $slider = is_front_page() || is_page_template( 'template-parts/template-pocetna.php' ) || is_singular( array( 'projekti', 'stanovi' ) );
    $animated = false;
    if ( is_singular() ) {
        $content = (string) get_post_field( 'post_content', get_queried_object_id() );
        if ( function_exists( 'get_field' ) ) {
            foreach ( array( 'tcontent', 'content', 'about_us_right_text', 'header_text' ) as $field ) { $content .= wp_json_encode( get_field( $field, get_queried_object_id() ) ); }
        }
        $animated = strpos( $content, 'bar-fill' ) !== false;
    }
    $fixed_page = is_page_template( array( 'template-parts/template-pocetna.php', 'template-parts/template-o-investitoru.php', 'template-parts/template-kontakt.php', 'template-parts/template-projekti.php', 'template-parts/template-prodaja-stanova.php' ) );
    $lightbox = is_singular( array( 'projekti', 'stanovi' ) ) || ( is_singular() && ! $fixed_page );
    $style( 'bootstrap-css', '/src/styles/css/vendor/bootstrap.min.css' );
    $style( 'bootstrap-icons-css', '/src/styles/css/vendor/bootstrap-icons.css' );
    $style( 'vladastheme-animate', '/src/styles/css/vendor/aos.css' );
    if ( $slider ) { $style( 'vladastheme-swiper-style', '/src/styles/css/vendor/swiper.min.css' ); $script( 'vladastheme-swiper', '/src/scripts/src/swiper.js' ); }
    // Flexible ACF content may use lightboxes on any singular page.
    if ( $lightbox ) { $style( 'vladastheme-fancybox', '/src/styles/css/vendor/fancybox.css' ); $script( 'fancybox-js', '/src/scripts/src/fancybox.umd.js' ); }
    $style( 'vladastheme-style', '/style.css', array( 'bootstrap-css', 'bootstrap-icons-css' ) );
    $script( 'bootstrap-js', '/src/scripts/src/bootstrap.bundle.min.js' );
    $script( 'aos-js', '/src/scripts/src/aos.js' );
    $dependencies = array( 'jquery', 'bootstrap-js', 'aos-js' );
    if ( $slider ) { $dependencies[] = 'vladastheme-swiper'; }
    if ( $animated && $script( 'gsap-js', '/src/scripts/src/gsap.min.js' ) ) {
        $script( 'gsap-st', '/src/scripts/src/ScrollTrigger.min.js', array( 'gsap-js' ) );
        $dependencies[] = 'gsap-st';
    }
    if ( is_singular( 'stanovi' ) ) { $script( 'single-apartment-js', '/src/scripts/src/single-apartment.js', array( 'vladastheme-swiper', 'fancybox-js' ) ); }
    if ( is_singular( 'projekti' ) ) { $script( 'single-project-js', '/src/scripts/src/single-project.js', array( 'vladastheme-swiper', 'fancybox-js' ) ); }
    if ( is_page_template( 'template-parts/template-projekti.php' ) ) { $script( 'projekti-js', '/src/scripts/src/projekti.js' ); }
    if ( is_page_template( 'template-parts/template-prodaja-stanova.php' ) ) {
        $script( 'luxor-apartments-filter', '/src/scripts/src/filters.js' );
        wp_localize_script( 'luxor-apartments-filter', 'stanoviAjax', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'stanovi_filter_nonce' ) ) );
    }
    $main = defined( 'WP_DEBUG' ) && WP_DEBUG ? '/src/scripts/src/script.js' : '/bundles/scripts/scripts.min.js';
    if ( ! is_file( $root . $main ) ) { $main = '/src/scripts/src/script.js'; }
    $script( 'vladastheme-script', $main, $dependencies );
    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
} );

add_action( 'wp_head', function () {
    foreach ( array( 'Regular', 'Bold' ) as $weight ) {
        $path = '/src/fonts/OpenSans/OpenSans-' . $weight . '.woff2';
        if ( is_file( get_template_directory() . $path ) ) { echo '<link rel="preload" as="font" type="font/woff2" crossorigin href="' . esc_url( get_template_directory_uri() . $path ) . '">' . "\n"; }
    }
}, 2 );
