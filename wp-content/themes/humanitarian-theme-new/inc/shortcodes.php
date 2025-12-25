<?php
/**
 * Custom Shortcodes
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Article Link Shortcode - GÖREV 12
 *
 * Usage:
 * [article id="123"]
 * [article slug="article-slug"]
 * [article id="123" style="card"]
 * [article id="123" style="inline"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function humanitarian_article_shortcode($atts) {
    $atts = shortcode_atts(array(
        'id'    => 0,
        'slug'  => '',
        'style' => 'card', // 'card' or 'inline'
    ), $atts, 'article');

    $post = null;

    // Get post by ID or slug
    if (!empty($atts['id'])) {
        $post = get_post(intval($atts['id']));
    } elseif (!empty($atts['slug'])) {
        $post = get_page_by_path($atts['slug'], OBJECT, 'post');
    }

    // Return empty if post not found
    if (!$post || $post->post_status !== 'publish') {
        return '';
    }

    // Inline style - simple link
    if ($atts['style'] === 'inline') {
        return sprintf(
            '<a href="%s" class="article-inline-link">%s</a>',
            esc_url(get_permalink($post->ID)),
            esc_html($post->post_title)
        );
    }

    // Card style - rich preview
    $thumbnail = '';
    if (has_post_thumbnail($post->ID)) {
        $thumbnail = get_the_post_thumbnail($post->ID, 'thumbnail', array(
            'class' => 'article-card-embed__image',
            'loading' => 'lazy',
        ));
    }

    $categories = get_the_category($post->ID);
    $category_name = !empty($categories) ? $categories[0]->name : '';

    $excerpt = get_the_excerpt($post->ID);
    if (empty($excerpt)) {
        $excerpt = wp_trim_words(strip_tags($post->post_content), 20, '...');
    }

    ob_start();
    ?>
    <div class="article-card-embed">
        <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="article-card-embed__link">
            <?php if ($thumbnail) : ?>
            <div class="article-card-embed__thumbnail">
                <?php echo $thumbnail; ?>
            </div>
            <?php endif; ?>
            <div class="article-card-embed__content">
                <?php if ($category_name) : ?>
                <span class="article-card-embed__category"><?php echo esc_html($category_name); ?></span>
                <?php endif; ?>
                <h4 class="article-card-embed__title"><?php echo esc_html($post->post_title); ?></h4>
                <p class="article-card-embed__excerpt"><?php echo esc_html($excerpt); ?></p>
            </div>
        </a>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('article', 'humanitarian_article_shortcode');

/**
 * Related Articles Shortcode
 *
 * Usage:
 * [related_articles count="3"]
 * [related_articles category="aid-and-policy" count="4"]
 *
 * @param array $atts Shortcode attributes
 * @return string HTML output
 */
function humanitarian_related_articles_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count'    => 3,
        'category' => '',
    ), $atts, 'related_articles');

    $args = array(
        'posts_per_page' => intval($atts['count']),
        'post__not_in'   => array(get_the_ID()),
    );

    // Filter by category if specified
    if (!empty($atts['category'])) {
        $args['category_name'] = sanitize_text_field($atts['category']);
    } else {
        // Get current post's categories
        $categories = get_the_category();
        if (!empty($categories)) {
            $args['category__in'] = array($categories[0]->term_id);
        }
    }

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        return '';
    }

    ob_start();
    ?>
    <div class="related-articles-embed">
        <h4 class="related-articles-embed__title"><?php esc_html_e('Related Articles', 'humanitarian'); ?></h4>
        <div class="related-articles-embed__grid">
            <?php
            while ($query->have_posts()) : $query->the_post();
                ?>
                <a href="<?php the_permalink(); ?>" class="related-articles-embed__item">
                    <?php if (has_post_thumbnail()) : ?>
                    <div class="related-articles-embed__thumb">
                        <?php the_post_thumbnail('thumbnail'); ?>
                    </div>
                    <?php endif; ?>
                    <span class="related-articles-embed__item-title"><?php the_title(); ?></span>
                </a>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('related_articles', 'humanitarian_related_articles_shortcode');
