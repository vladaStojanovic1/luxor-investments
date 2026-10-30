
<?php
// $site_logo = get_field('site_logo','options');
$logo_white = get_field('logo_white','options');
$phone = get_field('phone','options');
$email = get_field('email','options');
$address = get_field('address','options');
$fb_icon_url = get_field('fb_icon_url','options');
$instagram_icon_url = get_field('instagram_icon_url','options');
$whatsapp_icon_url = get_field('whatsapp_icon_url','options');
$footer_left_text = get_field('footer_left_text','options');

?>
<footer class="m-footer position-relative dark-bg">
    <div class="container">
       <div class="row">
           <div class="col-md-3 me-lg-auto text-center text-md-start mb-5 mb-md-0">
               <div class='text-center'>
                   <?php luxor_picture( $logo_white, 'medium', array( 'class' => 'm-footer__logo', 'alt' => get_bloginfo( 'name' ), 'sizes' => '100px', 'loading' => 'lazy', 'data-aos' => 'flip-up', 'data-aos-easing' => 'ease-in-sine' ) ); ?>
                   <p class="mb-0 mt-4 fs-14 m-footer__leftText"><?php echo wp_kses_post( $footer_left_text ); ?></p>

                   <div class="mt-4">
                       <a href="<?php echo esc_url( luxor_whatsapp_url( $phone ) ); ?>" aria-label="WhatsApp — otvara novi prozor" target="_blank" rel="noopener noreferrer" class="text-white me-3"><i class="bi bi-whatsapp fs-5"></i></a>
                       <a href="<?php echo esc_url( $instagram_icon_url ); ?>" aria-label="Instagram — otvara novi prozor" target="_blank" rel="noopener noreferrer" class="text-white me-3"><i class="bi bi-instagram fs-5"></i></a>
                   </div>
               </div>
           </div>

           <div class="col-md-3 w-20-lg">
               <div class="m-footer__menu text-center text-md-start mb-5 mb-md-0">
                   <h5 class="text-uppercase text-white mb-3 mb-md-4">Istražite</h5>
                   <?php wp_nav_menu( array( 'theme_location' => 'desktop-menu' ) ); ?>
               </div>
           </div>

           <div class="col-md-3 w-20-lg text-center text-md-start mb-5 mb-md-0">
               <h5 class="text-uppercase text-white mb-3 mb-md-4">Nasi projekti</h5>
               <div class="m-footer__menu">

                        <?php
                        $projekti = new WP_Query(array(
                            'post_type'      => 'projekti',
                            'posts_per_page' => -1,
                            'post_status'    => 'publish',
                            'orderby'        => 'date',
                            'order'          => 'ASC'
                        ));

                        if ($projekti->have_posts()) : ?>
                            <ul class="footer-menu">
                                <?php while ($projekti->have_posts()) : $projekti->the_post(); ?>
                                    <li>
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                        </a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php endif;

                        wp_reset_postdata();
                        ?>
               </div>
           </div>

           <div class="col-md-3 w-20-lg text-center text-md-start">
               <h5 class="text-uppercase text-white mb-3 mb-md-4">Kontaktirajte nas</h5>
               <div>
                   <div class="d-flex align-items-center mb-1 mb-md-2 justify-content-center justify-content-md-start">
                       <i class="bi bi-geo-alt-fill text-white me-3 fs-5"></i>
                       <a class="fs-14" target="_blank" rel="noopener noreferrer" href="<?php echo esc_url( luxor_address_url( $address ) ); ?>"><?php echo esc_html( $address ); ?></a>
                   </div>
                   <div class="d-flex align-items-center mb-1 mb-md-2 justify-content-center justify-content-md-start">
                       <i class="bi bi-telephone-fill text-white me-3 fs-5"></i>
                       <a class="fs-14" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a>
                   </div>
                   <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                       <i class="bi bi-envelope-at-fill text-white me-3 fs-5"></i>
                       <a class="fs-14" href="mailto:<?php echo esc_attr( sanitize_email( $email ) ); ?>"><?php echo esc_html( $email ); ?></a>
                   </div>
               </div>
           </div>

       </div>
    </div>

    <div class="">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between py-3 py-md-4 m-footer__bottom">
                <p class="mb-2 mb-md-0">All rights reserved | Luxor Investments</p>

                <a class="d-flex align-center" target="_blank" rel="noopener noreferrer"
                   href="https://www.instagram.com/vladaasto">&copy;<?php echo date('Y') ?> Created
                    and hosted by
                    <svg xmlns="http://www.w3.org/2000/svg" class="ms-2 icon icon-tabler icon-tabler-brand-instagram" width="20"
                         height="20" viewBox="0 0 24 24" stroke-width="1.5" stroke="#ffffff" fill="none"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <rect x="4" y="4" width="16" height="16" rx="4" />
                        <circle cx="12" cy="12" r="3" />
                        <line x1="16.5" y1="7.5" x2="16.5" y2="7.501" />
                    </svg>
                    <span>vladaasto</span>
                </a>
            </div>
        </div>
    </div>

</footer>

</div><!-- #page -->
<?php wp_footer(); ?>
</body>
</html>
