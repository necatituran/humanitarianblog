<?php
/**
 * The footer template file
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>

    </div><!-- #content -->

    <footer id="colophon" class="site-footer">
        <div class="container">

            <div class="footer-widgets">
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div>
                <div class="footer-widget-area">
                    <?php dynamic_sidebar('footer-4'); ?>
                </div>
            </div>

            <div class="site-info">
                <p>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php _e('All rights reserved.', 'humanitarianblog'); ?></p>
            </div>

        </div>
    </footer>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
