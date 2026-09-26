<?php

get_header(); ?>
    <div id="content" class="site-content">

        <main id="main" class="page-main site-main" role="main">
            <?php
            while (have_posts()) : the_post();
                if (post_type_exists(get_post_type())) :
                    get_template_part('template-parts/post/content', get_post_type());
                else :
                    get_template_part('template-parts/post/content');
                endif;
                //                comments_template( '', true );
            endwhile; ?>
        </main>
    </div>
<?php
// get_sidebar();
get_footer();