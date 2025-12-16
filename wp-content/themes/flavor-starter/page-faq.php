<?php
/**
 * Template Name: FAQ
 * Frequently Asked Questions page
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main faq-page">

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="section-badge"><?php _e('HELP', 'humanitarianblog'); ?></span>
                <h1><?php _e('Frequently Asked Questions', 'humanitarianblog'); ?></h1>
                <p class="page-hero-lead"><?php _e('Find answers to common questions about our journalism, website, and how to get involved.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

    <!-- FAQ Categories -->
    <section class="faq-content">
        <div class="container">
            <div class="faq-grid">
                <!-- Sidebar Navigation -->
                <aside class="faq-nav">
                    <nav class="sticky-nav">
                        <h4><?php _e('Categories', 'humanitarianblog'); ?></h4>
                        <ul>
                            <li><a href="#about" class="active"><?php _e('About Us', 'humanitarianblog'); ?></a></li>
                            <li><a href="#content"><?php _e('Our Content', 'humanitarianblog'); ?></a></li>
                            <li><a href="#contributing"><?php _e('Contributing', 'humanitarianblog'); ?></a></li>
                            <li><a href="#account"><?php _e('Account & Features', 'humanitarianblog'); ?></a></li>
                            <li><a href="#support"><?php _e('Support & Contact', 'humanitarianblog'); ?></a></li>
                        </ul>
                    </nav>
                </aside>

                <!-- FAQ Items -->
                <div class="faq-main">
                    <!-- About Us -->
                    <section id="about" class="faq-section">
                        <h2><?php _e('About Us', 'humanitarianblog'); ?></h2>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('What is HumanitarianBlog?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('HumanitarianBlog is an independent journalism platform dedicated to covering humanitarian crises, conflict zones, and global emergencies. We provide in-depth reporting on issues often overlooked by mainstream media.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('Who funds HumanitarianBlog?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('We are funded primarily through reader donations and grants from journalism foundations. We do not accept advertising revenue or corporate sponsorships that could compromise our editorial independence.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('Is HumanitarianBlog politically affiliated?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('No. We are strictly non-partisan and do not align with any political party or ideology. Our editorial decisions are based solely on newsworthiness and public interest, not political considerations.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>
                    </section>

                    <!-- Our Content -->
                    <section id="content" class="faq-section">
                        <h2><?php _e('Our Content', 'humanitarianblog'); ?></h2>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How do you verify your reporting?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('All stories go through a rigorous fact-checking process. We verify information through multiple independent sources, consult with subject matter experts, and cross-reference claims with official data when available. Read our Editorial Standards for more details.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('Can I republish your articles?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('Some of our content is available for republication under Creative Commons licensing. Please contact us at permissions@humanitarianblog.com for specific requests. All republished content must include proper attribution and a link to the original article.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('What\'s the difference between News and Opinion articles?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('News articles present factual reporting without editorial commentary. Opinion pieces, clearly labeled, represent the views of individual authors and do not necessarily reflect the position of HumanitarianBlog.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>
                    </section>

                    <!-- Contributing -->
                    <section id="contributing" class="faq-section">
                        <h2><?php _e('Contributing', 'humanitarianblog'); ?></h2>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How can I write for HumanitarianBlog?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('We welcome contributions from journalists, researchers, and humanitarian professionals. Visit our Write for Us page to learn about submission guidelines and how to pitch your story idea.', 'humanitarianblog'); ?></p>
                                <p><a href="<?php echo esc_url(home_url('/write-for-us/')); ?>"><?php _e('View submission guidelines', 'humanitarianblog'); ?> &rarr;</a></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('Do you pay contributors?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('We offer competitive compensation for commissioned articles and exclusive investigations. Guest opinion pieces are typically unpaid but provide exposure to our global audience. Payment terms are discussed during the pitch process.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How can I submit a news tip?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('You can submit news tips through our Contact page. For sensitive information, we offer secure communication channels. All tips are reviewed by our editorial team and sources are protected.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>
                    </section>

                    <!-- Account & Features -->
                    <section id="account" class="faq-section">
                        <h2><?php _e('Account & Features', 'humanitarianblog'); ?></h2>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How do I create an account?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('Click the "Sign In" button at the top of any page and select "Register." You can create an account using your email address. Registration is free and allows you to bookmark articles and customize your experience.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How do I bookmark articles?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('Once logged in, click the bookmark icon on any article to save it to your reading list. Access your saved articles from your account dashboard or by clicking the bookmark icon in the top navigation.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How do I subscribe to the newsletter?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('Enter your email address in the newsletter signup form at the bottom of any page. You can also manage your subscription preferences from your account settings.', 'humanitarianblog'); ?></p>
                            </div>
                        </div>
                    </section>

                    <!-- Support & Contact -->
                    <section id="support" class="faq-section">
                        <h2><?php _e('Support & Contact', 'humanitarianblog'); ?></h2>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How can I support HumanitarianBlog?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('You can support our journalism by making a one-time or monthly donation, subscribing to our newsletter, sharing our articles on social media, or contributing your expertise as a writer.', 'humanitarianblog'); ?></p>
                                <p><a href="<?php echo esc_url(home_url('/donate/')); ?>"><?php _e('Visit our donation page', 'humanitarianblog'); ?> &rarr;</a></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How do I report an error in an article?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('We appreciate readers who help us maintain accuracy. Please email corrections@humanitarianblog.com with the article URL and details of the error. We review all submissions and publish corrections promptly.', 'humanitarianblog'); ?></p>
                                <p><a href="<?php echo esc_url(home_url('/corrections/')); ?>"><?php _e('View our corrections policy', 'humanitarianblog'); ?> &rarr;</a></p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question" aria-expanded="false">
                                <span><?php _e('How can I contact the editorial team?', 'humanitarianblog'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="faq-answer">
                                <p><?php _e('For general inquiries, use our Contact page. For specific departments, use the appropriate email:', 'humanitarianblog'); ?></p>
                                <ul>
                                    <li><?php _e('Editorial:', 'humanitarianblog'); ?> editorial@humanitarianblog.com</li>
                                    <li><?php _e('Submissions:', 'humanitarianblog'); ?> submissions@humanitarianblog.com</li>
                                    <li><?php _e('Corrections:', 'humanitarianblog'); ?> corrections@humanitarianblog.com</li>
                                    <li><?php _e('General:', 'humanitarianblog'); ?> info@humanitarianblog.com</li>
                                </ul>
                            </div>
                        </div>
                    </section>

                    <!-- Still Have Questions -->
                    <div class="faq-contact-cta">
                        <h3><?php _e('Still Have Questions?', 'humanitarianblog'); ?></h3>
                        <p><?php _e('Can\'t find what you\'re looking for? Our team is happy to help.', 'humanitarianblog'); ?></p>
                        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn btn-primary"><?php _e('Contact Us', 'humanitarianblog'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(function(question) {
        question.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const answer = this.nextElementSibling;

            // Close all other answers
            faqQuestions.forEach(function(q) {
                q.setAttribute('aria-expanded', 'false');
                q.nextElementSibling.style.maxHeight = null;
            });

            // Toggle current
            if (!isExpanded) {
                this.setAttribute('aria-expanded', 'true');
                answer.style.maxHeight = answer.scrollHeight + 'px';
            }
        });
    });

    // Smooth scroll for navigation
    const navLinks = document.querySelectorAll('.faq-nav a');
    navLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            const target = document.querySelector(targetId);
            if (target) {
                const headerOffset = 100;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });

                // Update active state
                navLinks.forEach(function(l) { l.classList.remove('active'); });
                link.classList.add('active');
            }
        });
    });
});
</script>

<?php
get_footer();
