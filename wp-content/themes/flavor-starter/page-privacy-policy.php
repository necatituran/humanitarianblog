<?php
/**
 * Template Name: Privacy Policy
 * The privacy policy page template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main legal-page">

    <div class="legal-page-header">
        <div class="container">
            <h1 class="legal-page-title"><?php _e('Privacy Policy', 'humanitarianblog'); ?></h1>
            <p class="legal-page-updated"><?php _e('Last updated:', 'humanitarianblog'); ?> <?php echo date_i18n('F j, Y'); ?></p>
        </div>
    </div>

    <div class="legal-page-content">
        <div class="container">
            <div class="legal-content-wrapper">

                <nav class="legal-toc">
                    <h3><?php _e('Contents', 'humanitarianblog'); ?></h3>
                    <ul>
                        <li><a href="#introduction"><?php _e('Introduction', 'humanitarianblog'); ?></a></li>
                        <li><a href="#information-collected"><?php _e('Information We Collect', 'humanitarianblog'); ?></a></li>
                        <li><a href="#how-we-use"><?php _e('How We Use Information', 'humanitarianblog'); ?></a></li>
                        <li><a href="#cookies"><?php _e('Cookies & Tracking', 'humanitarianblog'); ?></a></li>
                        <li><a href="#data-sharing"><?php _e('Data Sharing', 'humanitarianblog'); ?></a></li>
                        <li><a href="#your-rights"><?php _e('Your Rights', 'humanitarianblog'); ?></a></li>
                        <li><a href="#contact"><?php _e('Contact Us', 'humanitarianblog'); ?></a></li>
                    </ul>
                </nav>

                <div class="legal-main-content">

                    <section id="introduction">
                        <h2><?php _e('Introduction', 'humanitarianblog'); ?></h2>
                        <p><?php _e('HumanitarianBlog ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.', 'humanitarianblog'); ?></p>
                        <p><?php _e('We reserve the right to make changes to this Privacy Policy at any time and for any reason. We will alert you about any changes by updating the "Last updated" date of this Privacy Policy.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="information-collected">
                        <h2><?php _e('Information We Collect', 'humanitarianblog'); ?></h2>

                        <h3><?php _e('Personal Data', 'humanitarianblog'); ?></h3>
                        <p><?php _e('We may collect personal information that you voluntarily provide to us when you:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('Subscribe to our newsletter', 'humanitarianblog'); ?></li>
                            <li><?php _e('Leave comments on articles', 'humanitarianblog'); ?></li>
                            <li><?php _e('Contact us through our forms', 'humanitarianblog'); ?></li>
                            <li><?php _e('Create an account on our website', 'humanitarianblog'); ?></li>
                        </ul>
                        <p><?php _e('This information may include your name, email address, and any other information you choose to provide.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Automatically Collected Data', 'humanitarianblog'); ?></h3>
                        <p><?php _e('When you access our website, we automatically collect certain information about your device, including:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('IP address', 'humanitarianblog'); ?></li>
                            <li><?php _e('Browser type and version', 'humanitarianblog'); ?></li>
                            <li><?php _e('Operating system', 'humanitarianblog'); ?></li>
                            <li><?php _e('Pages visited and time spent', 'humanitarianblog'); ?></li>
                            <li><?php _e('Referring website addresses', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="how-we-use">
                        <h2><?php _e('How We Use Your Information', 'humanitarianblog'); ?></h2>
                        <p><?php _e('We use the information we collect to:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('Deliver and improve our content and services', 'humanitarianblog'); ?></li>
                            <li><?php _e('Send you newsletters and updates (with your consent)', 'humanitarianblog'); ?></li>
                            <li><?php _e('Respond to your inquiries and comments', 'humanitarianblog'); ?></li>
                            <li><?php _e('Analyze website usage to improve user experience', 'humanitarianblog'); ?></li>
                            <li><?php _e('Protect against spam and malicious activity', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="cookies">
                        <h2><?php _e('Cookies & Tracking Technologies', 'humanitarianblog'); ?></h2>
                        <p><?php _e('We use cookies and similar tracking technologies to track activity on our website and hold certain information. Cookies are files with small amounts of data that are stored on your device.', 'humanitarianblog'); ?></p>
                        <p><?php _e('You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our website.', 'humanitarianblog'); ?></p>
                        <p><a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>"><?php _e('Learn more about our Cookie Policy', 'humanitarianblog'); ?></a></p>
                    </section>

                    <section id="data-sharing">
                        <h2><?php _e('Data Sharing & Disclosure', 'humanitarianblog'); ?></h2>
                        <p><?php _e('We do not sell, trade, or rent your personal information to third parties. We may share your information in the following situations:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('With service providers who assist in operating our website', 'humanitarianblog'); ?></li>
                            <li><?php _e('To comply with legal obligations', 'humanitarianblog'); ?></li>
                            <li><?php _e('To protect our rights and prevent fraud', 'humanitarianblog'); ?></li>
                            <li><?php _e('With your explicit consent', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="your-rights">
                        <h2><?php _e('Your Rights', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Depending on your location, you may have certain rights regarding your personal data:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><strong><?php _e('Access:', 'humanitarianblog'); ?></strong> <?php _e('Request access to your personal data', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('Rectification:', 'humanitarianblog'); ?></strong> <?php _e('Request correction of inaccurate data', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('Erasure:', 'humanitarianblog'); ?></strong> <?php _e('Request deletion of your personal data', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('Portability:', 'humanitarianblog'); ?></strong> <?php _e('Request transfer of your data', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('Opt-out:', 'humanitarianblog'); ?></strong> <?php _e('Unsubscribe from marketing communications', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="contact">
                        <h2><?php _e('Contact Us', 'humanitarianblog'); ?></h2>
                        <p><?php _e('If you have questions about this Privacy Policy or our data practices, please contact us:', 'humanitarianblog'); ?></p>
                        <p>
                            <strong><?php _e('Email:', 'humanitarianblog'); ?></strong> privacy@humanitarianblog.org<br>
                            <strong><?php _e('Address:', 'humanitarianblog'); ?></strong> HumanitarianBlog, Global Coverage
                        </p>
                    </section>

                </div>
            </div>
        </div>
    </div>

</main>

<?php
get_footer();
