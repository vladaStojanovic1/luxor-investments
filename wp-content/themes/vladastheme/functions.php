<?php

require_once get_template_directory() . '/inc/media.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/seo.php';

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

// Omogućavanje podrške za featured image
function enable_featured_images() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
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


require_once get_template_directory() . '/inc/apartments.php';
