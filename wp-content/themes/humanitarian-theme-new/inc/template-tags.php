<?php
/**
 * Template Tags
 *
 * Custom template tags for this theme.
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Category badge color mapping
 */
function humanitarian_get_category_colors() {
    return array(
        // Main Categories
        'technical-guides'        => '#0D5C63',  // Teal
        'aid-and-policy'          => '#1A73E8',  // Blue
        'environment-and-conflict'=> '#188038',  // Green
        'stories-from-the-field'  => '#E37400',  // Orange

        // Sub-categories / Topics
        'nutrition'          => '#0D5C63',
        'food-security'      => '#0D5C63',
        'wash'               => '#1A73E8',
        'water-sanitation'   => '#1A73E8',
        'health'             => '#D93025',
        'public-health'      => '#D93025',
        'shelter'            => '#795548',
        'nfi'                => '#795548',
        'protection'         => '#9C27B0',
        'child-protection'   => '#9C27B0',
        'education'          => '#FF9800',
        'education-in-emergencies' => '#FF9800',
        'livelihoods'        => '#4CAF50',
        'cash-assistance'    => '#4CAF50',
        'gender'             => '#E91E63',
        'gbv'                => '#E91E63',
        'climate'            => '#00BCD4',
        'climate-change'     => '#00BCD4',
        'conflict'           => '#607D8B',
        'displacement'       => '#607D8B',
        'coordination'       => '#3F51B5',
        'cluster-system'     => '#3F51B5',
        'monitoring'         => '#009688',
        'meal'               => '#009688',
        'localization'       => '#FF5722',
        'local-actors'       => '#FF5722',
        'fundraising'        => '#673AB7',
        'donors'             => '#673AB7',

        // Country/Region tags
        'syria'              => '#D93025',
        'yemen'              => '#1A73E8',
        'sudan'              => '#E37400',
        'ukraine'            => '#0057B7',
        'gaza'               => '#188038',
        'palestine'          => '#188038',

        // Legacy mappings
        'investigations'     => '#0D5C63',
        'policy'             => '#1A73E8',
        'migration'          => '#1A73E8',
        'aid-policy'         => '#1A73E8',
        'opinions'           => '#1A73E8',
        'environment'        => '#188038',
        'data'               => '#188038',
        'interview'          => '#E37400',
        'economy'            => '#1a1919',
        'tech'               => '#1a1919',
        'breaking'           => '#D93025',
        'urgent'             => '#D93025',
    );
}

/**
 * Tag badge color mapping (different from categories)
 */
function humanitarian_get_tag_colors() {
    return array(
        // Technical Guides sub-tags
        'strategy-development'      => '#2E7D32',  // Forest Green
        'project-management'        => '#1565C0',  // Strong Blue
        'humanitarian-programming'  => '#6A1B9A',  // Deep Purple
        'cross-cutting'             => '#00838F',  // Cyan Dark

        // Aid and Policy sub-tags
        'conflict'                  => '#C62828',  // Deep Red
        'culture'                   => '#AD1457',  // Pink Dark
        'policy'                    => '#283593',  // Indigo
        'opinion'                   => '#EF6C00',  // Orange Dark
        'aid'                       => '#2E7D32',  // Forest Green

        // General topic tags
        'nutrition'                 => '#558B2F',  // Light Green
        'food-security'             => '#33691E',  // Lime Dark
        'wash'                      => '#0277BD',  // Light Blue
        'water-sanitation'          => '#01579B',  // Light Blue Dark
        'health'                    => '#B71C1C',  // Red Dark
        'public-health'             => '#C62828',  // Deep Red
        'shelter'                   => '#4E342E',  // Brown Dark
        'nfi'                       => '#5D4037',  // Brown
        'protection'                => '#7B1FA2',  // Purple
        'child-protection'          => '#8E24AA',  // Purple Light
        'education'                 => '#F57C00',  // Orange
        'education-in-emergencies'  => '#EF6C00',  // Orange Dark
        'livelihoods'               => '#388E3C',  // Green
        'cash-assistance'           => '#43A047',  // Green Light
        'gender'                    => '#C2185B',  // Pink
        'gbv'                       => '#AD1457',  // Pink Dark
        'climate'                   => '#0097A7',  // Cyan
        'climate-change'            => '#00838F',  // Cyan Dark
        'displacement'              => '#455A64',  // Blue Grey
        'coordination'              => '#303F9F',  // Indigo Dark
        'cluster-system'            => '#3949AB',  // Indigo Light
        'monitoring'                => '#00796B',  // Teal
        'meal'                      => '#00695C',  // Teal Dark
        'localization'              => '#D84315',  // Deep Orange
        'local-actors'              => '#E64A19',  // Deep Orange Light
        'fundraising'               => '#512DA8',  // Deep Purple
        'donors'                    => '#5E35B1',  // Deep Purple Light

        // Country/Region tags
        'syria'                     => '#BF360C',  // Deep Orange Dark
        'yemen'                     => '#1976D2',  // Blue
        'sudan'                     => '#E65100',  // Orange Dark
        'ukraine'                   => '#1565C0',  // Blue Dark
        'gaza'                      => '#2E7D32',  // Green Dark
        'palestine'                 => '#1B5E20',  // Green Darker
        'afghanistan'               => '#4E342E',  // Brown
        'somalia'                   => '#BF360C',  // Deep Orange
        'ethiopia'                  => '#33691E',  // Lime Dark
        'drc'                       => '#1A237E',  // Indigo Dark
        'myanmar'                   => '#880E4F',  // Pink Dark
    );
}

/**
 * Article type badge color mapping
 */
function humanitarian_get_article_type_colors() {
    return array(
        'news'              => '#1A73E8',   // Blue
        'opinion'           => '#E37400',   // Orange
        'investigation'     => '#D93025',   // Red
        'in-depth-analysis' => '#0D5C63',   // Teal (primary)
        'feature'           => '#1a1919',   // Dark
        'interview'         => '#44A1A0',   // Light teal
    );
}

/**
 * Display category badge
 *
 * @param int|null $post_id Post ID (optional, defaults to current post)
 * @param bool $light Whether to use light style (for dark backgrounds)
 * @return void
 */
function humanitarian_category_badge($post_id = null, $light = false) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $categories = get_the_category($post_id);
    if (empty($categories)) {
        return;
    }

    $cat = $categories[0];
    $colors = humanitarian_get_category_colors();
    $slug = $cat->slug;
    $color = isset($colors[$slug]) ? $colors[$slug] : '#1a1919';

    if ($light) {
        printf(
            '<a href="%s" class="category-badge category-badge--light">%s</a>',
            esc_url(get_category_link($cat->term_id)),
            esc_html($cat->name)
        );
    } else {
        printf(
            '<a href="%s" class="category-badge category-badge--%s" style="background-color: %s; border-color: %s;">%s</a>',
            esc_url(get_category_link($cat->term_id)),
            esc_attr($slug),
            esc_attr($color),
            esc_attr($color),
            esc_html($cat->name)
        );
    }
}

/**
 * Get category badge HTML
 *
 * @param int|null $post_id Post ID (optional)
 * @param bool $light Whether to use light style
 * @return string HTML output
 */
function humanitarian_get_category_badge($post_id = null, $light = false) {
    ob_start();
    humanitarian_category_badge($post_id, $light);
    return ob_get_clean();
}

/**
 * Calculate reading time
 *
 * @param int|null $post_id Post ID (optional, defaults to current post)
 * @return string Formatted reading time
 */
function humanitarian_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = max(1, ceil($word_count / 200));

    return sprintf(
        /* translators: %d: number of minutes */
        _n('%d Min Read', '%d Min Read', $reading_time, 'humanitarian'),
        $reading_time
    );
}

/**
 * Display formatted date
 *
 * @param int|null $post_id Post ID (optional)
 * @return void
 */
function humanitarian_posted_on($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $time_string = '<time datetime="%1$s">%2$s</time>';

    $time_string = sprintf(
        $time_string,
        esc_attr(get_the_date(DATE_W3C, $post_id)),
        esc_html(get_the_date('', $post_id))
    );

    echo $time_string;
}

/**
 * Display relative date (Yesterday, 2 days ago, etc.)
 *
 * @param int|null $post_id Post ID (optional)
 * @return void
 */
function humanitarian_relative_date($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $post_date = get_the_date('U', $post_id);
    $current_date = current_time('timestamp');
    $diff = $current_date - $post_date;
    $days = floor($diff / (60 * 60 * 24));

    if ($days === 0) {
        echo esc_html__('Today', 'humanitarian');
    } elseif ($days === 1) {
        echo esc_html__('Yesterday', 'humanitarian');
    } elseif ($days < 7) {
        /* translators: %d: number of days */
        printf(esc_html__('%d days ago', 'humanitarian'), $days);
    } else {
        humanitarian_posted_on($post_id);
    }
}

/**
 * Display author with avatar
 *
 * @param int|null $post_id Post ID (optional)
 * @param bool $show_avatar Whether to show avatar
 * @return void
 */
function humanitarian_posted_by($post_id = null, $show_avatar = true) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $author_id = get_post_field('post_author', $post_id);

    echo '<div class="author-info">';

    if ($show_avatar) {
        echo '<div class="author-info__avatar">';
        echo get_avatar($author_id, 40);
        echo '</div>';
    }

    echo '<span class="author-info__name">';
    printf(
        /* translators: %s: post author */
        esc_html__('By %s', 'humanitarian'),
        '<a href="' . esc_url(get_author_posts_url($author_id)) . '">' . esc_html(get_the_author_meta('display_name', $author_id)) . '</a>'
    );
    echo '</span>';

    echo '</div>';
}

/**
 * Display post excerpt with custom length
 *
 * @param int $length Number of words
 * @param int|null $post_id Post ID (optional)
 * @return void
 */
function humanitarian_excerpt($length = 25, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $excerpt = get_the_excerpt($post_id);

    if (empty($excerpt)) {
        $content = get_post_field('post_content', $post_id);
        $excerpt = wp_trim_words(strip_tags($content), $length, '&hellip;');
    } else {
        $excerpt = wp_trim_words($excerpt, $length, '&hellip;');
    }

    echo esc_html($excerpt);
}

/**
 * Get post excerpt with custom length
 *
 * @param int $length Number of words
 * @param int|null $post_id Post ID (optional)
 * @return string
 */
function humanitarian_get_excerpt($length = 25, $post_id = null) {
    ob_start();
    humanitarian_excerpt($length, $post_id);
    return ob_get_clean();
}

/**
 * Display featured image with lazy loading
 *
 * @param string $size Image size
 * @param int|null $post_id Post ID (optional)
 * @param array $attr Additional attributes
 * @return void
 */
function humanitarian_post_thumbnail($size = 'humanitarian-card', $post_id = null, $attr = array()) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!has_post_thumbnail($post_id)) {
        // Placeholder image
        printf(
            '<img src="%s" alt="%s" class="wp-post-image" loading="lazy">',
            esc_url(HUMANITARIAN_URI . '/assets/images/placeholder.svg'),
            esc_attr(get_the_title($post_id))
        );
        return;
    }

    $default_attr = array(
        'loading' => 'lazy',
        'alt'     => get_the_title($post_id),
    );

    $attr = wp_parse_args($attr, $default_attr);

    echo get_the_post_thumbnail($post_id, $size, $attr);
}

/**
 * Display site logo or site title
 *
 * @return void
 */
function humanitarian_site_logo() {
    $logo_url = get_template_directory_uri() . '/assets/images/humanitarian_logo2.png';
    ?>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="site-logo" rel="home">
        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="site-logo__img" />
    </a>
    <?php
}

/**
 * Display pagination
 *
 * @param WP_Query|null $query Custom query object (optional)
 * @return void
 */
function humanitarian_pagination($query = null) {
    global $wp_query;

    $current_query = $query ? $query : $wp_query;
    $total_pages = $current_query->max_num_pages;

    // Always show pagination wrapper for consistent spacing
    echo '<nav class="pagination" aria-label="' . esc_attr__('Posts navigation', 'humanitarian') . '">';

    if ($total_pages > 1) {
        $current_page = max(1, get_query_var('paged'));

        $args = array(
            'total'     => $total_pages,
            'current'   => $current_page,
            'mid_size'  => 2,
            'end_size'  => 1,
            'prev_text' => '<span class="pagination__prev">&laquo; ' . __('Previous', 'humanitarian') . '</span>',
            'next_text' => '<span class="pagination__next">' . __('Next', 'humanitarian') . ' &raquo;</span>',
            'type'      => 'list',
        );

        echo paginate_links($args);
    } else {
        // Show info even when only one page
        $found_posts = $current_query->found_posts;
        if ($found_posts > 0) {
            echo '<span class="pagination__info">';
            printf(
                /* translators: %d: number of posts */
                esc_html(_n('Showing %d article', 'Showing %d articles', $found_posts, 'humanitarian')),
                $found_posts
            );
            echo '</span>';
        }
    }

    echo '</nav>';
}

/**
 * Display breadcrumbs
 *
 * @return void
 */
function humanitarian_breadcrumbs() {
    if (is_front_page()) {
        return;
    }

    echo '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'humanitarian') . '">';
    echo '<a href="' . esc_url(home_url('/')) . '">' . esc_html__('Home', 'humanitarian') . '</a>';

    if (is_category() || is_single()) {
        echo ' <span class="separator">/</span> ';
        $categories = get_the_category();
        if (!empty($categories)) {
            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
        }
    }

    if (is_single()) {
        echo ' <span class="separator">/</span> ';
        echo '<span class="current">' . esc_html(get_the_title()) . '</span>';
    }

    if (is_page()) {
        echo ' <span class="separator">/</span> ';
        echo '<span class="current">' . esc_html(get_the_title()) . '</span>';
    }

    if (is_search()) {
        echo ' <span class="separator">/</span> ';
        echo '<span class="current">' . esc_html__('Search Results', 'humanitarian') . '</span>';
    }

    if (is_author()) {
        echo ' <span class="separator">/</span> ';
        echo '<span class="current">' . esc_html(get_the_author()) . '</span>';
    }

    echo '</nav>';
}

/**
 * Get social share URLs
 *
 * @param int|null $post_id Post ID (optional)
 * @return array Array of share URLs
 */
function humanitarian_get_share_urls($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $url = urlencode(get_permalink($post_id));
    $title = urlencode(get_the_title($post_id));

    return array(
        'twitter'  => 'https://twitter.com/intent/tweet?url=' . $url . '&text=' . $title,
        'facebook' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url,
        'linkedin' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title,
        'email'    => 'mailto:?subject=' . $title . '&body=' . $url,
    );
}
