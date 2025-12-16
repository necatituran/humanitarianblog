<?php
/**
 * The footer template file - Editorial Magazine Style
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <!-- Background Text -->
        <div class="footer-bg-text" aria-hidden="true">HUMANITARIANBLOG</div>

        <div class="container">
            <!-- Footer Main -->
            <div class="footer-main">
                <!-- Column 1: About -->
                <div class="footer-column footer-about">
                    <div class="footer-logo">
                        <?php
                        $theme_logo = HUMANITARIAN_THEME_URI . '/assets/images/humanitarian-logo.png';
                        ?>
                        <img src="<?php echo esc_url($theme_logo); ?>" alt="<?php bloginfo('name'); ?>" class="footer-logo-img" />
                    </div>
                    <p class="footer-description">
                        <?php _e('Independent journalism covering humanitarian crises, conflict zones, and global emergencies. Giving voice to the voiceless.', 'humanitarianblog'); ?>
                    </p>
                    <div class="footer-social">
                        <a href="#" aria-label="Twitter" class="social-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="Facebook" class="social-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="LinkedIn" class="social-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" aria-label="YouTube" class="social-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Column 2: About -->
                <div class="footer-column">
                    <h4 class="footer-heading"><?php _e('About', 'humanitarianblog'); ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/about-us/')); ?>"><?php _e('About Us', 'humanitarianblog'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/authors/')); ?>"><?php _e('Authors', 'humanitarianblog'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/editorial-standards/')); ?>"><?php _e('Editorial Standards', 'humanitarianblog'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/corrections/')); ?>"><?php _e('Corrections', 'humanitarianblog'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact', 'humanitarianblog'); ?></a></li>
                    </ul>
                </div>

                <!-- Column 3: Get Involved -->
                <div class="footer-column">
                    <h4 class="footer-heading"><?php _e('Get Involved', 'humanitarianblog'); ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo esc_url(home_url('/donate/')); ?>"><?php _e('Donate', 'humanitarianblog'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/write-for-us/')); ?>"><?php _e('Write for Us', 'humanitarianblog'); ?></a></li>
                        <li><a href="#newsletter"><?php _e('Newsletter', 'humanitarianblog'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/archive/')); ?>"><?php _e('Archive', 'humanitarianblog'); ?></a></li>
                        <li><a href="<?php echo esc_url(home_url('/faq/')); ?>"><?php _e('FAQ', 'humanitarianblog'); ?></a></li>
                    </ul>
                </div>

                <!-- Column 4: Contact -->
                <div class="footer-column">
                    <h4 class="footer-heading"><?php _e('Get in Touch', 'humanitarianblog'); ?></h4>
                    <ul class="footer-contact">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="16" x="2" y="4" rx="2"/>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                            </svg>
                            <a href="mailto:info@humanitarianblog.org">info@humanitarianblog.org</a>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            <span><?php _e('Global Coverage', 'humanitarianblog'); ?></span>
                        </li>
                    </ul>
                    <a href="<?php echo esc_url(home_url('/donate/')); ?>" class="footer-contact-btn footer-donate-btn">
                        <?php _e('Support Our Work', 'humanitarianblog'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-copyright">
                    <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'humanitarianblog'); ?></p>
                </div>
                <nav class="footer-legal">
                    <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php _e('Privacy Policy', 'humanitarianblog'); ?></a>
                    <a href="<?php echo esc_url(home_url('/terms-of-service/')); ?>"><?php _e('Terms of Service', 'humanitarianblog'); ?></a>
                    <a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>"><?php _e('Cookie Policy', 'humanitarianblog'); ?></a>
                </nav>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <button class="back-to-top" id="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'humanitarianblog'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="m18 15-6-6-6 6"/>
        </svg>
    </button>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
