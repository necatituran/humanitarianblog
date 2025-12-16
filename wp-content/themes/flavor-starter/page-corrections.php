<?php
/**
 * Template Name: Corrections
 * Corrections and clarifications page
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main corrections-page">

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="section-badge"><?php _e('ACCOUNTABILITY', 'humanitarianblog'); ?></span>
                <h1><?php _e('Corrections & Clarifications', 'humanitarianblog'); ?></h1>
                <p class="page-hero-lead"><?php _e('We take accuracy seriously. When we make mistakes, we correct them promptly and transparently.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

    <!-- Policy Section -->
    <section class="corrections-policy">
        <div class="container">
            <div class="policy-box">
                <h2><?php _e('Our Corrections Policy', 'humanitarianblog'); ?></h2>
                <p><?php _e('Accuracy is fundamental to our journalism. When errors occur, we correct them as quickly as possible and note the correction prominently. We believe transparency about our mistakes builds trust with our readers.', 'humanitarianblog'); ?></p>
                <div class="policy-types">
                    <div class="policy-type">
                        <h3><?php _e('Corrections', 'humanitarianblog'); ?></h3>
                        <p><?php _e('Used when we have published incorrect information that materially affects the meaning of an article.', 'humanitarianblog'); ?></p>
                    </div>
                    <div class="policy-type">
                        <h3><?php _e('Clarifications', 'humanitarianblog'); ?></h3>
                        <p><?php _e('Used when information was technically accurate but potentially misleading or incomplete.', 'humanitarianblog'); ?></p>
                    </div>
                    <div class="policy-type">
                        <h3><?php _e('Updates', 'humanitarianblog'); ?></h3>
                        <p><?php _e('Used when new information becomes available that significantly changes the story.', 'humanitarianblog'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Corrections List -->
    <section class="corrections-list">
        <div class="container">
            <h2 class="section-title"><?php _e('Recent Corrections', 'humanitarianblog'); ?></h2>

            <div class="corrections-feed">
                <!-- Example correction entries - these would typically be dynamically generated -->
                <article class="correction-entry">
                    <div class="correction-meta">
                        <span class="correction-type correction-type--correction"><?php _e('Correction', 'humanitarianblog'); ?></span>
                        <time datetime="2024-12-15"><?php _e('December 15, 2024', 'humanitarianblog'); ?></time>
                    </div>
                    <div class="correction-content">
                        <h3><?php _e('Example Article Title', 'humanitarianblog'); ?></h3>
                        <p><?php _e('An earlier version of this article incorrectly stated... The article has been updated to reflect the correct information.', 'humanitarianblog'); ?></p>
                        <a href="#" class="correction-link"><?php _e('View corrected article', 'humanitarianblog'); ?> &rarr;</a>
                    </div>
                </article>

                <div class="no-corrections">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <p><?php _e('No recent corrections. We strive to get it right the first time.', 'humanitarianblog'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Report Error Section -->
    <section class="corrections-report">
        <div class="container">
            <div class="report-box">
                <h2><?php _e('Found an Error?', 'humanitarianblog'); ?></h2>
                <p><?php _e('We appreciate readers who help us maintain accuracy. If you believe you\'ve found an error in our reporting, please let us know.', 'humanitarianblog'); ?></p>

                <div class="report-methods">
                    <div class="report-method">
                        <h3><?php _e('Email Us', 'humanitarianblog'); ?></h3>
                        <p><?php _e('Send details to:', 'humanitarianblog'); ?></p>
                        <a href="mailto:corrections@humanitarianblog.com">corrections@humanitarianblog.com</a>
                    </div>
                    <div class="report-method">
                        <h3><?php _e('Include Details', 'humanitarianblog'); ?></h3>
                        <ul>
                            <li><?php _e('Article title and URL', 'humanitarianblog'); ?></li>
                            <li><?php _e('Description of the error', 'humanitarianblog'); ?></li>
                            <li><?php _e('Correct information with source', 'humanitarianblog'); ?></li>
                        </ul>
                    </div>
                </div>

                <p class="report-note"><?php _e('We review all submissions and will respond if we need additional information. Not all submissions result in corrections; we may determine the original information was accurate.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
