<?php
/**
 * Template Name: Cookie Policy
 * The cookie policy page template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main legal-page">

    <div class="legal-page-header">
        <div class="container">
            <h1 class="legal-page-title"><?php _e('Cookie Policy', 'humanitarianblog'); ?></h1>
            <p class="legal-page-updated"><?php _e('Last updated:', 'humanitarianblog'); ?> <?php echo date_i18n('F j, Y'); ?></p>
        </div>
    </div>

    <div class="legal-page-content">
        <div class="container">
            <div class="legal-content-wrapper">

                <nav class="legal-toc">
                    <h3><?php _e('Contents', 'humanitarianblog'); ?></h3>
                    <ul>
                        <li><a href="#what-are-cookies"><?php _e('What Are Cookies', 'humanitarianblog'); ?></a></li>
                        <li><a href="#how-we-use"><?php _e('How We Use Cookies', 'humanitarianblog'); ?></a></li>
                        <li><a href="#types-of-cookies"><?php _e('Types of Cookies', 'humanitarianblog'); ?></a></li>
                        <li><a href="#third-party"><?php _e('Third-Party Cookies', 'humanitarianblog'); ?></a></li>
                        <li><a href="#managing-cookies"><?php _e('Managing Cookies', 'humanitarianblog'); ?></a></li>
                        <li><a href="#your-choices"><?php _e('Your Choices', 'humanitarianblog'); ?></a></li>
                        <li><a href="#contact"><?php _e('Contact Us', 'humanitarianblog'); ?></a></li>
                    </ul>
                </nav>

                <div class="legal-main-content">

                    <section id="what-are-cookies">
                        <h2><?php _e('What Are Cookies', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work more efficiently and provide information to website owners.', 'humanitarianblog'); ?></p>
                        <p><?php _e('Cookies allow a website to recognize your device and remember certain information about your visits, such as your preferences and settings.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="how-we-use">
                        <h2><?php _e('How We Use Cookies', 'humanitarianblog'); ?></h2>
                        <p><?php _e('HumanitarianBlog uses cookies for a variety of purposes:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('To ensure the website functions properly', 'humanitarianblog'); ?></li>
                            <li><?php _e('To remember your preferences and settings', 'humanitarianblog'); ?></li>
                            <li><?php _e('To analyze how you use our website', 'humanitarianblog'); ?></li>
                            <li><?php _e('To improve our website and services', 'humanitarianblog'); ?></li>
                            <li><?php _e('To deliver relevant content based on your interests', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="types-of-cookies">
                        <h2><?php _e('Types of Cookies We Use', 'humanitarianblog'); ?></h2>

                        <h3><?php _e('Essential Cookies', 'humanitarianblog'); ?></h3>
                        <p><?php _e('These cookies are necessary for the website to function properly. They enable basic functions like page navigation and access to secure areas. The website cannot function properly without these cookies.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Analytics Cookies', 'humanitarianblog'); ?></h3>
                        <p><?php _e('These cookies help us understand how visitors interact with our website by collecting and reporting information anonymously. This helps us improve our website.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Functional Cookies', 'humanitarianblog'); ?></h3>
                        <p><?php _e('These cookies enable enhanced functionality and personalization, such as remembering your language preferences or login details.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Preference Cookies', 'humanitarianblog'); ?></h3>
                        <p><?php _e('These cookies remember choices you make to improve your experience, such as your preferred language or region.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="third-party">
                        <h2><?php _e('Third-Party Cookies', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Some cookies on our website are set by third-party services that appear on our pages. We use the following third-party services:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><strong><?php _e('Google Analytics:', 'humanitarianblog'); ?></strong> <?php _e('To analyze website traffic and usage patterns', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('Social Media:', 'humanitarianblog'); ?></strong> <?php _e('To enable social sharing features', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('YouTube:', 'humanitarianblog'); ?></strong> <?php _e('To embed video content', 'humanitarianblog'); ?></li>
                        </ul>
                        <p><?php _e('These third parties may use cookies to track your browsing activity across different websites.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="managing-cookies">
                        <h2><?php _e('Managing Cookies', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Most web browsers allow you to control cookies through their settings preferences. However, if you limit the ability of websites to set cookies, you may worsen your overall user experience.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Browser Settings', 'humanitarianblog'); ?></h3>
                        <p><?php _e('You can manage cookies through your browser settings. Here\'s how to access cookie settings in popular browsers:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><strong>Chrome:</strong> <?php _e('Settings > Privacy and Security > Cookies', 'humanitarianblog'); ?></li>
                            <li><strong>Firefox:</strong> <?php _e('Options > Privacy & Security > Cookies', 'humanitarianblog'); ?></li>
                            <li><strong>Safari:</strong> <?php _e('Preferences > Privacy > Cookies', 'humanitarianblog'); ?></li>
                            <li><strong>Edge:</strong> <?php _e('Settings > Privacy & Services > Cookies', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="your-choices">
                        <h2><?php _e('Your Choices', 'humanitarianblog'); ?></h2>
                        <p><?php _e('You have several options for managing cookies:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><strong><?php _e('Accept All:', 'humanitarianblog'); ?></strong> <?php _e('Allow all cookies for the best site experience', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('Essential Only:', 'humanitarianblog'); ?></strong> <?php _e('Allow only necessary cookies for basic functionality', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('Reject All:', 'humanitarianblog'); ?></strong> <?php _e('Block all cookies (may affect site functionality)', 'humanitarianblog'); ?></li>
                            <li><strong><?php _e('Custom:', 'humanitarianblog'); ?></strong> <?php _e('Choose which types of cookies to allow', 'humanitarianblog'); ?></li>
                        </ul>
                        <p><?php _e('Please note that blocking some types of cookies may impact your experience on our website and the services we are able to offer.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="contact">
                        <h2><?php _e('Contact Us', 'humanitarianblog'); ?></h2>
                        <p><?php _e('If you have any questions about our use of cookies, please contact us:', 'humanitarianblog'); ?></p>
                        <p>
                            <strong><?php _e('Email:', 'humanitarianblog'); ?></strong> privacy@humanitarianblog.org<br>
                            <strong><?php _e('Address:', 'humanitarianblog'); ?></strong> HumanitarianBlog, Global Coverage
                        </p>
                        <p><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php _e('View our Privacy Policy', 'humanitarianblog'); ?></a></p>
                    </section>

                </div>
            </div>
        </div>
    </div>

</main>

<?php
get_footer();
