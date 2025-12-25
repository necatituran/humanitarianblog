<?php
/**
 * Footer Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

</main><!-- #content -->

<!-- Strip Section - Before Footer -->
<section class="strip-section">
    <div class="container strip-section__inner">
        <div class="strip-section__text">
            <p class="strip-section__tagline"><?php esc_html_e('Enlightens the fields with real stories and practical knowledge.', 'humanitarian'); ?></p>
        </div>
        <div class="strip-section__logo">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/hum_logo_3.jpeg'); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" />
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="site-footer__grid">

            <!-- Brand Column -->
            <div class="site-footer__brand">
                <div class="site-footer__logo">
                    <?php echo esc_html(get_bloginfo('name')); ?>.
                </div>
                <p class="site-footer__tagline">
                    <?php echo esc_html(get_theme_mod('humanitarian_footer_tagline', __('An independent, non-profit, open-source platform dedicated to individuals engaged in humanitarian and development work.', 'humanitarian'))); ?>
                </p>
                <div class="site-footer__social">
                    <?php if ($twitter = get_theme_mod('humanitarian_twitter_url')) : ?>
                    <a href="<?php echo esc_url($twitter); ?>" class="site-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Twitter', 'humanitarian'); ?>">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if ($facebook = get_theme_mod('humanitarian_facebook_url')) : ?>
                    <a href="<?php echo esc_url($facebook); ?>" class="site-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('Facebook', 'humanitarian'); ?>">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if ($linkedin = get_theme_mod('humanitarian_linkedin_url')) : ?>
                    <a href="<?php echo esc_url($linkedin); ?>" class="site-footer__social-link" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e('LinkedIn', 'humanitarian'); ?>">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                        </svg>
                    </a>
                    <?php endif; ?>

                    <?php if ($email = get_theme_mod('humanitarian_email', 'info@humanitarianblog.org')) : ?>
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="site-footer__social-link" aria-label="<?php esc_attr_e('Email', 'humanitarian'); ?>">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sections Navigation -->
            <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footer Sections Navigation', 'humanitarian'); ?>">
                <h4 class="site-footer__nav-title"><?php esc_html_e('Sections', 'humanitarian'); ?></h4>
                <?php
                if (has_nav_menu('footer-sections')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-sections',
                        'container'      => false,
                        'menu_class'     => '',
                        'walker'         => new Humanitarian_Footer_Nav_Walker(),
                        'depth'          => 1,
                    ));
                } else {
                    // Default: Main categories
                    ?>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/category/technical-guides/')); ?>" class="site-footer__nav-link"><?php esc_html_e('Technical Guides', 'humanitarian'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/category/aid-and-policy/')); ?>" class="site-footer__nav-link"><?php esc_html_e('Aid and Policy', 'humanitarian'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/category/environment-and-conflict/')); ?>" class="site-footer__nav-link"><?php esc_html_e('Environment and Conflict', 'humanitarian'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/category/stories-from-the-field/')); ?>" class="site-footer__nav-link"><?php esc_html_e('Stories from the Field', 'humanitarian'); ?></a></li>
                    </ul>
                    <?php
                }
                ?>
            </nav>

            <!-- About Navigation -->
            <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footer About Navigation', 'humanitarian'); ?>">
                <h4 class="site-footer__nav-title"><?php esc_html_e('About', 'humanitarian'); ?></h4>
                <?php
                if (has_nav_menu('footer-about')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-about',
                        'container'      => false,
                        'menu_class'     => '',
                        'walker'         => new Humanitarian_Footer_Nav_Walker(),
                        'depth'          => 1,
                    ));
                } else {
                    // Default links
                    ?>
                    <ul>
                        <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="site-footer__nav-link"><?php esc_html_e('About Us', 'humanitarian'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/publish-with-us/')); ?>" class="site-footer__nav-link"><?php esc_html_e('Publish With Us', 'humanitarian'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="site-footer__nav-link"><?php esc_html_e('Contact Us', 'humanitarian'); ?></a></li>
                    </ul>
                    <?php
                }
                ?>
            </nav>

            <!-- Support Section -->
            <div class="site-footer__support">
                <h4 class="site-footer__support-title"><?php esc_html_e('Share Your Knowledge', 'humanitarian'); ?></h4>
                <p class="site-footer__support-text">
                    <?php echo esc_html(get_theme_mod('humanitarian_support_text', __('The Humanitarian Blog opens a door for field practitioners, analysts, and storytellers to share their experiences and perspectives.', 'humanitarian'))); ?>
                </p>
                <a href="<?php echo esc_url(home_url('/publish-with-us/')); ?>" class="site-footer__donate-btn">
                    <?php esc_html_e('Write For Us', 'humanitarian'); ?>
                </a>
            </div>

        </div>

        <!-- Bottom Bar -->
        <div class="site-footer__bottom">
            <p>
                <?php
                printf(
                    /* translators: %1$s: year, %2$s: site name */
                    esc_html__('© %1$s %2$s. All rights reserved.', 'humanitarian'),
                    date('Y'),
                    get_bloginfo('name')
                );
                ?>
            </p>
            <nav class="site-footer__legal" aria-label="<?php esc_attr_e('Legal Navigation', 'humanitarian'); ?>">
                <?php
                if (has_nav_menu('footer-legal')) {
                    wp_nav_menu(array(
                        'theme_location' => 'footer-legal',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'depth'          => 1,
                        'link_before'    => '<span class="site-footer__legal-link">',
                        'link_after'     => '</span>',
                    ));
                } else {
                    ?>
                    <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="site-footer__legal-link"><?php esc_html_e('Contact Us', 'humanitarian'); ?></a>
                    <a href="<?php echo esc_url(home_url('/cookies/')); ?>" class="site-footer__legal-link"><?php esc_html_e('Cookies', 'humanitarian'); ?></a>
                    <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" class="site-footer__legal-link"><?php esc_html_e('Privacy Policy', 'humanitarian'); ?></a>
                    <a href="<?php echo esc_url(home_url('/child-safeguard-policy/')); ?>" class="site-footer__legal-link"><?php esc_html_e('Child Safeguard Policy', 'humanitarian'); ?></a>
                    <a href="<?php echo esc_url(home_url('/content-usage-policy/')); ?>" class="site-footer__legal-link"><?php esc_html_e('Content Usage Policy and User Agreement', 'humanitarian'); ?></a>
                    <?php
                }
                ?>
            </nav>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
