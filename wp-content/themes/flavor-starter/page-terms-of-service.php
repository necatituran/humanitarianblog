<?php
/**
 * Template Name: Terms of Service
 * The terms of service page template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main legal-page">

    <div class="legal-page-header">
        <div class="container">
            <h1 class="legal-page-title"><?php _e('Terms of Service', 'humanitarianblog'); ?></h1>
            <p class="legal-page-updated"><?php _e('Last updated:', 'humanitarianblog'); ?> <?php echo date_i18n('F j, Y'); ?></p>
        </div>
    </div>

    <div class="legal-page-content">
        <div class="container">
            <div class="legal-content-wrapper">

                <nav class="legal-toc">
                    <h3><?php _e('Contents', 'humanitarianblog'); ?></h3>
                    <ul>
                        <li><a href="#acceptance"><?php _e('Acceptance of Terms', 'humanitarianblog'); ?></a></li>
                        <li><a href="#use-of-site"><?php _e('Use of Site', 'humanitarianblog'); ?></a></li>
                        <li><a href="#user-content"><?php _e('User Content', 'humanitarianblog'); ?></a></li>
                        <li><a href="#intellectual-property"><?php _e('Intellectual Property', 'humanitarianblog'); ?></a></li>
                        <li><a href="#disclaimers"><?php _e('Disclaimers', 'humanitarianblog'); ?></a></li>
                        <li><a href="#limitation"><?php _e('Limitation of Liability', 'humanitarianblog'); ?></a></li>
                        <li><a href="#changes"><?php _e('Changes to Terms', 'humanitarianblog'); ?></a></li>
                        <li><a href="#contact"><?php _e('Contact Us', 'humanitarianblog'); ?></a></li>
                    </ul>
                </nav>

                <div class="legal-main-content">

                    <section id="acceptance">
                        <h2><?php _e('Acceptance of Terms', 'humanitarianblog'); ?></h2>
                        <p><?php _e('By accessing and using HumanitarianBlog ("the Site"), you accept and agree to be bound by these Terms of Service and our Privacy Policy. If you do not agree to these terms, please do not use our Site.', 'humanitarianblog'); ?></p>
                        <p><?php _e('These terms apply to all visitors, users, and others who access or use the Site. By using the Site, you represent that you are at least 13 years of age.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="use-of-site">
                        <h2><?php _e('Use of Site', 'humanitarianblog'); ?></h2>
                        <p><?php _e('You agree to use the Site only for lawful purposes and in a way that does not infringe the rights of, restrict, or inhibit anyone else\'s use and enjoyment of the Site.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Prohibited Activities', 'humanitarianblog'); ?></h3>
                        <p><?php _e('You may not:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('Use the Site in any way that violates applicable laws or regulations', 'humanitarianblog'); ?></li>
                            <li><?php _e('Transmit any harmful, threatening, abusive, or defamatory content', 'humanitarianblog'); ?></li>
                            <li><?php _e('Attempt to gain unauthorized access to any portion of the Site', 'humanitarianblog'); ?></li>
                            <li><?php _e('Use automated systems to extract data from the Site without permission', 'humanitarianblog'); ?></li>
                            <li><?php _e('Impersonate any person or entity or misrepresent your affiliation', 'humanitarianblog'); ?></li>
                            <li><?php _e('Interfere with or disrupt the Site or servers connected to the Site', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="user-content">
                        <h2><?php _e('User Content', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Our Site may allow you to post, link, store, share, and otherwise make available certain information, text, or other material ("User Content"). You are responsible for the User Content that you post, including its legality, reliability, and appropriateness.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Content Standards', 'humanitarianblog'); ?></h3>
                        <p><?php _e('By posting User Content, you warrant that:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('You own or have the right to use the content', 'humanitarianblog'); ?></li>
                            <li><?php _e('The content does not violate any third party\'s rights', 'humanitarianblog'); ?></li>
                            <li><?php _e('The content is accurate and not misleading', 'humanitarianblog'); ?></li>
                            <li><?php _e('The content complies with these Terms and applicable laws', 'humanitarianblog'); ?></li>
                        </ul>

                        <p><?php _e('We reserve the right to remove any User Content at our discretion without notice.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="intellectual-property">
                        <h2><?php _e('Intellectual Property', 'humanitarianblog'); ?></h2>
                        <p><?php _e('The Site and its original content, features, and functionality are owned by HumanitarianBlog and are protected by international copyright, trademark, and other intellectual property laws.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Our Content', 'humanitarianblog'); ?></h3>
                        <p><?php _e('Unless otherwise indicated, all materials on this Site, including articles, images, graphics, and logos, are the property of HumanitarianBlog or our content suppliers and are protected by copyright laws.', 'humanitarianblog'); ?></p>

                        <h3><?php _e('Limited License', 'humanitarianblog'); ?></h3>
                        <p><?php _e('You may view, download, and print pages from the Site for your personal, non-commercial use, subject to the restrictions set out in these Terms. You must not republish, sell, or redistribute our content without express permission.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="disclaimers">
                        <h2><?php _e('Disclaimers', 'humanitarianblog'); ?></h2>
                        <p><?php _e('The Site is provided on an "as is" and "as available" basis. We make no representations or warranties of any kind, express or implied, regarding:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('The operation or availability of the Site', 'humanitarianblog'); ?></li>
                            <li><?php _e('The accuracy, reliability, or completeness of any content', 'humanitarianblog'); ?></li>
                            <li><?php _e('That the Site will be uninterrupted or error-free', 'humanitarianblog'); ?></li>
                            <li><?php _e('That defects will be corrected', 'humanitarianblog'); ?></li>
                        </ul>
                        <p><?php _e('The information provided on this Site is for general informational purposes only and should not be considered professional advice.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="limitation">
                        <h2><?php _e('Limitation of Liability', 'humanitarianblog'); ?></h2>
                        <p><?php _e('To the fullest extent permitted by applicable law, HumanitarianBlog shall not be liable for any indirect, incidental, special, consequential, or punitive damages, including but not limited to:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('Loss of profits, data, or goodwill', 'humanitarianblog'); ?></li>
                            <li><?php _e('Service interruption or computer damage', 'humanitarianblog'); ?></li>
                            <li><?php _e('Any damages arising from your use of or inability to use the Site', 'humanitarianblog'); ?></li>
                            <li><?php _e('Any content obtained from the Site', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="changes">
                        <h2><?php _e('Changes to Terms', 'humanitarianblog'); ?></h2>
                        <p><?php _e('We reserve the right to modify or replace these Terms at any time at our sole discretion. If a revision is material, we will provide at least 30 days\' notice prior to any new terms taking effect.', 'humanitarianblog'); ?></p>
                        <p><?php _e('By continuing to access or use our Site after any revisions become effective, you agree to be bound by the revised terms.', 'humanitarianblog'); ?></p>
                    </section>

                    <section id="contact">
                        <h2><?php _e('Contact Us', 'humanitarianblog'); ?></h2>
                        <p><?php _e('If you have any questions about these Terms of Service, please contact us:', 'humanitarianblog'); ?></p>
                        <p>
                            <strong><?php _e('Email:', 'humanitarianblog'); ?></strong> legal@humanitarianblog.org<br>
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
