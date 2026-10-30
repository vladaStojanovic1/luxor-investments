<?php get_header(); get_template_part( 'inc/_partials/_navigation' ); ?>
<main id="main" class="container py-5">
<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/post/content', 'page' );
if ( function_exists( 'get_field' ) && function_exists( 'flex_post_content' ) && ( get_field( 'tcontent' ) || get_field( 'content' ) ) ) { flex_post_content(); }
endwhile; ?>
</main>
<?php get_footer(); ?>
