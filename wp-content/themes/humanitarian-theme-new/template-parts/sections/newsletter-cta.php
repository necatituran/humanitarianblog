<?php
/**
 * Newsletter CTA Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if newsletter section should be shown
if (!get_theme_mod('humanitarian_show_newsletter', true)) {
    return;
}

$newsletter_url = get_theme_mod('humanitarian_newsletter_url', '#');
?>

<section class="newsletter-section">
    <div class="container newsletter-section__inner">

        <!-- Left Icon -->
        <div class="newsletter-section__icon">
            <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>

        <!-- Text Content -->
        <div class="newsletter-section__content">
            <div class="newsletter-section__title-wrapper">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h2 class="newsletter-section__title">
                    <?php esc_html_e('Get the latest humanitarian news, direct to your inbox', 'humanitarian'); ?>
                </h2>
            </div>
            <p class="newsletter-section__text">
                <?php esc_html_e('Sign up to receive our original, on-the-ground coverage that informs policymakers, practitioners, donors, and others who want to make the world more humane.', 'humanitarian'); ?>
            </p>
        </div>

        <!-- Action -->
        <div class="newsletter-section__action">
            <a href="<?php echo esc_url($newsletter_url); ?>" class="newsletter-section__btn">
                <?php esc_html_e('Sign up', 'humanitarian'); ?>
            </a>
        </div>

    </div>
</section>
