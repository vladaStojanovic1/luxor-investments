
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

    <?php wp_head(); ?>
    <noscript><style>[data-aos]{opacity:1!important;transform:none!important;visibility:visible!important}#apartmentsFilters{position:static!important;visibility:visible!important;transform:none!important;width:auto!important}.apartments-filter-mobile,#apartmentsFilters .offcanvas-header,#reset-apartment-filters{display:none!important}</style></noscript>

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
<?php wp_body_open(); ?>
<div id="page" class="site">