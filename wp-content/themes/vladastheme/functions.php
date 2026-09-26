<?php

function bootstrap_enqueue_styles() {
    wp_enqueue_style('bootstrap-css', get_stylesheet_directory_uri() . '/src/styles/css/vendor/bootstrap.min.css');
    wp_enqueue_style('bootstrap-icons-css', get_stylesheet_directory_uri() . '/src/styles/css/vendor/bootstrap-icons.css');
}
add_action('wp_enqueue_scripts', 'bootstrap_enqueue_styles');


add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'vladastheme-swiper-style', get_stylesheet_directory_uri() . '/src/styles/css/vendor/swiper.min.css' );
    wp_enqueue_style( 'vladastheme-animate', get_stylesheet_directory_uri() . '/src/styles/css/vendor/aos.css' );
    wp_enqueue_style( 'vladastheme-fancybox', get_stylesheet_directory_uri() . '/src/styles/css/vendor/fancybox.css' );
    // wp_enqueue_style( 'vladastheme-style', get_stylesheet_uri() );

    $style_path = get_stylesheet_directory() . '/style.css';

    wp_enqueue_style(
        'vladastheme-style',
        get_stylesheet_uri(),
        array(),
        file_exists( $style_path ) ? filemtime( $style_path ) : null
    );

    if( WP_DEBUG === true ) {
        wp_enqueue_script( 'vladastheme-swiper', get_template_directory_uri() . '/src/scripts/src/swiper.js', array('jquery'), true );
        wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/src/scripts/src/fancybox.js', array('jquery'), true );
        wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/src/scripts/src/bootstrap.bundle.min.js', array(), null, true);
        wp_enqueue_script( 'vladastheme-script', get_template_directory_uri() . '/src/scripts/src/script.js', array('jquery'), true );
    } else {
        wp_enqueue_script( 'vladastheme-swiper', get_template_directory_uri() . '/src/scripts/src/swiper.js', array('jquery'), true );
        wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/src/scripts/src/fancybox.js', array('jquery'), true );
        wp_enqueue_script( 'textPlugin', get_template_directory_uri() . '/src/scripts/src/textPlugin.min.js', array('jquery'), true );
        wp_enqueue_script( 'gsap', get_template_directory_uri() . '/src/scripts/src/gsap.min.js', array('jquery'), true );
        wp_enqueue_script('bootstrap-js', get_template_directory_uri() . '/src/scripts/src/bootstrap.bundle.min.js', array(), true);
        wp_enqueue_script('aos-js', get_template_directory_uri() . '/src/scripts/src/aos.js', array(), null, true);
        wp_enqueue_script('fancybox-js', get_template_directory_uri() . '/src/scripts/src/fancybox.umd.js', array(), null, true);
        wp_enqueue_script('single-apartment-js', get_template_directory_uri() . '/src/scripts/src/single-apartment.js', array(), null, true);
        wp_enqueue_script('projekti-js', get_template_directory_uri() . '/src/scripts/src/projekti.js', array(), null, true);
        wp_enqueue_script('single-project-js', get_template_directory_uri() . '/src/scripts/src/single-project.js', array(), null, true);
        wp_enqueue_script( 'vladastheme-script-min', get_template_directory_uri() . '/bundles/scripts/scripts.min.js', array('jquery'), true );
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
} );


include ( get_template_directory() . '/inc/_partials/index.php' );

function wpb_custom_new_menu() {
    register_nav_menu('my-custom-menu',__( 'My Custom Menu' ));
}
add_action( 'init', 'wpb_custom_new_menu' );


if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title'    => 'Theme General Settings',
        'menu_title'    => 'Theme Settings',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Header Settings',
        'menu_title'    => 'Header',
        'parent_slug'   => 'theme-general-settings',
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Theme Footer Settings',
        'menu_title'    => 'Footer',
        'parent_slug'   => 'theme-general-settings',
    ));

}

function theme_gsap_script(){
    // The core GSAP library
    wp_enqueue_script( 'gsap-js', get_template_directory_uri() . '/src/scripts/src/gsap.min.js', array(), false, true );
    wp_enqueue_script( 'gsap-st', get_template_directory_uri() . '/src/scripts/src/ScrollTrigger.min.js', array('gsap-js'), false, true );
}
add_action( 'wp_enqueue_scripts', 'theme_gsap_script' );

// Omogućavanje podrške za featured image
function enable_featured_images() {
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'enable_featured_images');

function get_excerpt_words($content, $word_limit = 50) {
    $allowed_tags = [
        'strong' => [],
        'b' => [],
        'em' => [],
        'i' => [],
    ];

    $content = wp_kses($content, $allowed_tags);

    $words = explode(' ', $content);

    if (count($words) > $word_limit) {
        $excerpt = implode(' ', array_slice($words, 0, $word_limit)) . '...';
    } else {
        $excerpt = $content;
    }
    return $excerpt;
}

function register_my_menus() {
    register_nav_menus(
        array(
            'desktop-menu' => __('Desktop Menu'),
            'mobile-menu' => __('Mobile Menu'),
            'services' => __('Services')
        )
    );
}
add_action('init', 'register_my_menus');




/**
 * CUSTOM POST TYPE: PROJEKTI
 */
function register_projekti_cpt() {

    $labels = array(
        'name'          => 'Projekti',
        'singular_name' => 'Projekat',
        'menu_name'     => 'Projekti',
        'add_new'       => 'Dodaj projekat',
        'add_new_item'  => 'Dodaj novi projekat',
        'edit_item'     => 'Izmeni projekat',
        'new_item'      => 'Novi projekat',
        'view_item'     => 'Pogledaj projekat',
        'search_items'  => 'Pretraži projekte',
        'not_found'     => 'Nema pronađenih projekata',
    );

    register_post_type('projekti', array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,

        'menu_icon'          => 'dashicons-building',
        'menu_position'      => 5,

        'has_archive'        => true,

        'rewrite' => array(
            'slug'       => 'projekti',
            'with_front' => false,
        ),

        'supports' => array(
            'title',
            'editor',
            'thumbnail',
            'excerpt'
        ),
    ));
}
add_action('init', 'register_projekti_cpt');


/**
 * CUSTOM POST TYPE: STANOVI
 */
function register_stanovi_cpt() {

    $labels = array(
        'name'          => 'Stanovi',
        'singular_name' => 'Stan',
        'menu_name'     => 'Stanovi',
        'add_new'       => 'Dodaj stan',
        'add_new_item'  => 'Dodaj novi stan',
        'edit_item'     => 'Izmeni stan',
        'new_item'      => 'Novi stan',
        'view_item'     => 'Pogledaj stan',
        'search_items'  => 'Pretraži stanove',
        'not_found'     => 'Nema pronađenih stanova',
    );

    register_post_type('stanovi', array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,

        'menu_icon'          => 'dashicons-admin-home',
        'menu_position'      => 6,

        'has_archive'        => true,

        'rewrite' => array(
            'slug'       => 'stanovi',
            'with_front' => false,
        ),

        'supports' => array(
            'title',
            'editor',
            'thumbnail'
        ),
    ));
}
add_action('init', 'register_stanovi_cpt');


/**
 * TAXONOMY: TIP STANA
 */
function register_tip_stana_taxonomy() {

    $labels = array(
        'name'          => 'Tipovi stanova',
        'singular_name' => 'Tip stana',
        'menu_name'     => 'Tipovi stanova',
        'all_items'     => 'Svi tipovi',
        'edit_item'     => 'Izmeni tip',
        'add_new_item'  => 'Dodaj novi tip',
        'new_item_name' => 'Naziv novog tipa',
    );

    register_taxonomy(
        'tip_stana',
        array('stanovi'),
        array(
            'labels'            => $labels,
            'public'            => true,
            'hierarchical'      => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,

            'rewrite' => array(
                'slug' => 'tip-stana',
            ),
        )
    );
}
add_action('init', 'register_tip_stana_taxonomy');


// Filters
function luxor_enqueue_apartments_filter_scripts() {

    wp_enqueue_script(
        'luxor-apartments-filter',
        get_template_directory_uri() . '/src/scripts/src/filters.js',
        [],
        '1.0',
        true
    );


    wp_localize_script(
        'luxor-apartments-filter',
        'stanoviAjax',
        [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('stanovi_filter_nonce'),
        ]
    );

}

add_action('wp_enqueue_scripts', 'luxor_enqueue_apartments_filter_scripts');





function luxor_filter_stanovi() {

    check_ajax_referer(
        'stanovi_filter_nonce',
        'nonce'
    );


    $tip_stana = isset($_POST['tip_stana'])
        ? array_map('sanitize_text_field', $_POST['tip_stana'])
        : [];


    $sprat = isset($_POST['sprat'])
        ? array_map('intval', $_POST['sprat'])
        : [];


    $projekat = isset($_POST['projekat'])
        ? array_map('intval', $_POST['projekat'])
        : [];


    $status = isset($_POST['status'])
        ? array_map('sanitize_text_field', $_POST['status'])
        : [];


    /*
     * TAXONOMY QUERY
     */

    $tax_query = [];


    if (!empty($tip_stana)) {

        $tax_query[] = [
            'taxonomy' => 'tip_stana',
            'field'    => 'slug',
            'terms'    => $tip_stana,
            'operator' => 'IN',
        ];

    }


    /*
     * META QUERY
     */

    $meta_query = [
        'relation' => 'AND'
    ];


    /*
     * SPRAT
     */

    if (!empty($sprat)) {

        $meta_query[] = [
            'key'     => 'sprat',
            'value'   => $sprat,
            'compare' => 'IN',
            'type'    => 'NUMERIC',
        ];

    }


    /*
     * PROJEKAT
     *
     * ACF Post Object čuva ID projekta.
     */

    if (!empty($projekat)) {

        $meta_query[] = [
            'key'     => 'projekat',
            'value'   => $projekat,
            'compare' => 'IN',
            'type'    => 'NUMERIC',
        ];

    }


    /*
     * STATUS
     */

    if (!empty($status)) {

        $meta_query[] = [
            'key'     => 'status',
            'value'   => $status,
            'compare' => 'IN',
        ];

    }


    /*
     * QUERY
     */

    $args = [

        'post_type'      => 'stanovi',

        'post_status'    => 'publish',

        'posts_per_page' => -1,

        'orderby'        => 'title',

        'order'          => 'ASC',

    ];


    if (!empty($tax_query)) {

        $args['tax_query'] = $tax_query;

    }


    if (count($meta_query) > 1) {

        $args['meta_query'] = $meta_query;

    }


    $query = new WP_Query($args);


    ob_start();


    if ($query->have_posts()) :

        while ($query->have_posts()) :

            $query->the_post();


            $project = get_field('projekat');

            $floor = get_field('sprat');

            $area = get_field('kvadratura');

            $status_value = get_field('status');

            $gallery = get_field('galerija');
            $gallery_count = is_array($gallery) ? count($gallery) : 0;


            $types = get_the_terms(
                get_the_ID(),
                'tip_stana'
            );


            $type_name = '';

            if ($types && !is_wp_error($types)) {

                $type_name = $types[0]->name;

            }


            ?>



<div class="col-12 mb-4">

    <article class="apartment-card border rounded-4 overflow-hidden bg-white shadow-sm">

        <div class="row g-0 align-items-stretch">

            <!-- SLIKA -->
            <div class="col-lg-4 position-relative">

                <?php if (has_post_thumbnail()) : ?>

                    <a href="<?php the_permalink(); ?>" class="d-block h-100">

                        <?php
                        the_post_thumbnail(
                            'large',
                            [
                                'class' => 'img-fluid w-100 apartment-card-image'
                            ]
                        );
                        ?>

                        <?php if ($gallery_count > 0) : ?>

                            <div class="position-absolute bottom-0 start-0 m-3">
                                <span class="badge bg-dark bg-opacity-75 px-3 py-2 rounded-pill">
                                    <i class="bi bi-images me-2"></i>
                                    <?php echo esc_html($gallery_count); ?>
                                    fotografija
                                </span>
                            </div>

                        <?php endif; ?>

                    </a>

                <?php endif; ?>


                <?php if ($status_value) : ?>

                    <?php
                    $status_class = '';

                    if ($status_value === 'slobodan') {
                        $status_class = 'bg-success';
                    } elseif ($status_value === 'rezervisan') {
                        $status_class = 'bg-warning text-dark';
                    } elseif ($status_value === 'prodat') {
                        $status_class = 'bg-danger';
                    }
                    ?>

                    <span class="position-absolute top-0 start-0 m-3 badge rounded-pill px-3 py-2 <?php echo esc_attr($status_class); ?>">
                        <?php echo esc_html(ucfirst($status_value)); ?>
                    </span>

                <?php endif; ?>

            </div>


            <!-- INFO -->
            <div class="col-lg-5">

                <div class="p-4 p-xl-5 h-100">

                    <?php if ($type_name) : ?>

                        <div class="text-uppercase text-muted small fw-semibold mb-2">
                            <?php echo esc_html($type_name); ?>
                        </div>

                    <?php endif; ?>


                    <h3 class="mb-4">

                        <a
                            href="<?php the_permalink(); ?>"
                            class="text-decoration-none text-dark"
                        >
                            <?php the_title(); ?>
                        </a>

                    </h3>


                    <div class="row g-4">

                        <?php if ($project) : ?>

                            <div class="col-6">

                                <div class="d-flex gap-3 align-items-start">

                                    <i class="bi bi-buildings fs-4 text-secondary"></i>

                                    <div>
                                        <div class="small text-muted">
                                            Projekat
                                        </div>

                                        <div class="fw-semibold">
                                            <?php
                                            echo esc_html(
                                                get_the_title($project)
                                            );
                                            ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>


                        <?php if ($floor !== '') : ?>

                            <div class="col-6">

                                <div class="d-flex gap-3 align-items-start">

                                    <i class="bi bi-bar-chart-steps fs-4 text-secondary"></i>

                                    <div>
                                        <div class="small text-muted">
                                            Sprat
                                        </div>

                                        <div class="fw-semibold">
                                            <?php echo esc_html($floor); ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>


                        <?php if ($area) : ?>

                            <div class="col-6">

                                <div class="d-flex gap-3 align-items-start">

                                    <i class="bi bi-arrows-fullscreen fs-4 text-secondary"></i>

                                    <div>
                                        <div class="small text-muted">
                                            Kvadratura
                                        </div>

                                        <div class="fw-semibold">
                                            <?php echo esc_html($area); ?> m²
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>


                        <?php if ($status_value) : ?>

                            <div class="col-6">

                                <div class="d-flex gap-3 align-items-start">

                                    <i class="bi bi-file-earmark-check fs-4 text-secondary"></i>

                                    <div>
                                        <div class="small text-muted">
                                            Status
                                        </div>

                                        <div class="fw-semibold">
                                            <?php echo esc_html(ucfirst($status_value)); ?>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>


            <!-- CTA -->
            <div class="col-lg-3 border-start">

                <div class="p-4 h-100 d-flex flex-column justify-content-center align-items-center">

                    <a
                        href="<?php the_permalink(); ?>"
                        class="btn btn-dark py-3 btn-dark-blue"
                    >
                        Pogledaj stan
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                </div>

            </div>

        </div>

    </article>

</div>




            <?php

        endwhile;

    else :

        ?>

        <div class="col-12">

            <div class="py-5 text-center">

                <h4>Nema pronađenih stanova</h4>

                <p>
                    Promenite kriterijume pretrage.
                </p>

            </div>

        </div>

        <?php

    endif;


    wp_reset_postdata();


    $html = ob_get_clean();


    wp_send_json_success([
        'html'  => $html,
        'count' => $query->found_posts,
    ]);

}


/*
 * Za ulogovane korisnike
 */
add_action(
    'wp_ajax_filter_stanovi',
    'luxor_filter_stanovi'
);


/*
 * Za korisnike koji nisu ulogovani
 */
add_action(
    'wp_ajax_nopriv_filter_stanovi',
    'luxor_filter_stanovi'
);