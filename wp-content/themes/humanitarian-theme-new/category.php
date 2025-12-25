<?php
/**
 * Category Template
 *
 * Displays category as a page with sub-tags and articles
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();

$category = get_queried_object();
$category_slug = $category->slug;

// Define sub-tags for each category
$category_tags = array(
    'technical-guides' => array(
        'strategy-development' => __('Strategy Development', 'humanitarian'),
        'project-management' => __('Project Management Techniques', 'humanitarian'),
        'humanitarian-programming' => __('Humanitarian Aid Programming', 'humanitarian'),
        'cross-cutting' => __('Cross Cutting', 'humanitarian'),
    ),
    'aid-and-policy' => array(
        'conflict' => __('Conflict', 'humanitarian'),
        'culture' => __('Culture', 'humanitarian'),
        'policy' => __('Policy', 'humanitarian'),
        'aid' => __('Aid', 'humanitarian'),
    ),
    'environment-and-conflict' => array(
        'climate' => __('Climate', 'humanitarian'),
        'resources' => __('Resources', 'humanitarian'),
        'environment' => __('Environment', 'humanitarian'),
    ),
    'stories-from-the-field' => array(
        'personal' => __('Personal Stories', 'humanitarian'),
        'community' => __('Community Voices', 'humanitarian'),
        'frontline' => __('Frontline Reports', 'humanitarian'),
    ),
);

// Get tags for current category
$current_tags = isset($category_tags[$category_slug]) ? $category_tags[$category_slug] : array();

// Check for active tag filter
$active_tag = isset($_GET['tag']) ? sanitize_text_field($_GET['tag']) : '';
?>

<div class="category-page">
    <div class="container">

        <!-- Category Header -->
        <header class="category-page__header">
            <h1 class="category-page__title"><?php single_cat_title(); ?></h1>
            <?php if (category_description()) : ?>
                <div class="category-page__description">
                    <?php echo category_description(); ?>
                </div>
            <?php endif; ?>
        </header>

        <?php if (!empty($current_tags)) : ?>
        <!-- Sub-Tags Navigation -->
        <nav class="category-page__tags">
            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
               class="category-tag <?php echo empty($active_tag) ? 'active' : ''; ?>">
                <?php esc_html_e('All', 'humanitarian'); ?>
            </a>
            <?php foreach ($current_tags as $tag_slug => $tag_name) :
                $tag = get_term_by('slug', $tag_slug, 'post_tag');
                $tag_count = $tag ? $tag->count : 0;
            ?>
                <a href="<?php echo esc_url(add_query_arg('tag', $tag_slug, get_category_link($category->term_id))); ?>"
                   class="category-tag category-tag--<?php echo esc_attr($tag_slug); ?> <?php echo $active_tag === $tag_slug ? 'active' : ''; ?>"
                   data-tag="<?php echo esc_attr($tag_slug); ?>">
                    <?php echo esc_html($tag_name); ?>
                    <?php if ($tag_count > 0) : ?>
                        <span class="category-tag__count"><?php echo esc_html($tag_count); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>

        <?php
        // Build query args
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => 12,
            'paged' => get_query_var('paged') ? get_query_var('paged') : 1,
            'cat' => $category->term_id,
        );

        // Add tag filter if active
        if (!empty($active_tag)) {
            $query_args['tag'] = $active_tag;
        }

        $category_query = new WP_Query($query_args);

        if ($category_query->have_posts()) :
        ?>

        <!-- Articles Grid -->
        <div class="category-page__grid">
            <?php
            while ($category_query->have_posts()) : $category_query->the_post();
                get_template_part('template-parts/cards/card', 'category');
            endwhile;
            ?>
        </div>

        <?php
        // Pagination
        $big = 999999999;
        echo '<nav class="category-page__pagination">';
        echo paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $category_query->max_num_pages,
            'prev_text' => '&larr; ' . __('Previous', 'humanitarian'),
            'next_text' => __('Next', 'humanitarian') . ' &rarr;',
        ));
        echo '</nav>';

        wp_reset_postdata();
        ?>

        <?php else : ?>

        <div class="category-page__empty">
            <p><?php esc_html_e('No articles found in this category.', 'humanitarian'); ?></p>
        </div>

        <?php endif; ?>

    </div>
</div>

<?php
get_footer();
?>
