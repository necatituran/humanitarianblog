<?php
/**
 * The template for displaying single posts - Editorial Style
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

        <!-- Breadcrumb Navigation -->
        <div class="breadcrumb-wrapper">
            <div class="container">
                <?php humanitarianblog_breadcrumb(); ?>
            </div>
        </div>

        <article id="post-<?php the_ID(); ?>" <?php post_class('single-article'); ?>>

            <!-- Article Hero - Side by Side Layout -->
            <header class="article-hero">
                <div class="container">
                    <div class="article-hero-grid">
                        <!-- Left: Article Info -->
                        <div class="article-hero-info">
                            <?php
                            // Article type and breaking indicator
                            $article_type = humanitarianblog_get_article_type();
                            $is_breaking = humanitarianblog_is_breaking();

                            // Show ONLY ONE badge - Breaking takes priority
                            if ($is_breaking) : ?>
                                <span class="breaking-indicator"><?php _e('Breaking', 'humanitarianblog'); ?></span>
                            <?php elseif ($article_type) : ?>
                                <a href="<?php echo esc_url($article_type['url']); ?>" class="category-badge category-badge--<?php echo esc_attr($article_type['color']); ?>">
                                    <?php echo esc_html($article_type['name']); ?>
                                </a>
                            <?php else :
                                // Fallback to category only if no article type
                                $categories = get_the_category();
                                if (!empty($categories)) : ?>
                                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="category-badge category-badge--dark">
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </a>
                                <?php endif;
                            endif; ?>

                            <?php the_title('<h1 class="article-hero-title">', '</h1>'); ?>

                            <?php if (has_excerpt()) : ?>
                                <p class="article-hero-excerpt"><?php echo get_the_excerpt(); ?></p>
                            <?php endif; ?>

                            <div class="article-hero-meta">
                                <div class="meta-author">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 48); ?>
                                    <div class="meta-author-info">
                                        <span class="author-name">
                                            <?php _e('By', 'humanitarianblog'); ?>
                                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                <?php the_author(); ?>
                                            </a>
                                        </span>
                                        <span class="meta-details">
                                            <time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date(); ?></time>
                                            <span class="separator">·</span>
                                            <span class="reading-time"><?php echo humanitarianblog_reading_time(); ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Listen Button - Prominent TTS -->
                            <div class="article-listen">
                                <button type="button"
                                        class="listen-btn-hero"
                                        id="listen-button-hero"
                                        aria-label="<?php esc_attr_e('Listen to this article', 'humanitarianblog'); ?>">
                                    <svg class="listen-icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
                                    </svg>
                                    <span class="listen-text"><?php _e('Listen to Article', 'humanitarianblog'); ?></span>
                                    <span class="listen-duration"><?php echo humanitarianblog_reading_time(); ?></span>
                                </button>
                            </div>

                            <!-- Share buttons -->
                            <div class="article-share">
                                <span class="share-label"><?php _e('Share', 'humanitarianblog'); ?></span>
                                <div class="share-buttons">
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>"
                                       target="_blank" rel="noopener" class="share-btn share-twitter" aria-label="Share on Twitter">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                        </svg>
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>"
                                       target="_blank" rel="noopener" class="share-btn share-facebook" aria-label="Share on Facebook">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                    </a>
                                    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>"
                                       target="_blank" rel="noopener" class="share-btn share-linkedin" aria-label="Share on LinkedIn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                    <button class="share-btn share-copy" onclick="navigator.clipboard.writeText('<?php echo esc_url(get_permalink()); ?>')" aria-label="Copy link">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Featured Image -->
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="article-hero-image">
                                <?php the_post_thumbnail('hero-large'); ?>
                                <?php
                                $caption = get_the_post_thumbnail_caption();
                                if ($caption) :
                                    ?>
                                    <span class="image-caption"><?php echo esc_html($caption); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <!-- Article Content -->
            <div class="article-content">
                <div class="container article-container">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'humanitarianblog'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
            </div>

            <!-- Article Footer -->
            <footer class="article-footer">
                <div class="container article-container">
                    <?php
                    $tags = get_the_tags();
                    if ($tags) :
                        ?>
                        <div class="article-tags">
                            <strong><?php _e('Tags:', 'humanitarianblog'); ?></strong>
                            <div class="tags-list">
                                <?php foreach ($tags as $tag) : ?>
                                    <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="tag-link">
                                        <?php echo esc_html($tag->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </footer>

        </article>

        <?php
        // Author Bio
        if (get_the_author_meta('description')) :
            ?>
            <section class="author-bio-section">
                <div class="container article-container">
                    <div class="author-bio">
                        <div class="author-avatar">
                            <?php echo get_avatar(get_the_author_meta('ID'), 96); ?>
                        </div>
                        <div class="author-info">
                            <span class="author-label"><?php _e('Written by', 'humanitarianblog'); ?></span>
                            <h3 class="author-name"><?php the_author(); ?></h3>
                            <p class="author-description"><?php the_author_meta('description'); ?></p>
                            <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-link">
                                <?php _e('View all articles', 'humanitarianblog'); ?> →
                            </a>
                        </div>
                    </div>
                </div>
            </section>
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
            <section class="related-articles-section">
                <div class="container">
                    <h2 class="section-title"><?php _e('Related Articles', 'humanitarianblog'); ?></h2>
                    <div class="related-grid">
                        <?php while ($related->have_posts()) : $related->the_post(); ?>
                            <article <?php post_class('article-card'); ?>>
                                <?php if (has_post_thumbnail()) : ?>
                                    <a href="<?php the_permalink(); ?>" class="card-thumbnail">
                                        <?php the_post_thumbnail('card-medium'); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-content">
                                    <?php the_title('<h3 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
                                    <div class="card-meta">
                                        <span class="date"><?php echo get_the_date(); ?></span>
                                    </div>
                                </div>
                            </article>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
            <?php
            wp_reset_postdata();
        endif;
        ?>

        <?php
        // Comments
        if (comments_open() || get_comments_number()) :
            ?>
            <section class="comments-section">
                <div class="container article-container">
                    <?php comments_template(); ?>
                </div>
            </section>
        <?php endif; ?>

    <?php endwhile; ?>

</main>

<?php
// Floating Reading Toolbar (QR, PDF, Save, Share buttons)
get_template_part('template-parts/reading-toolbar');
?>

<?php
get_footer();
