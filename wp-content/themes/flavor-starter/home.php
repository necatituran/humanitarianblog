<?php
/**
 * The blog home template - Magazine Style
 *
 * This template displays the blog listing page with category hero cards
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();

// Get ALL categories for hero section
$categories = get_categories([
    'orderby'    => 'name',
    'order'      => 'ASC',
    'hide_empty' => false,
]);

// Default placeholder images for categories (Unsplash free images)
$category_placeholders = [
    'aid'            => 'https://images.unsplash.com/photo-1532629345422-7515f3d16bb6?w=600&h=400&fit=crop',
    'analysis'       => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=600&h=400&fit=crop',
    'breaking'       => 'https://images.unsplash.com/photo-1495020689067-958852a7765e?w=600&h=400&fit=crop',
    'feature'        => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&h=400&fit=crop',
    'interview'      => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=600&h=400&fit=crop',
    'investigation'  => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=600&h=400&fit=crop',
    'news'           => 'https://images.unsplash.com/photo-1586339949916-3e9457bef6d3?w=600&h=400&fit=crop',
    'opinion'        => 'https://images.unsplash.com/photo-1455849318743-b2233052fcff?w=600&h=400&fit=crop',
    'report'         => 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?w=600&h=400&fit=crop',
    'sample'         => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=600&h=400&fit=crop',
    'uncategorized'  => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=600&h=400&fit=crop',
];

// Generic fallback image
$default_placeholder = 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&h=400&fit=crop';

// Category background images - returns image URL (always returns something)
function get_category_image($cat_id, $cat_slug = '') {
    global $category_placeholders, $default_placeholder;

    // First try to get image from category's posts
    $posts = get_posts([
        'category'       => $cat_id,
        'posts_per_page' => 1,
        'meta_key'       => '_thumbnail_id',
    ]);

    if (!empty($posts) && has_post_thumbnail($posts[0]->ID)) {
        return get_the_post_thumbnail_url($posts[0]->ID, 'card-medium');
    }

    // Use placeholder based on category slug
    $slug = strtolower($cat_slug);
    if (isset($category_placeholders[$slug])) {
        return $category_placeholders[$slug];
    }

    // Return default placeholder
    return $default_placeholder;
}
?>

<main id="primary" class="site-main blog-page">

    <!-- Category Hero Cards -->
    <section class="blog-hero">
        <div class="container">
            <header class="categories-header">
                <h1 class="categories-title"><?php _e('CATEGORIES', 'humanitarianblog'); ?></h1>
                <p class="categories-subtitle"><?php _e('Explore our coverage areas', 'humanitarianblog'); ?></p>
            </header>
            <div class="category-cards-grid">
                <?php
                foreach ($categories as $cat) :
                    $cat_link = get_category_link($cat->term_id);
                    $cat_image = get_category_image($cat->term_id, $cat->slug);
                ?>
                    <a href="<?php echo esc_url($cat_link); ?>" class="category-hero-card">
                        <div class="category-card-bg" style="background-image: url('<?php echo esc_url($cat_image); ?>');"></div>
                        <div class="category-card-overlay"></div>
                        <div class="category-card-content">
                            <h2 class="category-card-title"><?php echo esc_html(strtoupper($cat->name)); ?></h2>
                            <?php if ($cat->description) : ?>
                                <p class="category-card-desc"><?php echo esc_html($cat->description); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- Blog Posts Section -->
    <section class="blog-posts-section">
        <div class="container">
            <!-- Section Header -->
            <header class="blog-section-header">
                <div class="blog-header-left">
                    <span class="section-badge"><?php _e('BLOG', 'humanitarianblog'); ?></span>
                    <h1><?php _e('Latest Articles', 'humanitarianblog'); ?></h1>
                </div>
                <div class="blog-filters">
                    <span class="filter-label"><?php _e('Sort by:', 'humanitarianblog'); ?></span>
                    <select class="blog-sort-select">
                        <option value="date" <?php selected(get_query_var('orderby', 'date'), 'date'); ?>><?php _e('Newest', 'humanitarianblog'); ?></option>
                        <option value="title" <?php selected(get_query_var('orderby'), 'title'); ?>><?php _e('Title', 'humanitarianblog'); ?></option>
                        <option value="comment_count" <?php selected(get_query_var('orderby'), 'comment_count'); ?>><?php _e('Popular', 'humanitarianblog'); ?></option>
                    </select>
                </div>
            </header>

            <?php if (have_posts()) : ?>
                <!-- Posts Grid (Current Coverage Style) -->
                <div class="articles-grid">
                    <?php while (have_posts()) : the_post(); ?>
                        <article <?php post_class('article-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="card-thumbnail">
                                    <?php the_post_thumbnail('card-medium'); ?>
                                    <?php
                                    // Photo caption
                                    $photo_caption = function_exists('humanitarian_get_photo_caption') ? humanitarian_get_photo_caption() : '';
                                    if ($photo_caption) :
                                        $cats = get_the_category();
                                        $cat_name = !empty($cats) ? $cats[0]->name : __('Report', 'humanitarianblog');
                                    ?>
                                        <div class="photo-caption-wrapper">
                                            <span class="photo-caption"><?php echo esc_html($cat_name); ?></span>
                                            <span class="photo-caption-line2"><?php echo esc_html($photo_caption); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                            <div class="card-content">
                                <?php
                                $categories = get_the_category();
                                if (!empty($categories)) :
                                    $cat_colors = ['category-badge--red', 'category-badge--blue', 'category-badge--green', 'category-badge--orange'];
                                    $cat_color = $cat_colors[array_rand($cat_colors)];
                                    ?>
                                    <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="category-badge <?php echo esc_attr($cat_color); ?>">
                                        <?php echo esc_html($categories[0]->name); ?>
                                    </a>
                                <?php endif; ?>
                                <?php the_title('<h3 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
                                <div class="card-meta">
                                    <span class="date"><?php echo get_the_date(); ?></span>
                                    <span class="separator">·</span>
                                    <span class="read-time"><?php echo function_exists('humanitarianblog_reading_time') ? humanitarianblog_reading_time() : '1 min read'; ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <nav class="blog-pagination">
                    <?php
                    the_posts_pagination([
                        'mid_size'  => 2,
                        'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>' . __('Previous', 'humanitarianblog'),
                        'next_text' => __('Next', 'humanitarianblog') . '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
                    ]);
                    ?>
                </nav>

            <?php else : ?>
                <div class="no-posts-message">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                        <polyline points="14,2 14,8 20,8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <line x1="10" y1="9" x2="8" y2="9"/>
                    </svg>
                    <h2><?php _e('No articles found', 'humanitarianblog'); ?></h2>
                    <p><?php _e('Check back soon for new content.', 'humanitarianblog'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main>

<?php
get_footer();
