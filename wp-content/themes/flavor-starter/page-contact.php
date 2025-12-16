<?php
/**
 * Template Name: Contact
 * The contact page template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main contact-page">

    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <span class="section-badge"><?php _e('CONTACT', 'humanitarianblog'); ?></span>
            <h1><?php _e('Get in Touch', 'humanitarianblog'); ?></h1>
            <p class="contact-hero-lead"><?php _e('Have a story tip, feedback, or want to collaborate? We\'d love to hear from you.', 'humanitarianblog'); ?></p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-content">
        <div class="container">
            <div class="contact-grid">

                <!-- Contact Form -->
                <div class="contact-form-wrapper">
                    <h2><?php _e('Send Us a Message', 'humanitarianblog'); ?></h2>
                    <form class="contact-form" action="#" method="post">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-name"><?php _e('Your Name', 'humanitarianblog'); ?> <span class="required">*</span></label>
                                <input type="text" id="contact-name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="contact-email"><?php _e('Email Address', 'humanitarianblog'); ?> <span class="required">*</span></label>
                                <input type="email" id="contact-email" name="email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact-subject"><?php _e('Subject', 'humanitarianblog'); ?></label>
                            <select id="contact-subject" name="subject">
                                <option value="general"><?php _e('General Inquiry', 'humanitarianblog'); ?></option>
                                <option value="story"><?php _e('Story Tip / News Lead', 'humanitarianblog'); ?></option>
                                <option value="feedback"><?php _e('Feedback', 'humanitarianblog'); ?></option>
                                <option value="collaboration"><?php _e('Collaboration / Partnership', 'humanitarianblog'); ?></option>
                                <option value="press"><?php _e('Press Inquiry', 'humanitarianblog'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="contact-message"><?php _e('Your Message', 'humanitarianblog'); ?> <span class="required">*</span></label>
                            <textarea id="contact-message" name="message" rows="6" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <?php _e('Send Message', 'humanitarianblog'); ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m22 2-7 20-4-9-9-4Z"/>
                                <path d="M22 2 11 13"/>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Contact Info Sidebar -->
                <aside class="contact-sidebar">
                    <div class="contact-info-card">
                        <h3><?php _e('Contact Information', 'humanitarianblog'); ?></h3>
                        <ul class="contact-info-list">
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect width="20" height="16" x="2" y="4" rx="2"/>
                                    <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                                </svg>
                                <div>
                                    <strong><?php _e('Email', 'humanitarianblog'); ?></strong>
                                    <a href="mailto:info@humanitarianblog.org">info@humanitarianblog.org</a>
                                </div>
                            </li>
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/>
                                    <circle cx="12" cy="10" r="3"/>
                                </svg>
                                <div>
                                    <strong><?php _e('Coverage', 'humanitarianblog'); ?></strong>
                                    <span><?php _e('Global - Remote Team', 'humanitarianblog'); ?></span>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="contact-info-card">
                        <h3><?php _e('For Urgent Tips', 'humanitarianblog'); ?></h3>
                        <p><?php _e('If you have an urgent story tip or breaking news from a crisis zone, please reach out directly:', 'humanitarianblog'); ?></p>
                        <a href="mailto:tips@humanitarianblog.org" class="tips-email">tips@humanitarianblog.org</a>
                        <p class="tips-note"><?php _e('Confidential sources protected.', 'humanitarianblog'); ?></p>
                    </div>

                    <div class="contact-info-card">
                        <h3><?php _e('Follow Us', 'humanitarianblog'); ?></h3>
                        <div class="contact-social">
                            <a href="#" aria-label="Twitter">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="Facebook">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" aria-label="LinkedIn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </aside>

            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="contact-faq">
        <div class="container">
            <h2><?php _e('Frequently Asked Questions', 'humanitarianblog'); ?></h2>
            <div class="faq-grid">
                <div class="faq-item">
                    <h3><?php _e('How can I submit a story?', 'humanitarianblog'); ?></h3>
                    <p><?php _e('You can submit story tips or pitches through our contact form or directly via email. Please include as much detail as possible, including any documentation or sources.', 'humanitarianblog'); ?></p>
                </div>
                <div class="faq-item">
                    <h3><?php _e('Do you accept guest contributions?', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Yes, we welcome contributions from experienced journalists, aid workers, and experts in humanitarian fields. Please send your pitch with relevant credentials.', 'humanitarianblog'); ?></p>
                </div>
                <div class="faq-item">
                    <h3><?php _e('How do you protect sources?', 'humanitarianblog'); ?></h3>
                    <p><?php _e('We take source protection very seriously. All communications can be encrypted upon request, and we never reveal confidential sources without explicit permission.', 'humanitarianblog'); ?></p>
                </div>
                <div class="faq-item">
                    <h3><?php _e('Can I republish your content?', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Some of our content is available for republishing under certain conditions. Please contact us for syndication and licensing inquiries.', 'humanitarianblog'); ?></p>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
