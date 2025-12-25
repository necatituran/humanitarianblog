<?php
/**
 * 404 Error Page Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="error-404">
    <div class="container">
        <p class="error-404__code">404</p>
        <h1 class="error-404__title"><?php esc_html_e('Page not found', 'humanitarian'); ?></h1>
        <p class="error-404__text">
            <?php esc_html_e('The page you\'re looking for doesn\'t exist or has been moved. Try searching or go back to the homepage.', 'humanitarian'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="error-404__btn">
            <?php esc_html_e('Go to Homepage', 'humanitarian'); ?>
        </a>
    </div>
</div>

<?php
get_footer();
?>
