<?php get_header(); get_template_part( 'inc/_partials/_navigation' ); ?>
<main id="main" class="container py-5">
<h1><?php echo esc_html( is_search() ? 'Rezultati pretrage: ' . get_search_query() : ( is_archive() ? wp_strip_all_tags( get_the_archive_title() ) : get_bloginfo( 'name' ) ) ); ?></h1>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article class="my-5"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'medium_large', array( 'class' => 'img-fluid' ) ); } the_excerpt(); ?></article>
<?php endwhile; the_posts_pagination(); else : ?><p>Nema pronađenog sadržaja.</p><?php get_search_form(); endif; ?>
</main>
<?php get_footer(); ?>
