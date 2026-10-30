<?php
/** CLI-only integration checks. No posts/options/products are created or changed. */
if ( PHP_SAPI !== 'cli' ) { exit; }
require dirname( __DIR__, 4 ) . '/wp-load.php';
$count = 0;
function luxor_check( $ok, $label ) { global $count; if ( ! $ok ) { throw new RuntimeException( 'FAIL: ' . $label ); } ++$count; echo 'PASS: ' . $label . PHP_EOL; }
$before = array( wp_count_posts( 'stanovi' ), wp_count_posts( 'projekti' ), get_option( 'wpseo_titles' ), get_option( 'active_plugins' ) );
luxor_check( current_theme_supports( 'title-tag' ), 'Theme title-tag support' );
$normal = luxor_apartments_result();
$expected = new WP_Query( array( 'post_type' => 'stanovi', 'post_status' => 'publish', 'posts_per_page' => 12 ) );
luxor_check( $normal['count'] === (int) $expected->found_posts, 'Only current published apartments in results' );
luxor_check( substr_count( $normal['html'], '<article class="apartment-card ' ) === $normal['shown'], 'One existing card per apartment' );
foreach ( array( null, 'bad', array( 'sprat' => '2', 'status' => 'slobodan' ), array( 'sprat' => array( array( 'bad' ), true, '-1', '0', '2' ), 'projekat' => array( 'bad', '0', '-1' ), 'status' => array( 'wrong', array(), 'prodat' ) ) ) as $input ) {
    $filters = luxor_apartment_filters( $input );
    luxor_check( count( $filters ) === 4 && is_array( $filters['status'] ), 'Malformed input safely normalized' );
}
$f = luxor_apartment_filters( array( 'sprat' => array( '-1', '0', '2' ), 'status' => array( 'slobodan', 'wrong' ) ) );
luxor_check( $f['sprat'] === array( -1, 0, 2 ) && $f['status'] === array( 'slobodan' ), 'Floors include zero and negative values; status allowlist' );
foreach ( get_posts( array( 'post_type' => 'projekti', 'numberposts' => -1 ) ) as $project ) {
    $result = luxor_apartments_result( array( 'projekat' => array( $project->ID ) ) );
    $query = new WP_Query( array( 'post_type' => 'stanovi', 'post_status' => 'publish', 'meta_key' => 'projekat', 'meta_value' => $project->ID, 'posts_per_page' => 1 ) );
    luxor_check( $result['count'] === (int) $query->found_posts, 'Project filter ' . $project->ID );
}
$paginate = function ( $query ) { if ( $query->get( 'post_type' ) === 'stanovi' && $query->get( 'posts_per_page' ) === 12 ) { $query->set( 'posts_per_page', 2 ); } };
add_action( 'pre_get_posts', $paginate );
$first = luxor_apartments_result(); $second = luxor_apartments_result( array(), 2 );
luxor_check( $first['shown'] <= 2 && $first['pages'] >= 1, 'Bounded page size' );
if ( $first['pages'] > 1 ) {
    luxor_check( strpos( $first['pagination'], 'stanovi_strana=2' ) !== false && strpos( $first['pagination'], 'href=' ) !== false, 'Pagination has real crawlable links' );
    luxor_check( $first['html'] !== $second['html'] && $second['page'] === 2, 'Next page has independent results' );
}
$excessive = luxor_apartments_result( array(), 100000 ); luxor_check( strlen( $excessive['pagination'] ) < 3000, 'Extreme page cannot produce unbounded pagination' );
remove_action( 'pre_get_posts', $paginate );
luxor_check( strpos( luxor_address_url( 'Test adresa' ), 'maps/search' ) !== false, 'Text addresses become map search URLs' );
luxor_check( luxor_address_url( 'https://example.test/map' ) === 'https://example.test/map', 'Existing map URLs preserved' );
$apartment = get_posts( array( 'post_type' => 'stanovi', 'numberposts' => 1 ) );
if ( $apartment ) {
    $id = $apartment[0]->ID; $GLOBALS['wp_query'] = new WP_Query( array( 'p' => $id, 'post_type' => 'stanovi' ) );
    luxor_check( strpos( luxor_apartment_seo_title( $id ), get_the_title( $id ) ) !== false, 'Apartment title uses real record' );
    $custom = function ( $value, $object_id, $key ) use ( $id ) { return $object_id === $id && $key === '_yoast_wpseo_title' ? array( 'Custom editor title' ) : $value; };
    add_filter( 'get_post_metadata', $custom, 10, 3 );
    luxor_check( apply_filters( 'wpseo_title', 'Existing editor title' ) === 'Existing editor title', 'Custom Yoast title is retained' );
    remove_filter( 'get_post_metadata', $custom, 10 );
}
luxor_check( apply_filters( 'wpseo_metadesc', 'Existing description' ) === 'Existing description', 'Custom Yoast description is retained' );
$root = get_template_directory();
luxor_check( strpos( file_get_contents( $root . '/src/styles/scss/style.scss' ), 'Theme Name: vladastheme' ) !== false, 'Theme metadata survives build' );
luxor_check( strpos( file_get_contents( $root . '/template-parts/post/content-stanovi.php' ), 'get_header()' ) === false && strpos( file_get_contents( $root . '/template-parts/post/content-stanovi.php' ), 'get_footer()' ) === false, 'Apartment content does not own the document shell' );
luxor_check( wp_json_encode( $before ) === wp_json_encode( array( wp_count_posts( 'stanovi' ), wp_count_posts( 'projekti' ), get_option( 'wpseo_titles' ), get_option( 'active_plugins' ) ) ), 'Published content, SEO options and active plugins unchanged' );
$english = function () { return 'en'; }; add_filter( 'wpml_current_language', $english );
luxor_check( ! luxor_serbian_frontend(), 'Future English WPML pages keep their language' ); remove_filter( 'wpml_current_language', $english );
luxor_check( strpos( apply_filters( 'language_attributes', 'lang="en-US"' ), 'sr-Latn-RS' ) !== false, 'Serbian Latin public HTML language' );
luxor_check( apply_filters( 'wpseo_schema_company_logo_id', 123 ) === 123, 'Explicit Yoast logo is retained' );
luxor_check( apply_filters( 'wpseo_schema_company_logo_id', 0 ) > 0, 'Existing ACF logo provides missing company logo' );
$org = apply_filters( 'wpseo_schema_organization', array( 'telephone' => 'existing' ) );
luxor_check( $org['telephone'] === 'existing', 'Existing Organization contact data is retained' );
$page = get_page_by_path( 'prodaja-stanova' );
if ( $page ) {
    $GLOBALS['wp_query'] = new WP_Query( array( 'page_id' => $page->ID ) );
    $original_get = $_GET;
    $_GET['projekat'] = array( '311' ); $robots = apply_filters( 'wpseo_robots_array', array( 'index' => 'index' ) );
    luxor_check( $robots['index'] === 'noindex', 'Filter variants do not bloat the index' );
    $_GET = array( 'stanovi_strana' => '2' );
    luxor_check( strpos( apply_filters( 'wpseo_canonical', get_permalink( $page ) ), 'stanovi_strana=2' ) !== false, 'Unfiltered pagination keeps its own canonical' );
    $_GET = $original_get;
}
luxor_check( luxor_whatsapp_url( '062/371-371' ) === 'https://wa.me/38162371371', 'WhatsApp normalizes Serbian local number' );
if ( function_exists( 'luxor_optimized_media' ) ) {
    $image = get_field( 'header_image', (int) get_option( 'page_on_front' ) ); $image_id = luxor_media_id( $image );
    $meta_before = wp_get_attachment_metadata( $image_id ); $optimized = luxor_optimized_media( $image_id );
    luxor_check( $optimized && count( $optimized['variants'] ) > 1, 'Responsive variants available for current hero' );
    luxor_check( strpos( luxor_image_url( $image, 640 ), '/src/images/optimized-media/' ) !== false, 'Optimized asset uses portable theme URL' );
    $html = wp_get_attachment_image( $image_id, 'large', false, array( 'alt' => 'Preserved ALT', 'sizes' => '100vw' ) );
    luxor_check( strpos( $html, '<picture' ) !== false && strpos( $html, 'type="image/webp"' ) !== false && strpos( $html, 'Preserved ALT' ) !== false, 'Picture keeps ALT and WebP source' );
    luxor_check( strpos( $html, wp_get_attachment_image_url( $image_id, 'large' ) ) !== false, 'Original image remains as browser fallback' );
    luxor_check( luxor_optimized_media( 999999999 ) === false, 'Unknown/new media safely use native fallback' );
    luxor_check( $meta_before === wp_get_attachment_metadata( $image_id ), 'Original attachment metadata unchanged' );
}
echo $count . ' checks passed; no database writes performed by tests.' . PHP_EOL;
