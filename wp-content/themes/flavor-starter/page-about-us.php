<?php
/**
 * Template Name: About Us
 * The about us page template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main about-page">

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="container">
            <div class="about-hero-content">
                <span class="section-badge"><?php _e('ABOUT US', 'humanitarianblog'); ?></span>
                <h1><?php _e('Voices for Humanity', 'humanitarianblog'); ?></h1>
                <p class="about-hero-lead"><?php _e('Independent journalism dedicated to covering humanitarian crises, conflict zones, and global emergencies. We believe every story deserves to be told.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="about-mission">
        <div class="container">
            <div class="mission-grid">
                <div class="mission-content">
                    <h2><?php _e('Our Mission', 'humanitarianblog'); ?></h2>
                    <p><?php _e('HumanitarianBlog was founded with a clear purpose: to shine a light on the world\'s most pressing humanitarian issues. In an age of information overload, we provide carefully researched, in-depth coverage of crises that often go unnoticed by mainstream media.', 'humanitarianblog'); ?></p>
                    <p><?php _e('We work with journalists, aid workers, and local reporters on the ground to bring you accurate, timely, and compassionate reporting from the frontlines of humanitarian emergencies.', 'humanitarianblog'); ?></p>
                </div>
                <div class="mission-stats">
                    <div class="stat-item">
                        <span class="stat-number">50+</span>
                        <span class="stat-label"><?php _e('Countries Covered', 'humanitarianblog'); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label"><?php _e('Stories Published', 'humanitarianblog'); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">100K+</span>
                        <span class="stat-label"><?php _e('Monthly Readers', 'humanitarianblog'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="about-values">
        <div class="container">
            <h2 class="section-title"><?php _e('Our Values', 'humanitarianblog'); ?></h2>
            <div class="values-grid">
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </div>
                    <h3><?php _e('Truth & Accuracy', 'humanitarianblog'); ?></h3>
                    <p><?php _e('We verify every story and present facts with integrity. Our commitment to accuracy ensures readers can trust our reporting.', 'humanitarianblog'); ?></p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3><?php _e('Human Dignity', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Every person\'s story matters. We report with compassion and respect for those affected by crises.', 'humanitarianblog'); ?></p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/>
                            <path d="M2 12h20"/>
                        </svg>
                    </div>
                    <h3><?php _e('Global Perspective', 'humanitarianblog'); ?></h3>
                    <p><?php _e('We cover stories from every corner of the world, ensuring no crisis is forgotten or overlooked.', 'humanitarianblog'); ?></p>
                </div>
                <div class="value-card">
                    <div class="value-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/>
                        </svg>
                    </div>
                    <h3><?php _e('Independence', 'humanitarianblog'); ?></h3>
                    <p><?php _e('We operate independently from political and commercial interests, ensuring unbiased coverage.', 'humanitarianblog'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="about-team">
        <div class="container">
            <div class="team-header">
                <h2><?php _e('Our Team', 'humanitarianblog'); ?></h2>
                <p><?php _e('A dedicated group of journalists, editors, and humanitarian professionals working together to bring you the stories that matter.', 'humanitarianblog'); ?></p>
            </div>
            <div class="team-grid">
                <div class="team-card">
                    <div class="team-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="5"/>
                            <path d="M20 21a8 8 0 1 0-16 0"/>
                        </svg>
                    </div>
                    <h3><?php _e('Editorial Team', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Experienced journalists and editors dedicated to quality reporting.', 'humanitarianblog'); ?></p>
                </div>
                <div class="team-card">
                    <div class="team-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3><?php _e('Field Correspondents', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Reporters on the ground in crisis zones around the world.', 'humanitarianblog'); ?></p>
                </div>
                <div class="team-card">
                    <div class="team-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <rect width="18" height="18" x="3" y="3" rx="2"/>
                            <path d="M3 9h18"/>
                            <path d="M9 21V9"/>
                        </svg>
                    </div>
                    <h3><?php _e('Research Team', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Analysts and researchers providing data-driven insights.', 'humanitarianblog'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="about-cta">
        <div class="container">
            <div class="cta-content">
                <h2><?php _e('Join Our Mission', 'humanitarianblog'); ?></h2>
                <p><?php _e('Support independent humanitarian journalism. Subscribe to our newsletter and stay informed about the crises that shape our world.', 'humanitarianblog'); ?></p>
                <a href="#newsletter" class="btn btn-amber btn-lg"><?php _e('Subscribe Now', 'humanitarianblog'); ?></a>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
