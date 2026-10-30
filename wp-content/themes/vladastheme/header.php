
<?php
/**
 * @package vladastheme
 */
global $globalSite;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php
    if (is_single() || is_page()) {
        $yoast_meta_desc = get_post_meta(get_the_ID(), '_yoast_wpseo_metadesc', true);
        if (!empty($yoast_meta_desc)) {
            echo '<meta name="description" content="' . esc_attr($yoast_meta_desc) . '">';
        } else {
            $excerpt = strip_tags(get_the_excerpt());
            echo '<meta name="description" content="' . esc_attr($excerpt) . '">';
        }
    } else {
        if (function_exists('wpseo_get_value')) {
            $meta_description = wpseo_get_value('metadesc');
            if (!empty($meta_description)) {
                echo '<meta name="description" content="' . esc_attr($meta_description) . '">';
            }
        }
    }
    ?>

    <?php wp_head(); ?>

    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php if ( ! has_site_icon() ) :
        $favicon_uri = get_template_directory_uri() . '/src/images/favicon/';
    ?>
    <link rel="icon" type="image/x-icon" href="<?php echo esc_url( $favicon_uri . 'favicon.ico' ); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url( $favicon_uri . 'favicon-32x32.png' ); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url( $favicon_uri . 'favicon-16x16.png' ); ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo esc_url( $favicon_uri . 'favicon-96x96.png' ); ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url( $favicon_uri . 'apple-touch-icon.png' ); ?>">
    <meta name="apple-mobile-web-app-title" content="Luxor Investments">
    <link rel="manifest" href="<?php echo esc_url( $favicon_uri . 'site.webmanifest' ); ?>">
    <?php endif; ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">