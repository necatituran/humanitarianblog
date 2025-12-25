<?php
/**
 * Template Name: Contact Us
 * Template for the Contact Us page with two-column layout
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

<div class="page-contact">
    <div class="container">
        <!-- Page Header -->
        <header class="page-contact__header">
            <h1 class="page-contact__title"><?php the_title(); ?></h1>
        </header>

        <div class="page-contact__grid">
            <!-- Left Column: Content -->
            <div class="page-contact__content">
                <div class="page-contact__intro">
                    <h2>Get in Touch</h2>
                    <p>Have a question, feedback, or want to contribute? We'd love to hear from you. Fill out the form or reach us directly using the contact information below.</p>
                </div>

                <div class="page-contact__info">
                    <div class="contact-info-item">
                        <div class="contact-info-item__icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="contact-info-item__content">
                            <h3>Email</h3>
                            <p><a href="mailto:info@humanitarianblog.org">info@humanitarianblog.org</a></p>
                            <p><a href="mailto:editor@humanitarianblog.org">editor@humanitarianblog.org</a></p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-item__icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="contact-info-item__content">
                            <h3>Location</h3>
                            <p>We operate globally with contributors from around the world.</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <div class="contact-info-item__icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="contact-info-item__content">
                            <h3>Response Time</h3>
                            <p>We typically respond within 2-3 business days.</p>
                        </div>
                    </div>
                </div>

                <div class="page-contact__social">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Twitter">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="Facebook">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Contact Form -->
            <div class="page-contact__form-wrapper">
                <div class="page-contact__form-box">
                    <h2>Send us a Message</h2>
                    <?php
                    // Show success/error messages
                    if (function_exists('humanitarian_contact_form_messages')) {
                        humanitarian_contact_form_messages();
                    }
                    ?><?php
                    // Check for Contact Form 7
                    if (function_exists('wpcf7_contact_form')) {
                        // Get Contact Form 7 shortcode
                        $cf7 = get_posts([
                            'post_type' => 'wpcf7_contact_form',
                            'numberposts' => 1
                        ]);
                        if ($cf7) {
                            echo do_shortcode('[contact-form-7 id="' . $cf7[0]->ID . '"]');
                        } else {
                            // Show default form
                            humanitarian_contact_form();
                        }
                    } elseif (function_exists('wpforms_display')) {
                        // Check for WPForms
                        echo do_shortcode('[wpforms id="contact"]');
                    } else {
                        // Default HTML form
                        humanitarian_contact_form();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();

/**
 * Default contact form HTML
 */
function humanitarian_contact_form() {
    ?>
    <form class="contact-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
        <input type="hidden" name="action" value="humanitarian_contact_form">
        <?php wp_nonce_field('humanitarian_contact', 'contact_nonce'); ?>

        <div class="form-group">
            <label for="contact_name"><?php esc_html_e('Your Name', 'humanitarian'); ?> <span class="required">*</span></label>
            <input type="text" id="contact_name" name="contact_name" required>
        </div>

        <div class="form-group">
            <label for="contact_email"><?php esc_html_e('Email Address', 'humanitarian'); ?> <span class="required">*</span></label>
            <input type="email" id="contact_email" name="contact_email" required>
        </div>

        <div class="form-group">
            <label for="contact_subject"><?php esc_html_e('Subject', 'humanitarian'); ?></label>
            <input type="text" id="contact_subject" name="contact_subject">
        </div>

        <div class="form-group">
            <label for="contact_message"><?php esc_html_e('Message', 'humanitarian'); ?> <span class="required">*</span></label>
            <textarea id="contact_message" name="contact_message" rows="6" required></textarea>
        </div>

        <button type="submit" class="contact-form__submit">
            <?php esc_html_e('Send Message', 'humanitarian'); ?>
        </button>
    </form>
    <?php
}
?>
