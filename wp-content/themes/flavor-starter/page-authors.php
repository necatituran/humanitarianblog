<?php
/**
 * Template Name: Authors
 * Displays all authors/contributors
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main authors-page">

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="section-badge"><?php _e('OUR TEAM', 'humanitarianblog'); ?></span>
                <h1><?php _e('Authors & Contributors', 'humanitarianblog'); ?></h1>
                <p class="page-hero-lead"><?php _e('Meet the journalists, analysts, and humanitarian professionals who bring you stories from around the world.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

    <!-- Authors Grid -->
    <section class="authors-section">
        <div class="container">
            <?php
            // Get all authors who have published posts
            $authors = get_users(array(
                'has_published_posts' => array('post'),
                'orderby' => 'post_count',
                'order' => 'DESC'
            ));

            if (!empty($authors)) :
            ?>
                <div class="authors-grid">
                    <?php foreach ($authors as $author) :
                        $author_id = $author->ID;
                        $post_count = count_user_posts($author_id, 'post', true);
                        $author_description = get_the_author_meta('description', $author_id);
                        $author_url = get_author_posts_url($author_id);
                    ?>
                        <article class="author-card">
                            <a href="<?php echo esc_url($author_url); ?>" class="author-card-link">
                                <div class="author-card-avatar">
                                    <?php echo get_avatar($author_id, 120); ?>
                                </div>
                                <div class="author-card-info">
                                    <h2 class="author-card-name"><?php echo esc_html($author->display_name); ?></h2>
                                    <?php if ($author_description) : ?>
                                        <p class="author-card-bio"><?php echo esc_html(wp_trim_words($author_description, 20, '...')); ?></p>
                                    <?php endif; ?>
                                    <span class="author-card-meta">
                                        <?php printf(
                                            _n('%d Article', '%d Articles', $post_count, 'humanitarianblog'),
                                            $post_count
                                        ); ?>
                                    </span>
                                </div>
                            </a>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="no-authors"><?php _e('No authors found.', 'humanitarianblog'); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Join Us CTA -->
    <section class="authors-cta">
        <div class="container">
            <div class="cta-box">
                <h2><?php _e('Want to Write for Us?', 'humanitarianblog'); ?></h2>
                <p><?php _e('We welcome contributions from journalists, researchers, and humanitarian professionals. Share your expertise with our global audience.', 'humanitarianblog'); ?></p>
                <a href="<?php echo esc_url(home_url('/write-for-us/')); ?>" class="btn btn-primary btn-lg"><?php _e('Submit Your Story', 'humanitarianblog'); ?></a>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
