<?php
/**
 * Author Archive Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$author_id = get_queried_object_id();
$author_name = get_the_author_meta('display_name', $author_id);
$author_bio = get_the_author_meta('description', $author_id);
$author_title = get_the_author_meta('job_title', $author_id);
?>

<div class="author-page">
    <div class="container">

        <header class="author-page__header">
            <div class="author-page__avatar">
                <?php echo get_avatar($author_id, 128); ?>
            </div>
            <h1 class="author-page__name"><?php echo esc_html($author_name); ?></h1>
            <?php if ($author_title) : ?>
            <p class="author-page__title"><?php echo esc_html($author_title); ?></p>
            <?php endif; ?>
            <?php if ($author_bio) : ?>
            <p class="author-page__bio"><?php echo esc_html($author_bio); ?></p>
            <?php endif; ?>

            <div class="author-page__social">
                <?php if ($twitter = get_the_author_meta('twitter', $author_id)) : ?>
                <a href="<?php echo esc_url($twitter); ?>" class="author-page__social-link" target="_blank" rel="noopener noreferrer">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </a>
                <?php endif; ?>

                <?php if ($website = get_the_author_meta('url', $author_id)) : ?>
                <a href="<?php echo esc_url($website); ?>" class="author-page__social-link" target="_blank" rel="noopener noreferrer">
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </a>
                <?php endif; ?>
            </div>
        </header>

        <?php if (have_posts()) : ?>

        <h2 class="author-page__articles-title">
            <?php
            /* translators: %s: author name */
            printf(esc_html__('Articles by %s', 'humanitarian'), esc_html($author_name));
            ?>
        </h2>

        <div class="archive-page__grid">
            <?php
            while (have_posts()) : the_post();
                get_template_part('template-parts/cards/card', 'grid');
            endwhile;
            ?>
        </div>

        <?php humanitarian_pagination(); ?>

        <?php else : ?>

        <p><?php esc_html_e('No articles found.', 'humanitarian'); ?></p>

        <?php endif; ?>

    </div>
</div>

<?php
get_footer();
?>
