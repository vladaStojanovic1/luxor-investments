
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

    <!--Favicon-->
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri() . '/src/images/favicon/favicon-96x96.png'; ?>" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri() . '/src/images/favicon/favicon.svg'; ?>" />
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri() . '/src/images/favicon/favicon.ico'; ?>" />
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri() . '/src/images/favicon/apple-touch-icon.png'; ?>" />
    <meta name="apple-mobile-web-app-title" content="Vojkan ALU & PVC" />
    <link rel="manifest" href="<?php echo get_template_directory_uri() . '/src/images/favicon/site.webmanifest'; ?>" />
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">