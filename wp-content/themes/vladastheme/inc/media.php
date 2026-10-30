<?php
/** Portable, pre-generated image copies. Originals and media-library metadata stay unchanged. */
function luxor_media_id( $image ) {
    if ( $image instanceof WP_Post ) { return $image->ID; }
    if ( is_array( $image ) ) { $image = $image['ID'] ?? $image['id'] ?? $image['url'] ?? ''; }
    if ( is_numeric( $image ) ) { return absint( $image ); }
    return is_string( $image ) && preg_match( '#^https?://#i', $image ) ? attachment_url_to_postid( $image ) : 0;
}
function luxor_optimized_media( $id ) {
    static $manifest, $cache = array();
    $id = absint( $id ); if ( ! $id ) { return false; }
    if ( array_key_exists( $id, $cache ) ) { return $cache[ $id ]; }
    if ( $manifest === null ) {
        $path = get_template_directory() . '/src/images/optimized-media/manifest.json';
        $manifest = is_file( $path ) ? json_decode( file_get_contents( $path ), true ) : array();
        if ( ! is_array( $manifest ) ) { $manifest = array(); }
    }
    $relative = get_post_meta( $id, '_wp_attached_file', true ); $original = get_attached_file( $id );
    if ( ! is_string( $relative ) || ! isset( $manifest[ $relative ] ) || ! is_file( $original ) || filesize( $original ) !== $manifest[ $relative ]['original_bytes'] ) { return $cache[ $id ] = false; }
    $data = $manifest[ $relative ]; $variants = array();
    foreach ( $data['variants'] as $variant ) {
        if ( ! is_file( get_template_directory() . '/src/images/optimized-media/' . $variant['file'] ) ) { return $cache[ $id ] = false; }
        $variant['url'] = get_template_directory_uri() . '/src/images/optimized-media/' . $variant['file']; $variants[] = $variant;
    }
    $data['variants'] = $variants;
    $data['srcset'] = implode( ', ', array_map( function ( $v ) { return $v['url'] . ' ' . $v['width'] . 'w'; }, $variants ) );
    return $cache[ $id ] = $variants ? $data : false;
}
function luxor_image_url( $image, $width = 1600 ) {
    $id = luxor_media_id( $image ); $data = luxor_optimized_media( $id );
    if ( $data ) { foreach ( $data['variants'] as $variant ) { if ( $variant['width'] >= $width ) { return $variant['url']; } } return end( $data['variants'] )['url']; }
    if ( $id ) { return wp_get_attachment_image_url( $id, 'full' ); }
    if ( is_array( $image ) ) { return $image['url'] ?? ''; }
    return is_string( $image ) ? $image : '';
}
function luxor_picture( $image, $size = 'large', $attrs = array() ) {
    $id = luxor_media_id( $image );
    if ( $id ) { echo wp_get_attachment_image( $id, $size, false, $attrs ); return; }
    $url = luxor_image_url( $image );
    if ( $url ) { echo '<img src="' . esc_url( $url ) . '" alt="' . esc_attr( $attrs['alt'] ?? '' ) . '" loading="' . esc_attr( $attrs['loading'] ?? 'lazy' ) . '" decoding="async">'; }
}
add_filter( 'wp_get_attachment_image', function ( $html, $id, $size, $icon, $attr ) {
    if ( is_admin() || $icon || strpos( $html, '<picture' ) !== false ) { return $html; }
    $data = luxor_optimized_media( $id ); if ( ! $data ) { return $html; }
    $sizes = isset( $attr['sizes'] ) ? $attr['sizes'] : '(max-width: 1024px) 100vw, 1024px';
    // Keep the exact native image/ALT/fallback JPEG/PNG and original lightbox links.
    return '<picture class="luxor-picture"><source type="image/webp" srcset="' . esc_attr( $data['srcset'] ) . '" sizes="' . esc_attr( $sizes ) . '">' . $html . '</picture>';
}, 10, 5 );
add_filter( 'wp_get_attachment_image_attributes', function ( $attr ) {
    $class = $attr['class'] ?? '';
    if ( preg_match( '/featured-apartment__img|apartment-card-image/', $class ) ) { $attr['sizes'] = '(max-width: 767px) 90vw, (max-width: 1199px) 45vw, 420px'; }
    return $attr;
} );
