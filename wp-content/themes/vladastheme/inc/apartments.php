<?php
/** Same public query and card markup for initial HTML and AJAX filtering. */
function luxor_apartment_filters( $source ) {
    $result = array( 'tip_stana' => array(), 'sprat' => array(), 'projekat' => array(), 'status' => array() );
    if ( ! is_array( $source ) ) { return $result; }
    foreach ( $result as $key => $empty ) {
        if ( ! isset( $source[ $key ] ) || ! is_array( $source[ $key ] ) ) { continue; }
        foreach ( array_slice( $source[ $key ], 0, 50 ) as $value ) {
            if ( ! is_scalar( $value ) || is_bool( $value ) ) { continue; }
            if ( $key === 'sprat' || $key === 'projekat' ) {
                if ( ! preg_match( '/^-?\d{1,9}$/D', (string) $value ) ) { continue; }
                $clean = (int) $value; if ( $key === 'projekat' && $clean <= 0 ) { continue; }
            } else {
                $clean = sanitize_text_field( wp_unslash( $value ) );
                if ( strlen( $clean ) > 100 ) { continue; }
                if ( $key === 'status' && ! in_array( $clean, array( 'slobodan', 'rezervisan', 'prodat' ), true ) ) { continue; }
            }
            $result[ $key ][] = $clean;
        }
        $result[ $key ] = array_values( array_unique( $result[ $key ] ) );
    }
    return $result;
}
function luxor_apartments_result( $source = array(), $page = 1 ) {
    $filters = luxor_apartment_filters( $source ); $page = min( 100000, max( 1, (int) $page ) );
    $args = array( 'post_type' => 'stanovi', 'post_status' => 'publish', 'posts_per_page' => 12, 'paged' => $page, 'orderby' => array( 'title' => 'ASC', 'ID' => 'ASC' ) );
    if ( $filters['tip_stana'] ) { $args['tax_query'] = array( array( 'taxonomy' => 'tip_stana', 'field' => 'slug', 'terms' => $filters['tip_stana'] ) ); }
    $meta = array( 'relation' => 'AND' );
    foreach ( array( 'sprat', 'projekat', 'status' ) as $key ) {
        if ( $filters[ $key ] ) { $clause = array( 'key' => $key, 'value' => $filters[ $key ], 'compare' => 'IN' ); if ( $key !== 'status' ) { $clause['type'] = 'NUMERIC'; } $meta[] = $clause; }
    }
    if ( count( $meta ) > 1 ) { $args['meta_query'] = $meta; }
    $query = new WP_Query( $args ); ob_start();
    if ( $query->have_posts() ) { while ( $query->have_posts() ) { $query->the_post(); get_template_part( 'template-parts/apartments/card' ); } }
    else { echo '<div class="col-12"><p class="py-5 text-center">Nema pronađenih stanova. Promenite kriterijume pretrage.</p></div>'; }
    wp_reset_postdata(); $html = ob_get_clean(); $pages = (int) $query->max_num_pages;
    $landing = luxor_archive_landing( 'stanovi' ); $pagination = '';
    if ( $pages > 1 && $landing ) {
        // Only a bounded set of links around the current page, even for a large catalog.
        $indices = array_unique( array_merge( array( 1, $pages ), ( $page <= $pages ? range( max( 1, $page - 2 ), min( $pages, $page + 2 ) ) : array() ) ) ); sort( $indices );
        $pagination = '<nav class="luxor-apartment-pagination" aria-label="Stranice ponude stanova"><ul class="pagination flex-wrap">';
        foreach ( $indices as $number ) {
            $params = array_filter( $filters ); if ( $number > 1 ) { $params['stanovi_strana'] = $number; }
            $url = $params ? add_query_arg( $params, $landing ) : $landing;
            $pagination .= '<li class="page-item' . ( $number === $page ? ' active' : '' ) . '"><a class="page-link" data-apartment-page="' . (int) $number . '" href="' . esc_url( $url ) . '"' . ( $number === $page ? ' aria-current="page"' : '' ) . '>' . (int) $number . '</a></li>';
        }
        $pagination .= '</ul></nav>';
    }
    return array( 'html' => $html, 'count' => (int) $query->found_posts, 'shown' => (int) $query->post_count, 'page' => $page, 'pages' => $pages, 'pagination' => $pagination );
}
function luxor_filter_stanovi() {
    check_ajax_referer( 'stanovi_filter_nonce', 'nonce' );
    $page = isset( $_POST['page'] ) && is_scalar( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;
    wp_send_json_success( luxor_apartments_result( $_POST, $page ) );
}
add_action( 'wp_ajax_filter_stanovi', 'luxor_filter_stanovi' );
add_action( 'wp_ajax_nopriv_filter_stanovi', 'luxor_filter_stanovi' );
