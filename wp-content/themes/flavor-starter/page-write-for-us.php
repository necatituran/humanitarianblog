<?php
/**
 * Template Name: Write For Us
 * Author submission/contribution page
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main write-for-us-page">

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="section-badge"><?php _e('JOIN US', 'humanitarianblog'); ?></span>
                <h1><?php _e('Write for Us', 'humanitarianblog'); ?></h1>
                <p class="page-hero-lead"><?php _e('Share your expertise and perspective with our global audience. We welcome contributions from journalists, researchers, and humanitarian professionals.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

    <!-- What We're Looking For -->
    <section class="write-looking-for">
        <div class="container">
            <h2 class="section-title"><?php _e('What We\'re Looking For', 'humanitarianblog'); ?></h2>
            <div class="looking-for-grid">
                <div class="looking-for-card">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    </div>
                    <h3><?php _e('Field Reports', 'humanitarianblog'); ?></h3>
                    <p><?php _e('First-hand accounts from conflict zones, disaster areas, and humanitarian crises around the world.', 'humanitarianblog'); ?></p>
                </div>
                <div class="looking-for-card">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                    </div>
                    <h3><?php _e('In-Depth Analysis', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Expert analysis of humanitarian issues, policy implications, and root causes of crises.', 'humanitarianblog'); ?></p>
                </div>
                <div class="looking-for-card">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                    </div>
                    <h3><?php _e('Research & Data', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Data-driven articles and research findings on humanitarian trends and developments.', 'humanitarianblog'); ?></p>
                </div>
                <div class="looking-for-card">
                    <div class="card-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <h3><?php _e('Opinion & Commentary', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Thought-provoking perspectives on humanitarian policy, ethics, and global challenges.', 'humanitarianblog'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Submission Guidelines -->
    <section class="write-guidelines">
        <div class="container">
            <div class="guidelines-grid">
                <div class="guidelines-content">
                    <h2><?php _e('Submission Guidelines', 'humanitarianblog'); ?></h2>

                    <div class="guideline-section">
                        <h3><?php _e('Article Requirements', 'humanitarianblog'); ?></h3>
                        <ul>
                            <li><?php _e('Word count: 800-2,500 words depending on topic', 'humanitarianblog'); ?></li>
                            <li><?php _e('Original content not published elsewhere', 'humanitarianblog'); ?></li>
                            <li><?php _e('Well-researched with credible sources', 'humanitarianblog'); ?></li>
                            <li><?php _e('Clear, accessible language for general audience', 'humanitarianblog'); ?></li>
                            <li><?php _e('Relevant images with proper attribution (optional)', 'humanitarianblog'); ?></li>
                        </ul>
                    </div>

                    <div class="guideline-section">
                        <h3><?php _e('What to Include', 'humanitarianblog'); ?></h3>
                        <ul>
                            <li><?php _e('Compelling headline and subheading', 'humanitarianblog'); ?></li>
                            <li><?php _e('Brief author bio (50-100 words)', 'humanitarianblog'); ?></li>
                            <li><?php _e('Professional headshot', 'humanitarianblog'); ?></li>
                            <li><?php _e('Links to previous work (if available)', 'humanitarianblog'); ?></li>
                            <li><?php _e('Sources and references', 'humanitarianblog'); ?></li>
                        </ul>
                    </div>

                    <div class="guideline-section">
                        <h3><?php _e('What We Don\'t Accept', 'humanitarianblog'); ?></h3>
                        <ul>
                            <li><?php _e('Promotional or sponsored content', 'humanitarianblog'); ?></li>
                            <li><?php _e('Previously published articles', 'humanitarianblog'); ?></li>
                            <li><?php _e('Content with political bias or propaganda', 'humanitarianblog'); ?></li>
                            <li><?php _e('Articles without proper sourcing', 'humanitarianblog'); ?></li>
                        </ul>
                    </div>
                </div>

                <div class="guidelines-process">
                    <h3><?php _e('Submission Process', 'humanitarianblog'); ?></h3>
                    <div class="process-steps">
                        <div class="process-step">
                            <span class="step-number">1</span>
                            <div class="step-content">
                                <h4><?php _e('Pitch Your Idea', 'humanitarianblog'); ?></h4>
                                <p><?php _e('Send us a brief pitch (200-300 words) outlining your article idea and why it matters.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>
                        <div class="process-step">
                            <span class="step-number">2</span>
                            <div class="step-content">
                                <h4><?php _e('Editorial Review', 'humanitarianblog'); ?></h4>
                                <p><?php _e('Our editors will review your pitch and respond within 5-7 business days.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>
                        <div class="process-step">
                            <span class="step-number">3</span>
                            <div class="step-content">
                                <h4><?php _e('Write & Submit', 'humanitarianblog'); ?></h4>
                                <p><?php _e('If approved, write your article and submit via email or our contributor portal.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>
                        <div class="process-step">
                            <span class="step-number">4</span>
                            <div class="step-content">
                                <h4><?php _e('Edit & Publish', 'humanitarianblog'); ?></h4>
                                <p><?php _e('Our team will edit your piece and work with you on revisions before publication.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits -->
    <section class="write-benefits">
        <div class="container">
            <h2 class="section-title"><?php _e('Why Write for Us?', 'humanitarianblog'); ?></h2>
            <div class="benefits-grid">
                <div class="benefit-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    <h3><?php _e('Global Reach', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Your work reaches readers across 100+ countries interested in humanitarian issues.', 'humanitarianblog'); ?></p>
                </div>
                <div class="benefit-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                    <h3><?php _e('Professional Editing', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Work with experienced editors who will help polish your piece.', 'humanitarianblog'); ?></p>
                </div>
                <div class="benefit-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <h3><?php _e('Author Profile', 'humanitarianblog'); ?></h3>
                    <p><?php _e('Get a dedicated author page with your bio, photo, and all your articles.', 'humanitarianblog'); ?></p>
                </div>
                <div class="benefit-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" x2="12" y1="2" y2="15"/></svg>
                    <h3><?php _e('Social Promotion', 'humanitarianblog'); ?></h3>
                    <p><?php _e('We promote your work across our social media channels and newsletter.', 'humanitarianblog'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="write-cta">
        <div class="container">
            <div class="cta-box">
                <h2><?php _e('Ready to Submit?', 'humanitarianblog'); ?></h2>
                <p><?php _e('Send your pitch or completed article to our editorial team. We look forward to hearing from you.', 'humanitarianblog'); ?></p>
                <div class="cta-actions">
                    <a href="mailto:submissions@humanitarianblog.com" class="btn btn-primary btn-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <?php _e('Email Your Pitch', 'humanitarianblog'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-outline btn-lg"><?php _e('Ask a Question', 'humanitarianblog'); ?></a>
                </div>
                <p class="cta-email"><?php _e('Email:', 'humanitarianblog'); ?> <a href="mailto:submissions@humanitarianblog.com">submissions@humanitarianblog.com</a></p>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
