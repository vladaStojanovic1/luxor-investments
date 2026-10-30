<?php
get_header();
get_template_part( 'inc/_partials/_navigation' );
?>
<main id="main" class="page-main site-main">
<?php while ( have_posts() ) : the_post(); get_template_part( 'template-parts/post/content', 'stanovi' ); endwhile; ?>
</main>
<?php get_footer(); ?>
