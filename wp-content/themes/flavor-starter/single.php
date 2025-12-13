<?php
/**
 * The template for displaying single posts
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main single-post">

    <?php
    while (have_posts()) :
        the_post();
        ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

            <header class="entry-header container content-width">
                <?php the_category(); ?>

                <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

                <?php if (has_excerpt()) : ?>
                    <div class="entry-subtitle">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>

                <div class="entry-meta">
                    <span class="author">
                        <?php _e('By', 'humanitarianblog'); ?>
                        <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                            <?php the_author(); ?>
                        </a>
                    </span>
                    <span class="date"><?php echo get_the_date(); ?></span>
                    <span class="reading-time"><?php echo flavor_reading_time(); ?></span>
                </div>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <div class="entry-thumbnail">
                    <?php the_post_thumbnail('hero-large'); ?>
                    <?php
                    $caption = get_the_post_thumbnail_caption();
                    if ($caption) :
                        ?>
                        <p class="image-caption"><?php echo esc_html($caption); ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="entry-content container content-width">
                <?php
                the_content();

                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'humanitarianblog'),
                    'after'  => '</div>',
                ));
                ?>
            </div>

            <footer class="entry-footer container content-width">
                <?php
                $tags = get_the_tags();
                if ($tags) :
                    ?>
                    <div class="entry-tags">
                        <strong><?php _e('Tags:', 'humanitarianblog'); ?></strong>
                        <?php the_tags('', ', ', ''); ?>
                    </div>
                <?php endif; ?>
            </footer>

        </article>

        <?php
        // Author Bio
        if (get_the_author_meta('description')) :
            ?>
            <div class="author-bio container content-width">
                <div class="author-avatar">
                    <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                </div>
                <div class="author-info">
                    <h3 class="author-name"><?php the_author(); ?></h3>
                    <p class="author-description"><?php the_author_meta('description'); ?></p>
                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-link">
                        <?php _e('View all posts', 'humanitarianblog'); ?> →
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php
        // Related Articles
        $related = new WP_Query(array(
            'posts_per_page'      => 3,
            'post__not_in'        => array(get_the_ID()),
            'category__in'        => wp_get_post_categories(get_the_ID()),
            'ignore_sticky_posts' => true,
        ));

        if ($related->have_posts()) :
            ?>
            <section class="related-articles container">
                <h2><?php _e('Related Articles', 'humanitarianblog'); ?></h2>
                <div class="grid grid-cols-3">
                    <?php while ($related->have_posts()) : $related->the_post(); ?>
                        <article <?php post_class('article-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="card-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('card-medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="card-content">
                                <?php the_title('<h3 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php
            wp_reset_postdata();
        endif;
        ?>

        <?php
        // Comments
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif;
        ?>

    <?php endwhile; ?>

</main>

<?php
get_footer();

/**
 * Calculate reading time
 */
function flavor_reading_time() {
    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // 200 words per minute

    return sprintf(_n('%s min read', '%s min read', $reading_time, 'humanitarianblog'), $reading_time);
}
