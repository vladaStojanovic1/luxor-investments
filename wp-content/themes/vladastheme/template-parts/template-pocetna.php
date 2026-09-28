<?php
/* Template Name: Home */

get_header(); ?>
    <div id="content" class="site-content">
        <?php
        include(get_template_directory() . '/inc/_partials/_navigation.php');
        headerHomePage();
        ?>

        <main id="main" class="page-main site-main" role="main">
            <?php include(get_template_directory() . '/template-parts/sections/about-us.php'); ?>
            <?php include(get_template_directory() . '/template-parts/sections/offers.php'); ?>
            <?php include(get_template_directory() . '/template-parts/sections/projects-slider.php'); ?>
            <?php include(get_template_directory() . '/template-parts/sections/call.php'); ?>
        </main>
    </div>
<?php
get_footer();