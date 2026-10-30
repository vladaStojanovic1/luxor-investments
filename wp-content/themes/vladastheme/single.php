<?php get_header(); get_template_part( 'inc/_partials/_navigation' ); ?>
<main id="main" class="container py-5">
<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/post/content', get_post_type() ); endwhile; ?>
</main>
<?php get_footer(); ?>
