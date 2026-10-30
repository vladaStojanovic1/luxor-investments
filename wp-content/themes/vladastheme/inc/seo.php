<?php
/** Extend the existing Yoast output. Never emit a competing canonical/schema graph. */
function luxor_project_id( $value ) { return $value instanceof WP_Post ? $value->ID : absint( $value ); }
function luxor_address_url( $address ) {
    return preg_match( '#^https?://#i', (string) $address ) ? $address : 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode( wp_strip_all_tags( (string) $address ) );
}
function luxor_apartment_seo_title( $id ) {
    $parts = array( get_the_title( $id ) );
    if ( function_exists( 'get_field' ) ) {
        $area = get_field( 'kvadratura', $id );
        if ( is_numeric( $area ) && $area > 0 ) { $parts[] = number_format_i18n( $area, floor( $area ) == $area ? 0 : 2 ) . ' m²'; }
        $project = luxor_project_id( get_field( 'projekat', $id ) );
        if ( $project && get_post_status( $project ) === 'publish' ) { $parts[] = get_the_title( $project ); }
    }
    return implode( ' — ', array_filter( $parts ) );
}
add_filter( 'wpseo_title', function ( $title ) {
    if ( is_front_page() && ! get_post_meta( get_queried_object_id(), '_yoast_wpseo_title', true ) ) {
        $settings = get_option( 'wpseo_titles', array() );
        if ( empty( $settings['title-home-wpseo'] ) || $settings['title-home-wpseo'] === '%%sitename%% %%page%% %%sep%% %%sitedesc%%' ) { return get_bloginfo( 'name' ) . ' — Izgradnja i prodaja stanova'; }
    }
    if ( ! is_singular( 'stanovi' ) || get_post_meta( get_queried_object_id(), '_yoast_wpseo_title', true ) ) { return $title; }
    return luxor_apartment_seo_title( get_queried_object_id() ) . ' — ' . get_bloginfo( 'name' );
} );
add_filter( 'wpseo_metadesc', function ( $description ) {
    if ( $description || ! is_singular() ) { return $description; }
    $id = get_queried_object_id(); $text = '';
    if ( function_exists( 'get_field' ) ) {
        foreach ( array( 'header_text', 'opis_projekta', 'about_us_right_text', 'contact_text' ) as $field ) {
            $value = get_field( $field, $id ); if ( is_string( $value ) && trim( wp_strip_all_tags( $value ) ) !== '' ) { $text = $value; break; }
        }
    }
    if ( ! $text ) { $text = get_post_field( 'post_excerpt', $id ) ?: get_post_field( 'post_content', $id ); }
    if ( is_singular( 'stanovi' ) ) { $text = luxor_apartment_seo_title( $id ) . ( $text ? '. ' . $text : '' ); }
    if ( ! $text ) { $text = get_the_title( $id ) . ' — ' . get_bloginfo( 'name' ); }
    return wp_html_excerpt( trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( strip_shortcodes( $text ) ) ) ), 155, '…' );
} );
function luxor_archive_landing( $type ) {
    foreach ( get_pages( array( 'post_status' => 'publish' ) ) as $page ) {
        $template = get_page_template_slug( $page );
        if ( $template === ( $type === 'projekti' ? 'template-parts/template-projekti.php' : 'template-parts/template-prodaja-stanova.php' ) ) { return get_permalink( $page ); }
    }
    return '';
}
// Existing public listing pages are the canonical landing pages; singles are unchanged.
add_action( 'template_redirect', function () {
    foreach ( array( 'projekti', 'stanovi' ) as $type ) {
        if ( is_post_type_archive( $type ) ) {
            $landing = luxor_archive_landing( $type );
            if ( $landing ) { wp_safe_redirect( $landing, 301 ); exit; }
        }
    }
} );
add_filter( 'wpseo_canonical', function ( $url ) {
    if ( ! is_page_template( 'template-parts/template-prodaja-stanova.php' ) ) { return $url; }
    if ( get_post_meta( get_queried_object_id(), '_yoast_wpseo_canonical', true ) ) { return $url; }
    $page = isset( $_GET['stanovi_strana'] ) && is_scalar( $_GET['stanovi_strana'] ) ? max( 1, absint( $_GET['stanovi_strana'] ) ) : 1;
    foreach ( array( 'tip_stana', 'sprat', 'projekat', 'status' ) as $key ) { if ( ! empty( $_GET[ $key ] ) ) { return get_permalink( get_queried_object_id() ); } }
    return $page > 1 ? add_query_arg( 'stanovi_strana', $page, get_permalink( get_queried_object_id() ) ) : $url;
} );
add_filter( 'wpseo_robots_array', function ( $robots ) {
    if ( is_page_template( 'template-parts/template-prodaja-stanova.php' ) ) {
        foreach ( array( 'tip_stana', 'sprat', 'projekat', 'status' ) as $key ) { if ( ! empty( $_GET[ $key ] ) ) { $robots['index'] = 'noindex'; break; } }
    }
    return $robots;
} );
// Keep Yoast's breadcrumb graph; make the apartment's project discoverable as its parent.
add_filter( 'wpseo_breadcrumb_links', function ( $links ) {
    if ( ! is_singular( 'stanovi' ) || ! function_exists( 'get_field' ) ) { return $links; }
    $project = luxor_project_id( get_field( 'projekat', get_queried_object_id() ) );
    if ( $project && get_post_status( $project ) === 'publish' ) {
        foreach ( $links as $link ) { if ( isset( $link['id'] ) && (int) $link['id'] === $project ) { return $links; } }
        array_splice( $links, max( 0, count( $links ) - 1 ), 0, array( array( 'id' => $project, 'url' => get_permalink( $project ), 'text' => get_the_title( $project ) ) ) );
    }
    return $links;
} );

// Yoast already represents this site as a company; reuse the existing ACF logo
// only when no explicit Yoast logo has been selected. No competing schema block.
add_filter( 'wpseo_schema_company_logo_id', function ( $id ) {
    if ( $id || ! function_exists( 'get_field' ) ) { return $id; }
    $image = get_field( 'site_logo', 'options' );
    if ( is_array( $image ) ) { $image = isset( $image['ID'] ) ? $image['ID'] : 0; }
    $attachment = is_numeric( $image ) ? (int) $image : ( is_string( $image ) ? attachment_url_to_postid( $image ) : 0 );
    return $attachment && wp_attachment_is_image( $attachment ) ? $attachment : $id;
} );
add_filter( 'wpseo_schema_organization', function ( $data ) {
    if ( ! function_exists( 'get_field' ) ) { return $data; }
    $phone = get_field( 'phone', 'options' ); $email = get_field( 'email', 'options' );
    if ( $phone && empty( $data['telephone'] ) ) { $data['telephone'] = preg_replace( '/[^0-9+]/', '', $phone ); }
    if ( is_email( $email ) && empty( $data['email'] ) ) { $data['email'] = sanitize_email( $email ); }
    $profiles = isset( $data['sameAs'] ) ? (array) $data['sameAs'] : array();
    foreach ( array( 'instagram_icon_url', 'fb_icon_url' ) as $key ) {
        $value = get_field( $key, 'options' ); if ( is_string( $value ) && preg_match( '#^https?://#i', $value ) ) { $profiles[] = esc_url_raw( $value ); }
    }
    if ( $profiles ) { $data['sameAs'] = array_values( array_unique( $profiles ) ); }
    return $data;
} );
// Preload exactly the same responsive image selected by the hero picture.
add_action( 'wp_head', function () {
    if ( ! function_exists( 'get_field' ) || ! is_page_template( array( 'template-parts/template-pocetna.php', 'template-parts/template-o-investitoru.php', 'template-parts/template-kontakt.php', 'template-parts/template-projekti.php', 'template-parts/template-prodaja-stanova.php' ) ) ) { return; }
    $image = get_field( 'header_image', get_queried_object_id() );
    $data = luxor_optimized_media( luxor_media_id( $image ) );
    $home = is_front_page() || is_page_template( 'template-parts/template-pocetna.php' );
    $url = luxor_image_url( $image, 1600 );
    if ( $url ) {
        echo '<link rel="preload" as="image" fetchpriority="high" href="' . esc_url( $url ) . '"';
        if ( $home && $data ) { echo ' type="image/webp" imagesrcset="' . esc_attr( $data['srcset'] ) . '" imagesizes="100vw"'; }
        echo '>' . "\n";
    }
}, 2 );

/** Serbian local business number -> international WhatsApp path; explicit saved URL wins. */
function luxor_whatsapp_url( $phone ) {
    $saved = function_exists( 'get_field' ) ? get_field( 'whatsapp_icon_url', 'options' ) : '';
    if ( is_string( $saved ) && preg_match( '#^https?://#i', $saved ) ) { return $saved; }
    $digits = preg_replace( '/[^0-9]/', '', (string) $phone );
    if ( substr( $digits, 0, 2 ) === '00' ) { $digits = substr( $digits, 2 ); }
    elseif ( preg_match( '/^0[1-9][0-9]{7,8}$/D', $digits ) ) { $digits = '381' . substr( $digits, 1 ); }
    return $digits ? 'https://wa.me/' . $digits : '';
}

/** Public content is Serbian Latin; keep admin language and future WPML translations intact. */
function luxor_serbian_frontend() {
    if ( is_admin() || ( isset( $GLOBALS['pagenow'] ) && $GLOBALS['pagenow'] === 'wp-login.php' ) ) { return false; }
    $language = apply_filters( 'wpml_current_language', null );
    if ( $language ) { return in_array( $language, array( 'sr', 'sr-latn', 'sr_Latn' ), true ); }
    return in_array( get_locale(), array( 'en_US', 'sr_RS', 'sr_RS_latin', 'sr_Latn_RS' ), true );
}
add_filter( 'language_attributes', function ( $attributes ) {
    return luxor_serbian_frontend() ? preg_replace( '/((?:xml:)?lang)="[^"]*"/', '$1="sr-Latn-RS"', $attributes ) : $attributes;
} );
add_filter( 'wpseo_locale', function ( $locale ) { return luxor_serbian_frontend() ? 'sr_RS' : $locale; } );
function luxor_schema_language( $data ) {
    if ( ! luxor_serbian_frontend() ) { return $data; }
    foreach ( $data as $key => $value ) {
        if ( $key === 'inLanguage' ) { $data[ $key ] = 'sr-Latn-RS'; }
        elseif ( is_array( $value ) ) { $data[ $key ] = luxor_schema_language( $value ); }
    }
    return $data;
}
add_filter( 'wpseo_schema_graph', 'luxor_schema_language' );
