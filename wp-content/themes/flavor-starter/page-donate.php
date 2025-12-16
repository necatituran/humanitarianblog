<?php
/**
 * Template Name: Donate
 * Support/Donation page template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main donate-page">

    <!-- Hero Section -->
    <section class="page-hero page-hero--accent">
        <div class="container">
            <div class="page-hero-content">
                <span class="section-badge"><?php _e('SUPPORT US', 'humanitarianblog'); ?></span>
                <h1><?php _e('Support Independent Journalism', 'humanitarianblog'); ?></h1>
                <p class="page-hero-lead"><?php _e('Your contribution helps us continue reporting on humanitarian crises around the world. Every donation makes a difference.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

    <!-- Why Support Section -->
    <section class="donate-why">
        <div class="container">
            <div class="donate-why-grid">
                <div class="donate-why-content">
                    <h2><?php _e('Why Your Support Matters', 'humanitarianblog'); ?></h2>
                    <p><?php _e('In an era where news is increasingly driven by clicks and advertising revenue, independent journalism is more important than ever. We rely on reader support to:', 'humanitarianblog'); ?></p>
                    <ul class="donate-benefits">
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <?php _e('Send reporters to conflict zones and disaster areas', 'humanitarianblog'); ?>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <?php _e('Maintain our commitment to ad-free, unbiased reporting', 'humanitarianblog'); ?>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <?php _e('Invest in investigative journalism and in-depth analysis', 'humanitarianblog'); ?>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <?php _e('Keep our content free and accessible to everyone', 'humanitarianblog'); ?>
                        </li>
                        <li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                            <?php _e('Support local journalists in underreported regions', 'humanitarianblog'); ?>
                        </li>
                    </ul>
                </div>
                <div class="donate-impact">
                    <h3><?php _e('Your Impact', 'humanitarianblog'); ?></h3>
                    <div class="impact-items">
                        <div class="impact-item">
                            <span class="impact-amount">$10</span>
                            <span class="impact-desc"><?php _e('Provides translation for one story', 'humanitarianblog'); ?></span>
                        </div>
                        <div class="impact-item">
                            <span class="impact-amount">$50</span>
                            <span class="impact-desc"><?php _e('Supports a local reporter for a day', 'humanitarianblog'); ?></span>
                        </div>
                        <div class="impact-item">
                            <span class="impact-amount">$100</span>
                            <span class="impact-desc"><?php _e('Funds an investigative report', 'humanitarianblog'); ?></span>
                        </div>
                        <div class="impact-item">
                            <span class="impact-amount">$500</span>
                            <span class="impact-desc"><?php _e('Supports field reporting for a week', 'humanitarianblog'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Donation Options -->
    <section class="donate-options">
        <div class="container">
            <h2 class="section-title"><?php _e('Ways to Support', 'humanitarianblog'); ?></h2>
            <div class="donate-options-grid">
                <div class="donate-option-card">
                    <div class="donate-option-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
                    </div>
                    <h3><?php _e('One-Time Donation', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Make a single contribution of any amount to support our journalism.', 'humanitarianblog'); ?></p>
                    <a href="#" class="btn btn-primary"><?php _e('Donate Now', 'humanitarianblog'); ?></a>
                </div>
                <div class="donate-option-card donate-option-card--featured">
                    <span class="featured-badge"><?php _e('Recommended', 'humanitarianblog'); ?></span>
                    <div class="donate-option-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/><path d="m9 12 2 2 4-4"/></svg>
                    </div>
                    <h3><?php _e('Monthly Supporter', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Become a sustaining member with a monthly contribution. Cancel anytime.', 'humanitarianblog'); ?></p>
                    <a href="#" class="btn btn-amber"><?php _e('Join Monthly', 'humanitarianblog'); ?></a>
                </div>
                <div class="donate-option-card">
                    <div class="donate-option-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    </div>
                    <h3><?php _e('Corporate Sponsorship', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Partner with us to support humanitarian journalism at scale.', 'humanitarianblog'); ?></p>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-outline"><?php _e('Contact Us', 'humanitarianblog'); ?></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Other Ways to Help -->
    <section class="donate-other">
        <div class="container">
            <h2 class="section-title"><?php _e('Other Ways to Help', 'humanitarianblog'); ?></h2>
            <div class="other-ways-grid">
                <div class="other-way">
                    <h3><?php _e('Share Our Stories', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Spread awareness by sharing our articles on social media and with your network.', 'humanitarianblog'); ?></p>
                </div>
                <div class="other-way">
                    <h3><?php _e('Subscribe to Newsletter', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Stay informed and help us grow our community of engaged readers.', 'humanitarianblog'); ?></p>
                </div>
                <div class="other-way">
                    <h3><?php _e('Contribute Stories', 'humanitarianblog'); ?></h3>
                    <p><?php _e('If you\'re a journalist or expert, consider writing for us.', 'humanitarianblog'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Transparency Section -->
    <section class="donate-transparency">
        <div class="container">
            <div class="transparency-box">
                <h2><?php _e('Our Commitment to Transparency', 'humanitarianblog'); ?></h2>
                <p><?php _e('We believe in complete transparency about how donations are used. Every contribution goes directly toward journalism and operations. We publish annual reports detailing our finances and impact.', 'humanitarianblog'); ?></p>
                <div class="transparency-stats">
                    <div class="trans-stat">
                        <span class="trans-number">85%</span>
                        <span class="trans-label"><?php _e('Journalism & Reporting', 'humanitarianblog'); ?></span>
                    </div>
                    <div class="trans-stat">
                        <span class="trans-number">10%</span>
                        <span class="trans-label"><?php _e('Operations & Technology', 'humanitarianblog'); ?></span>
                    </div>
                    <div class="trans-stat">
                        <span class="trans-number">5%</span>
                        <span class="trans-label"><?php _e('Outreach & Growth', 'humanitarianblog'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
