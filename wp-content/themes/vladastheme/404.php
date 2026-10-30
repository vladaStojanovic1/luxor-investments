<?php get_header(); get_template_part( 'inc/_partials/_navigation' ); ?>
<main id="main" class="container py-5"><h1>Stranica nije pronađena</h1><p>Proverite adresu ili se vratite na početnu stranicu.</p><p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Početna stranica</a></p><?php get_search_form(); ?></main>
<?php get_footer(); ?>
