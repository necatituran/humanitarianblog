<?php
/**
 * Template Name: Editorial Standards
 * Editorial standards and ethics page
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main editorial-page">

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="section-badge"><?php _e('OUR STANDARDS', 'humanitarianblog'); ?></span>
                <h1><?php _e('Editorial Standards', 'humanitarianblog'); ?></h1>
                <p class="page-hero-lead"><?php _e('Our commitment to accuracy, fairness, and ethical journalism guides everything we publish.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="editorial-content">
        <div class="container">
            <div class="editorial-grid">
                <!-- Sidebar Navigation -->
                <aside class="editorial-nav">
                    <nav class="sticky-nav">
                        <h4><?php _e('On This Page', 'humanitarianblog'); ?></h4>
                        <ul>
                            <li><a href="#accuracy"><?php _e('Accuracy', 'humanitarianblog'); ?></a></li>
                            <li><a href="#independence"><?php _e('Independence', 'humanitarianblog'); ?></a></li>
                            <li><a href="#fairness"><?php _e('Fairness', 'humanitarianblog'); ?></a></li>
                            <li><a href="#sources"><?php _e('Sources', 'humanitarianblog'); ?></a></li>
                            <li><a href="#corrections"><?php _e('Corrections', 'humanitarianblog'); ?></a></li>
                            <li><a href="#conflicts"><?php _e('Conflicts of Interest', 'humanitarianblog'); ?></a></li>
                            <li><a href="#ethics"><?php _e('Ethical Guidelines', 'humanitarianblog'); ?></a></li>
                        </ul>
                    </nav>
                </aside>

                <!-- Main Content -->
                <div class="editorial-main">
                    <section id="accuracy" class="editorial-section">
                        <h2><?php _e('Accuracy', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Accuracy is the foundation of our journalism. We are committed to:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('Verifying all facts before publication through multiple sources', 'humanitarianblog'); ?></li>
                            <li><?php _e('Clearly distinguishing between news and opinion content', 'humanitarianblog'); ?></li>
                            <li><?php _e('Using primary sources whenever possible', 'humanitarianblog'); ?></li>
                            <li><?php _e('Providing context to help readers understand complex issues', 'humanitarianblog'); ?></li>
                            <li><?php _e('Acknowledging uncertainty when information cannot be fully verified', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="independence" class="editorial-section">
                        <h2><?php _e('Independence', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Editorial independence is essential to maintaining public trust:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('Our editorial decisions are made independently of advertisers, donors, and political interests', 'humanitarianblog'); ?></li>
                            <li><?php _e('We do not allow commercial considerations to influence our coverage', 'humanitarianblog'); ?></li>
                            <li><?php _e('Staff members are prohibited from accepting gifts that could compromise their independence', 'humanitarianblog'); ?></li>
                            <li><?php _e('We maintain a strict separation between editorial and business operations', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="fairness" class="editorial-section">
                        <h2><?php _e('Fairness', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Fair reporting means presenting all relevant perspectives:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('We seek comment from all parties mentioned in our reporting', 'humanitarianblog'); ?></li>
                            <li><?php _e('We present multiple viewpoints on controversial issues', 'humanitarianblog'); ?></li>
                            <li><?php _e('We avoid stereotypes and generalizations', 'humanitarianblog'); ?></li>
                            <li><?php _e('We give subjects of criticism an opportunity to respond', 'humanitarianblog'); ?></li>
                            <li><?php _e('We treat all communities and individuals with dignity', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="sources" class="editorial-section">
                        <h2><?php _e('Sources', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Our approach to sources ensures credible and reliable reporting:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('We prefer named sources and only use anonymous sources when necessary to protect safety', 'humanitarianblog'); ?></li>
                            <li><?php _e('We verify the credibility and reliability of all sources', 'humanitarianblog'); ?></li>
                            <li><?php _e('We are transparent about our sourcing methods', 'humanitarianblog'); ?></li>
                            <li><?php _e('We protect confidential sources and their information', 'humanitarianblog'); ?></li>
                            <li><?php _e('We cite and link to original sources when appropriate', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="corrections" class="editorial-section">
                        <h2><?php _e('Corrections Policy', 'humanitarianblog'); ?></h2>
                        <p><?php _e('We take errors seriously and correct them promptly:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('Factual errors are corrected as soon as they are discovered', 'humanitarianblog'); ?></li>
                            <li><?php _e('Corrections are clearly labeled and appended to the original article', 'humanitarianblog'); ?></li>
                            <li><?php _e('Significant corrections are noted at the top of articles', 'humanitarianblog'); ?></li>
                            <li><?php _e('We maintain a public corrections page for transparency', 'humanitarianblog'); ?></li>
                        </ul>
                        <p><a href="<?php echo esc_url(home_url('/corrections/')); ?>"><?php _e('View our Corrections page', 'humanitarianblog'); ?> &rarr;</a></p>
                    </section>

                    <section id="conflicts" class="editorial-section">
                        <h2><?php _e('Conflicts of Interest', 'humanitarianblog'); ?></h2>
                        <p><?php _e('We proactively manage and disclose potential conflicts:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('Writers do not cover topics where they have personal or financial interests', 'humanitarianblog'); ?></li>
                            <li><?php _e('Any potential conflicts are disclosed to editors and readers', 'humanitarianblog'); ?></li>
                            <li><?php _e('Staff members do not engage in political activities that could compromise their work', 'humanitarianblog'); ?></li>
                            <li><?php _e('We are transparent about funding sources and partnerships', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <section id="ethics" class="editorial-section">
                        <h2><?php _e('Ethical Guidelines', 'humanitarianblog'); ?></h2>
                        <p><?php _e('Our ethical standards guide our conduct:', 'humanitarianblog'); ?></p>
                        <ul>
                            <li><?php _e('We do not pay sources for information', 'humanitarianblog'); ?></li>
                            <li><?php _e('We identify ourselves as journalists when reporting', 'humanitarianblog'); ?></li>
                            <li><?php _e('We do not misrepresent ourselves to obtain information', 'humanitarianblog'); ?></li>
                            <li><?php _e('We respect the privacy of individuals, especially victims and minors', 'humanitarianblog'); ?></li>
                            <li><?php _e('We avoid causing unnecessary harm while pursuing the public interest', 'humanitarianblog'); ?></li>
                            <li><?php _e('We follow local laws and regulations in all jurisdictions where we operate', 'humanitarianblog'); ?></li>
                        </ul>
                    </section>

                    <div class="editorial-contact">
                        <h3><?php _e('Questions or Concerns?', 'humanitarianblog'); ?></h3>
                        <p><?php _e('If you have questions about our editorial standards or wish to report a concern, please contact our editorial team.', 'humanitarianblog'); ?></p>
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary"><?php _e('Contact Editorial Team', 'humanitarianblog'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
