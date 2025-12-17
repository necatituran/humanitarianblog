<?php
/**
 * HumanitarianBlog Theme Functions
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define Constants
 */
define('HUMANITARIAN_THEME_VERSION', '1.0.0');
define('HUMANITARIAN_THEME_DIR', get_template_directory());
define('HUMANITARIAN_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function humanitarianblog_theme_setup() {

    // Make theme available for translation
    load_theme_textdomain('humanitarianblog', HUMANITARIAN_THEME_DIR . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Add custom image sizes
    add_image_size('hero-large', 1200, 800, true);      // Hero section
    add_image_size('card-medium', 600, 400, true);      // Standard cards
    add_image_size('card-small', 400, 267, true);       // Small cards
    add_image_size('author-thumb', 150, 150, true);     // Author avatars

    // Register navigation menus
    register_nav_menus(array(
        'primary'   => __('Primary Menu', 'humanitarianblog'),
        'footer'    => __('Footer Menu', 'humanitarianblog'),
        'social'    => __('Social Links', 'humanitarianblog'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');

    // Add support for responsive embedded content
    add_theme_support('responsive-embeds');

    // Add support for custom background
    add_theme_support('custom-background', array(
        'default-color' => 'F9FAFB',
    ));
}
add_action('after_setup_theme', 'humanitarianblog_theme_setup');

/**
 * Set the content width
 */
function humanitarianblog_content_width() {
    $GLOBALS['content_width'] = apply_filters('humanitarianblog_content_width', 800);
}
add_action('after_setup_theme', 'humanitarianblog_content_width', 0);

/**
 * Google Fonts URL - Editorial Magazine Style
 *
 * @return string Google Fonts URL
 */
function humanitarianblog_fonts_url() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    // Playfair Display (Headlines - Editorial serif)
    if ('off' !== _x('on', 'Playfair Display font: on or off', 'humanitarianblog')) {
        $fonts[] = 'Playfair Display:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400;1,700';
    }

    // Inter (Body & UI - Clean modern sans-serif)
    if ('off' !== _x('on', 'Inter font: on or off', 'humanitarianblog')) {
        $fonts[] = 'Inter:wght@300;400;500;600;700';
    }

    // Amiri (Arabic Headlines)
    if ('off' !== _x('on', 'Amiri font: on or off', 'humanitarianblog')) {
        $fonts[] = 'Amiri:ital,wght@0,400;0,700;1,400;1,700';
        $subsets .= ',arabic';
    }

    // IBM Plex Sans Arabic (Arabic Body)
    if ('off' !== _x('on', 'IBM Plex Sans Arabic font: on or off', 'humanitarianblog')) {
        $fonts[] = 'IBM+Plex+Sans+Arabic:wght@400;500;600;700';
        $subsets .= ',arabic';
    }

    if ($fonts) {
        $fonts_url = add_query_arg(array(
            'family'  => implode('&family=', $fonts),
            'display' => 'swap',
        ), 'https://fonts.googleapis.com/css2');
    }

    return esc_url_raw($fonts_url);
}

/**
 * Fallback Menu (when no menu is assigned)
 */
function humanitarianblog_fallback_menu() {
    // Get blog page URL
    $blog_page_id = get_option('page_for_posts');
    $blog_url = $blog_page_id ? get_permalink($blog_page_id) : home_url('/blog/');

    echo '<ul id="primary-menu" class="nav-menu">';
    echo '<li class="menu-item"><a href="' . esc_url(home_url('/')) . '">' . __('Home', 'humanitarianblog') . '</a></li>';
    echo '<li class="menu-item"><a href="' . esc_url($blog_url) . '">' . __('Blog', 'humanitarianblog') . '</a></li>';
    echo '<li class="menu-item"><a href="' . esc_url(home_url('/about-us/')) . '">' . __('About Us', 'humanitarianblog') . '</a></li>';
    echo '<li class="menu-item"><a href="' . esc_url(home_url('/contact/')) . '">' . __('Contact', 'humanitarianblog') . '</a></li>';
    echo '<li class="menu-item"><a href="' . esc_url(home_url('/privacy-policy/')) . '">' . __('Privacy Policy', 'humanitarianblog') . '</a></li>';
    echo '</ul>';
}

/**
 * Breadcrumb Navigation
 */
function humanitarianblog_breadcrumb() {
    if (is_front_page()) {
        return;
    }

    $separator = '<span class="breadcrumb-separator">/</span>';
    $home_title = __('Home', 'humanitarianblog');

    echo '<nav class="breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'humanitarianblog') . '">';
    echo '<ol class="breadcrumb-list" itemscope itemtype="https://schema.org/BreadcrumbList">';

    // Home link
    echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
    echo '<a href="' . esc_url(home_url('/')) . '" itemprop="item"><span itemprop="name">' . esc_html($home_title) . '</span></a>';
    echo '<meta itemprop="position" content="1" />';
    echo '</li>';

    $position = 2;

    if (is_single()) {
        // Article type
        $article_type = humanitarianblog_get_article_type();
        if ($article_type) {
            echo $separator;
            echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            echo '<a href="' . esc_url($article_type['url']) . '" itemprop="item"><span itemprop="name">' . esc_html($article_type['name']) . '</span></a>';
            echo '<meta itemprop="position" content="' . $position++ . '" />';
            echo '</li>';
        } else {
            // Fallback to category
            $categories = get_the_category();
            if (!empty($categories)) {
                echo $separator;
                echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '" itemprop="item"><span itemprop="name">' . esc_html($categories[0]->name) . '</span></a>';
                echo '<meta itemprop="position" content="' . $position++ . '" />';
                echo '</li>';
            }
        }

        // Current post title
        echo $separator;
        echo '<li class="breadcrumb-item breadcrumb-current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(wp_trim_words(get_the_title(), 6, '...')) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';

    } elseif (is_category()) {
        echo $separator;
        echo '<li class="breadcrumb-item breadcrumb-current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . single_cat_title('', false) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';

    } elseif (is_tax('article_type')) {
        echo $separator;
        echo '<li class="breadcrumb-item breadcrumb-current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . single_term_title('', false) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';

    } elseif (is_tax('region')) {
        echo $separator;
        echo '<li class="breadcrumb-item breadcrumb-current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . single_term_title('', false) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';

    } elseif (is_page()) {
        $ancestors = get_post_ancestors(get_the_ID());
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            foreach ($ancestors as $ancestor) {
                echo $separator;
                echo '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
                echo '<a href="' . esc_url(get_permalink($ancestor)) . '" itemprop="item"><span itemprop="name">' . esc_html(get_the_title($ancestor)) . '</span></a>';
                echo '<meta itemprop="position" content="' . $position++ . '" />';
                echo '</li>';
            }
        }
        echo $separator;
        echo '<li class="breadcrumb-item breadcrumb-current" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        echo '<span itemprop="name">' . esc_html(get_the_title()) . '</span>';
        echo '<meta itemprop="position" content="' . $position . '" />';
        echo '</li>';

    } elseif (is_search()) {
        echo $separator;
        echo '<li class="breadcrumb-item breadcrumb-current">';
        echo '<span>' . sprintf(__('Search: %s', 'humanitarianblog'), get_search_query()) . '</span>';
        echo '</li>';

    } elseif (is_author()) {
        echo $separator;
        echo '<li class="breadcrumb-item breadcrumb-current">';
        echo '<span>' . get_the_author() . '</span>';
        echo '</li>';
    }

    echo '</ol>';
    echo '</nav>';
}

/**
 * Calculate reading time for a post
 *
 * @param int $post_id Optional post ID
 * @return string Formatted reading time
 */
function humanitarianblog_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $content = get_post_field('post_content', $post_id);
    $word_count = str_word_count(strip_tags($content));
    $reading_time = ceil($word_count / 200); // Average reading speed: 200 words per minute

    if ($reading_time < 1) {
        $reading_time = 1;
    }

    return sprintf(
        _n('%d min read', '%d min read', $reading_time, 'humanitarianblog'),
        $reading_time
    );
}

/**
 * Generate Table of Contents from H2/H3 headings
 * Only shows if there are 3+ headings
 */
function humanitarianblog_table_of_contents($content) {
    if (!is_single()) {
        return $content;
    }

    // Find all h2 and h3 headings
    preg_match_all('/<h([23])([^>]*)>(.*?)<\/h[23]>/i', $content, $matches, PREG_SET_ORDER);

    // Only show TOC if 3 or more headings
    if (count($matches) < 3) {
        return $content;
    }

    $toc_items = array();
    $counter = 0;

    foreach ($matches as $match) {
        $level = $match[1];
        $attrs = $match[2];
        $title = strip_tags($match[3]);
        $counter++;

        // Generate unique ID
        $id = 'toc-' . $counter . '-' . sanitize_title($title);

        // Add ID to heading if not present
        if (strpos($attrs, 'id=') === false) {
            $new_heading = '<h' . $level . ' id="' . esc_attr($id) . '"' . $attrs . '>' . $match[3] . '</h' . $level . '>';
            $content = str_replace($match[0], $new_heading, $content);
        } else {
            // Extract existing ID
            preg_match('/id=["\']([^"\']+)["\']/', $attrs, $id_match);
            $id = $id_match[1];
        }

        $toc_items[] = array(
            'level' => $level,
            'id' => $id,
            'title' => $title
        );
    }

    // Build TOC HTML
    $toc_html = '<aside class="table-of-contents" id="table-of-contents">';
    $toc_html .= '<div class="toc-header">';
    $toc_html .= '<h4 class="toc-title">' . __('In This Article', 'humanitarianblog') . '</h4>';
    $toc_html .= '<button type="button" class="toc-toggle" aria-expanded="true" aria-controls="toc-list">';
    $toc_html .= '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>';
    $toc_html .= '</button>';
    $toc_html .= '</div>';
    $toc_html .= '<nav class="toc-nav"><ol class="toc-list" id="toc-list">';

    foreach ($toc_items as $item) {
        $indent_class = $item['level'] == '3' ? 'toc-item--indent' : '';
        $toc_html .= '<li class="toc-item ' . $indent_class . '">';
        $toc_html .= '<a href="#' . esc_attr($item['id']) . '">' . esc_html($item['title']) . '</a>';
        $toc_html .= '</li>';
    }

    $toc_html .= '</ol></nav></aside>';

    return $toc_html . $content;
}
add_filter('the_content', 'humanitarianblog_table_of_contents', 5);

/**
 * Get article type data (slug, name, color class)
 *
 * @param int $post_id Optional post ID
 * @return array|false Article type data or false if not set
 */
function humanitarianblog_get_article_type($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $terms = get_the_terms($post_id, 'article_type');
    if (!$terms || is_wp_error($terms)) {
        return false;
    }

    $term = $terms[0]; // Get first article type
    $slug = $term->slug;

    // Color mapping for article types
    $colors = array(
        'news'             => 'blue',
        'opinion'          => 'orange',
        'investigation'    => 'purple',
        'in-depth-analysis' => 'green',
        'feature'          => 'yellow',
        'breaking'         => 'red',
    );

    return array(
        'slug'  => $slug,
        'name'  => $term->name,
        'color' => isset($colors[$slug]) ? $colors[$slug] : 'blue',
        'url'   => get_term_link($term),
    );
}

/**
 * Check if post is breaking news
 *
 * @param int $post_id Optional post ID
 * @return bool
 */
function humanitarianblog_is_breaking($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $terms = get_the_terms($post_id, 'article_type');
    if (!$terms || is_wp_error($terms)) {
        return false;
    }

    foreach ($terms as $term) {
        if ($term->slug === 'breaking') {
            return true;
        }
    }

    return false;
}

/**
 * Register Widget Areas
 */
function humanitarianblog_widgets_init() {

    register_sidebar(array(
        'name'          => __('Sidebar', 'humanitarianblog'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'humanitarianblog'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'humanitarianblog'),
        'id'            => 'footer-1',
        'description'   => __('Appears in the footer section.', 'humanitarianblog'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'humanitarianblog'),
        'id'            => 'footer-2',
        'description'   => __('Appears in the footer section.', 'humanitarianblog'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'humanitarianblog'),
        'id'            => 'footer-3',
        'description'   => __('Appears in the footer section.', 'humanitarianblog'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 4', 'humanitarianblog'),
        'id'            => 'footer-4',
        'description'   => __('Appears in the footer section.', 'humanitarianblog'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'humanitarianblog_widgets_init');

/**
 * Enqueue Scripts and Styles
 */
function humanitarianblog_enqueue_scripts() {

    // Google Fonts
    wp_enqueue_style(
        'humanitarianblog-fonts',
        humanitarianblog_fonts_url(),
        array(),
        null
    );

    // Main stylesheet (from root style.css - WordPress standard)
    wp_enqueue_style(
        'humanitarianblog-style',
        get_stylesheet_uri(),
        array('humanitarianblog-fonts'),
        HUMANITARIAN_THEME_VERSION
    );

    // RTL support
    if (is_rtl()) {
        wp_enqueue_style(
            'humanitarianblog-rtl',
            HUMANITARIAN_THEME_URI . '/assets/css/rtl.css',
            array('humanitarianblog-style'),
            HUMANITARIAN_THEME_VERSION
        );
    }

    // Print styles
    wp_enqueue_style(
        'humanitarianblog-print',
        HUMANITARIAN_THEME_URI . '/assets/css/print.css',
        array(),
        HUMANITARIAN_THEME_VERSION,
        'print'
    );

    // Main JavaScript
    wp_enqueue_script(
        'humanitarianblog-main',
        HUMANITARIAN_THEME_URI . '/assets/js/main.js',
        array(),
        HUMANITARIAN_THEME_VERSION,
        true
    );

    // Search JavaScript
    wp_enqueue_script(
        'humanitarianblog-search',
        HUMANITARIAN_THEME_URI . '/assets/js/search.js',
        array(),
        HUMANITARIAN_THEME_VERSION,
        true
    );

    // Reading Experience (single posts only)
    if (is_singular('post')) {
        wp_enqueue_script(
            'humanitarianblog-reading',
            HUMANITARIAN_THEME_URI . '/assets/js/reading-experience.js',
            array(),
            HUMANITARIAN_THEME_VERSION,
            true
        );

        wp_enqueue_script(
            'humanitarianblog-audio',
            HUMANITARIAN_THEME_URI . '/assets/js/audio-player.js',
            array(),
            HUMANITARIAN_THEME_VERSION,
            true
        );
    }

    // Bookmarks Page (only on page-bookmarks.php template)
    if (is_page_template('page-bookmarks.php')) {
        wp_enqueue_script(
            'humanitarianblog-bookmarks-page',
            HUMANITARIAN_THEME_URI . '/assets/js/bookmarks-page.js',
            array(),
            HUMANITARIAN_THEME_VERSION,
            true
        );
    }

    // Modals
    wp_enqueue_script(
        'humanitarianblog-modals',
        HUMANITARIAN_THEME_URI . '/assets/js/modals.js',
        array(),
        HUMANITARIAN_THEME_VERSION,
        true
    );

    // Accessibility Features (for elderly readers)
    wp_enqueue_script(
        'humanitarianblog-accessibility',
        HUMANITARIAN_THEME_URI . '/assets/js/accessibility.js',
        array(),
        HUMANITARIAN_THEME_VERSION,
        false // Load in head to prevent FOUC
    );

    // Comments script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Localize script for AJAX
    wp_localize_script('humanitarianblog-main', 'humanitarianBlogAjax', array(
        'ajax_url'      => admin_url('admin-ajax.php'),
        'nonce'         => wp_create_nonce('humanitarian_nonce'),
        'search_nonce'  => wp_create_nonce('search_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'humanitarianblog_enqueue_scripts');

/**
 * Add custom body classes
 */
function humanitarianblog_body_classes($classes) {
    // Add single-post class for single posts (compatibility with reading-experience.js)
    if (is_singular('post')) {
        $classes[] = 'single-post';
    }

    return $classes;
}
add_filter('body_class', 'humanitarianblog_body_classes');

/**
 * Custom Comment Callback
 */
function humanitarianblog_comment_callback($comment, $args, $depth) {
    $tag = ('div' === $args['style']) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent', $comment); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php
                    if (0 != $args['avatar_size']) {
                        echo get_avatar($comment, $args['avatar_size']);
                    }
                    ?>
                    <?php printf('<b class="fn">%s</b>', get_comment_author_link($comment)); ?>
                </div>

                <div class="comment-metadata">
                    <a href="<?php echo esc_url(get_comment_link($comment, $args)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php
                            printf(
                                '%1$s at %2$s',
                                get_comment_date('', $comment),
                                get_comment_time()
                            );
                            ?>
                        </time>
                    </a>
                    <?php edit_comment_link(__('Edit', 'humanitarianblog'), '<span class="edit-link">', '</span>'); ?>
                </div>

                <?php if ('0' == $comment->comment_approved) : ?>
                    <p class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'humanitarianblog'); ?></p>
                <?php endif; ?>
            </footer>

            <div class="comment-content">
                <?php comment_text(); ?>
            </div>

            <?php
            comment_reply_link(array_merge($args, [
                'add_below' => 'div-comment',
                'depth'     => $depth,
                'max_depth' => $args['max_depth'],
                'before'    => '<div class="reply">',
                'after'     => '</div>',
            ]));
            ?>
        </article>
    <?php
}

/**
 * Include required files
 */
require_once HUMANITARIAN_THEME_DIR . '/inc/custom-taxonomies.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/admin-simplify.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/ajax-handlers.php';

// Additional includes will be added in future phases
// require_once HUMANITARIAN_THEME_DIR . '/inc/template-functions.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/pdf-generator.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/qr-generator.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/email-verification.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/post-notifications.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/auto-translate.php';

/**
 * ==========================================================================
 * Photo Caption Meta Box (Foto Etiketi)
 * Allows authors to add a descriptive caption overlay on featured images
 * ==========================================================================
 */

// Register the meta box
function humanitarian_photo_caption_meta_box() {
    add_meta_box(
        'photo_caption_meta',
        __('Photo Caption / Foto Etiketi', 'humanitarianblog'),
        'humanitarian_photo_caption_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'humanitarian_photo_caption_meta_box');

// Meta box HTML
function humanitarian_photo_caption_callback($post) {
    wp_nonce_field('humanitarian_photo_caption_nonce', 'photo_caption_nonce');

    $caption = get_post_meta($post->ID, '_photo_caption', true);
    ?>
    <p>
        <label for="photo_caption" style="font-weight: 600; display: block; margin-bottom: 8px;">
            <?php _e('Image overlay text (appears on photo)', 'humanitarianblog'); ?>
        </label>
        <input
            type="text"
            id="photo_caption"
            name="photo_caption"
            value="<?php echo esc_attr($caption); ?>"
            class="widefat"
            placeholder="<?php esc_attr_e('e.g., Inside a displacement camp', 'humanitarianblog'); ?>"
            style="margin-bottom: 8px;"
        />
        <span class="description" style="color: #666; font-size: 12px;">
            <?php _e('Short, descriptive text (max 50 chars). Appears on the featured image.', 'humanitarianblog'); ?>
        </span>
    </p>
    <?php
}

// Save meta box data
function humanitarian_save_photo_caption($post_id) {
    // Verify nonce
    if (!isset($_POST['photo_caption_nonce']) || !wp_verify_nonce($_POST['photo_caption_nonce'], 'humanitarian_photo_caption_nonce')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save or delete
    if (isset($_POST['photo_caption'])) {
        $caption = sanitize_text_field($_POST['photo_caption']);
        // Limit to 50 characters
        $caption = mb_substr($caption, 0, 50);
        update_post_meta($post_id, '_photo_caption', $caption);
    }
}
add_action('save_post', 'humanitarian_save_photo_caption');

/**
 * Get photo caption for a post
 *
 * @param int $post_id Optional post ID
 * @return string Photo caption or empty string
 */
function humanitarian_get_photo_caption($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    return get_post_meta($post_id, '_photo_caption', true);
}

// Demo content generator (REMOVE AFTER USE)
/**
 * Demo Content Generator - Admin Version
 *
 * Temporary function to create demo content - REMOVE AFTER USE
 */

add_action('admin_menu', 'humanitarian_demo_content_menu');

function humanitarian_demo_content_menu() {
    add_management_page(
        'Create Demo Content',
        'Create Demo Content',
        'manage_options',
        'humanitarian-demo-content',
        'humanitarian_create_demo_content_page'
    );
}

function humanitarian_create_demo_content_page() {

    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }

    // Check if already created
    if (get_option('humanitarian_demo_content_created') && !isset($_GET['force'])) {
        echo '<div class="notice notice-warning"><p>Demo content already created! <a href="?page=humanitarian-demo-content&force=1">Force recreate?</a></p></div>';
        return;
    }

    echo '<div class="wrap">';
    echo '<h1>🚀 Creating Demo Content...</h1>';

    /**
     * Demo Articles Data
     */
    $demo_articles = [
        [
            'title' => 'Humanitarian Crisis Deepens in Northern Syria as Winter Approaches',
            'content' => '<p>As temperatures drop across northern Syria, humanitarian organizations are warning of a potential catastrophe facing displaced populations in makeshift camps along the Turkish border.</p>

<p>More than 2.5 million people remain displaced in northwest Syria, with many living in tents that offer little protection against harsh winter conditions.</p>

<h2>Urgent Needs</h2>

<p>"We are in a race against time," said Maria Rodriguez, country director for a major international NGO. "Without immediate intervention, we could see preventable deaths from hypothermia."</p>

<p>The United Nations has appealed for $400 million in emergency funding to provide winter assistance.</p>',
            'category' => 'News',
            'article_type' => 'News',
            'region' => 'Middle East',
            'tags' => ['Syria', 'Winter Emergency', 'Humanitarian Crisis'],
            'excerpt' => 'More than 2.5 million displaced people face harsh winter conditions in northwest Syria.',
            'photo_caption' => 'Displaced families in Idlib camps',
        ],
        [
            'title' => 'Why Climate Finance Must Prioritize Frontline Communities',
            'content' => '<p>As world leaders gather for yet another climate summit, the conversation around climate finance continues to miss the mark.</p>

<p><em>By Sarah Johnson, Climate Policy Analyst</em></p>

<h2>The Broken Promise</h2>

<p>In 2009, wealthy nations promised to mobilize $100 billion annually by 2020 to help developing countries adapt to climate change. That target was missed.</p>

<p>Small island nations facing existential threats. Indigenous communities watching their lands transform. These are the people who contributed least to the climate crisis but suffer most.</p>

<blockquote>"We don\'t need another conference room promise. We need resources in the hands of people who know their land." - Pacific Islands activist</blockquote>',
            'category' => 'Opinion',
            'article_type' => 'Opinion',
            'region' => 'Global',
            'tags' => ['Climate Change', 'Climate Finance', 'Environmental Justice'],
            'excerpt' => 'Current climate finance mechanisms fail frontline communities. We need direct funding and local knowledge.',
            'photo_caption' => 'Pacific island facing rising seas',
        ],
        [
            'title' => 'How Social Media Shapes Modern Humanitarian Response',
            'content' => '<p>The relationship between social media and humanitarian action has evolved dramatically, fundamentally changing how crises are documented and how aid is mobilized.</p>

<h2>Real-Time Documentation</h2>

<p>Today, anyone with a smartphone can document emergencies as they unfold. During the 2023 Turkey-Syria earthquake, social media became critical for coordinating rescue efforts.</p>

<h2>The Verification Challenge</h2>

<p>But misinformation spreads rapidly. Humanitarian organizations now employ dedicated teams to verify information and combat false narratives.</p>

<p>Social media is now inescapable in humanitarian response. The question is how to use it ethically and effectively.</p>',
            'category' => 'Analysis',
            'article_type' => 'Analysis',
            'region' => 'Global',
            'tags' => ['Social Media', 'Technology', 'Digital Humanitarianism'],
            'excerpt' => 'Social media has fundamentally transformed humanitarian response, bringing both opportunities and challenges.',
            'photo_caption' => 'Aid workers coordinating via mobile',
        ],
        [
            'title' => 'Inside Yemen\'s Hidden Hunger Crisis',
            'content' => '<p>In the port city of Hodeidah, Amina cradles her daughter, whose skeletal frame tells a story statistics cannot convey. They are among the 17 million Yemenis facing acute food insecurity.</p>

<h2>A Perfect Storm</h2>

<p>Yemen\'s hunger crisis results from converging factors: nearly a decade of conflict, economic collapse, infrastructure destruction, and climate shocks.</p>

<p>"We are witnessing a slow-motion famine," explains Dr. Ahmed Hassan. "Families reduce meals, switch to less nutritious foods. By the time they reach us, malnutrition is severe."</p>

<h2>Children Bear the Burden</h2>

<p>UNICEF estimates 2.2 million children under five suffer from acute malnutrition. Therapeutic feeding centers can only accommodate a fraction of those in need.</p>',
            'category' => 'Report',
            'article_type' => 'Report',
            'region' => 'Middle East',
            'tags' => ['Yemen', 'Hunger', 'Food Security'],
            'excerpt' => 'An investigation into Yemen\'s severe hunger crisis, where 17 million face acute food insecurity.',
            'photo_caption' => 'Therapeutic feeding center in Hodeidah',
        ],
        [
            'title' => 'UNHCR Chief on the Global Refugee Crisis at Record Levels',
            'content' => '<p>Filippo Grandi, the UN High Commissioner for Refugees, discusses the unprecedented displacement crisis with more than 110 million people forcibly displaced globally.</p>

<p><strong>Q: What\'s driving this trend?</strong></p>

<p><strong>Filippo Grandi:</strong> We\'re dealing with a fundamental shift. Conflicts are lasting longer - the average refugee spends 20 years in displacement. Syria, Afghanistan, South Sudan - these persist year after year.</p>

<p>But it\'s not just conflict. Climate change is a massive displacement driver we\'re only beginning to understand.</p>

<p><strong>Q: How is UNHCR adapting?</strong></p>

<p><strong>FG:</strong> The traditional refugee response model of camps cannot be the long-term answer for 110 million people. We need to think differently about solutions.</p>

<blockquote>"Every time I visit a camp, I meet doctors, engineers, teachers who just want to rebuild their lives. We\'re wasting human potential on a massive scale."</blockquote>',
            'category' => 'Interview',
            'article_type' => 'Interview',
            'region' => 'Global',
            'tags' => ['Refugees', 'UNHCR', 'Migration'],
            'excerpt' => 'UNHCR Chief discusses the unprecedented global displacement crisis and why traditional responses must evolve.',
            'photo_caption' => 'Interview at UN headquarters',
        ],
        [
            'title' => 'The Women Rebuilding Healthcare in Post-Conflict Liberia',
            'content' => '<p>In a small clinic outside Monrovia, Dr. Grace Williams examines a pregnant woman while training medical students. This represents something extraordinary: the rebuilding of healthcare decimated by civil war and Ebola.</p>

<p>What makes it remarkable is that Dr. Williams and her entire team are women - part of a generation rebuilding Liberia\'s health infrastructure from the ground up.</p>

<h2>Starting from Ruins</h2>

<p>"When I returned from medical school in 2005, I found hospitals without electricity, water, or medicines," Dr. Williams recalls. "But most striking was the absence of women in healthcare leadership."</p>

<h2>Breaking Barriers</h2>

<p>Mary Johnson was 19 when she began training as a community health worker in a refugee camp. Today, at 38, she manages a health center serving 50,000 people.</p>

<blockquote>"You can\'t parachute in healthcare from outside. It has to be rooted in the community." - Dr. Grace Williams</blockquote>

<h2>Maternal Health Priority</h2>

<p>Liberia had one of the world\'s highest maternal mortality rates. The network of women health workers made maternal health their flagship initiative. Maternal mortality has dropped by more than half.</p>',
            'category' => 'Feature',
            'article_type' => 'Feature',
            'region' => 'Africa',
            'tags' => ['Liberia', 'Healthcare', 'Women', 'Post-Conflict'],
            'excerpt' => 'Women health workers are rebuilding Liberia\'s shattered healthcare system, transforming maternal health.',
            'photo_caption' => 'Dr. Williams at community clinic',
        ],
    ];

    // Get admin user
    $admin = get_users(['role' => 'administrator', 'number' => 1]);
    $author_id = $admin[0]->ID;

    $created = 0;

    foreach ($demo_articles as $index => $article) {

        // Get or create category
        $cat = get_category_by_slug(sanitize_title($article['category']));
        if (!$cat) {
            $cat_id = wp_create_category($article['category']);
            echo '<p>✓ Created category: ' . esc_html($article['category']) . '</p>';
        } else {
            $cat_id = $cat->term_id;
        }

        // Create post
        $post_id = wp_insert_post([
            'post_title'    => $article['title'],
            'post_content'  => $article['content'],
            'post_excerpt'  => $article['excerpt'],
            'post_status'   => 'publish',
            'post_author'   => $author_id,
            'post_category' => [$cat_id],
            'post_date'     => date('Y-m-d H:i:s', strtotime('-' . ($index * 2) . ' days')),
            'tags_input'    => $article['tags'],
        ]);

        if ($post_id) {
            // Set taxonomies
            $at = get_term_by('slug', sanitize_title($article['article_type']), 'article_type');
            if ($at) wp_set_object_terms($post_id, $at->term_id, 'article_type');

            $rg = get_term_by('slug', sanitize_title($article['region']), 'region');
            if ($rg) wp_set_object_terms($post_id, $rg->term_id, 'region');

            // Reading time
            $words = str_word_count(strip_tags($article['content']));
            update_post_meta($post_id, 'reading_time', ceil($words / 200));

            // Photo caption (foto etiketi)
            if (!empty($article['photo_caption'])) {
                update_post_meta($post_id, '_photo_caption', $article['photo_caption']);
            }

            echo '<p style="color: green;">✓ Created: ' . esc_html($article['title']) . '</p>';
            $created++;
        }
    }

    update_option('humanitarian_demo_content_created', true);

    echo '<div class="notice notice-success"><p><strong>✅ Success! Created ' . $created . ' demo articles.</strong></p></div>';
    echo '<p><a href="' . home_url() . '" class="button button-primary">View Site</a></p>';
    echo '<p><a href="edit.php" class="button">View Posts</a></p>';

    echo '</div>';
}

/**
 * Admin Tool: Add Photo Captions to Existing Posts
 * Run once via Tools menu, then remove
 */
add_action('admin_menu', 'humanitarian_add_captions_menu');

function humanitarian_add_captions_menu() {
    add_management_page(
        'Add Photo Captions',
        'Add Photo Captions',
        'manage_options',
        'humanitarian-add-captions',
        'humanitarian_add_captions_page'
    );
}

function humanitarian_add_captions_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }

    // Photo captions mapping by post title keywords
    $caption_map = [
        'syria' => 'Displaced families in Idlib camps',
        'climate' => 'Pacific island facing rising seas',
        'social media' => 'Aid workers coordinating via mobile',
        'yemen' => 'Therapeutic feeding center in Hodeidah',
        'unhcr' => 'Interview at UN headquarters',
        'refugee' => 'Families waiting at border crossing',
        'liberia' => 'Dr. Williams at community clinic',
        'healthcare' => 'Community health worker on duty',
        'women' => 'Women-led health initiative',
        'hunger' => 'Food distribution point',
        'crisis' => 'Emergency response in action',
        'conflict' => 'Aftermath of recent fighting',
        'migration' => 'Journey to safety',
        'aid' => 'Humanitarian aid delivery',
    ];

    echo '<div class="wrap">';
    echo '<h1>Add Photo Captions to Posts</h1>';

    if (isset($_GET['run'])) {
        $posts = get_posts([
            'numberposts' => -1,
            'post_type' => 'post',
            'post_status' => 'publish',
        ]);

        $updated = 0;

        foreach ($posts as $post) {
            // Skip if already has caption
            $existing = get_post_meta($post->ID, '_photo_caption', true);
            if (!empty($existing)) {
                echo '<p>⏭️ Skipped (has caption): ' . esc_html($post->post_title) . '</p>';
                continue;
            }

            // Find matching caption
            $title_lower = strtolower($post->post_title);
            $caption = '';

            foreach ($caption_map as $keyword => $cap) {
                if (strpos($title_lower, $keyword) !== false) {
                    $caption = $cap;
                    break;
                }
            }

            if ($caption) {
                update_post_meta($post->ID, '_photo_caption', $caption);
                echo '<p style="color: green;">✓ Added caption to: ' . esc_html($post->post_title) . ' → "' . $caption . '"</p>';
                $updated++;
            } else {
                echo '<p style="color: orange;">⚠️ No match for: ' . esc_html($post->post_title) . '</p>';
            }
        }

        echo '<div class="notice notice-success"><p><strong>✅ Updated ' . $updated . ' posts with photo captions.</strong></p></div>';
    } else {
        echo '<p>This will add photo captions to existing posts based on their titles.</p>';
        echo '<p><a href="?page=humanitarian-add-captions&run=1" class="button button-primary">Run Now</a></p>';
    }

    echo '</div>';
}

/**
 * Custom Login Failure Redirect
 * Redirect failed login attempts to the frontend login page
 */
function humanitarianblog_login_failed($username) {
    // Get the referrer to determine if login came from frontend
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    // Check if login came from frontend login page
    if (strpos($referrer, '/login/') !== false || strpos($referrer, 'wp-login.php') !== false) {
        wp_redirect(home_url('/login/?login=failed'));
        exit;
    }
}
add_action('wp_login_failed', 'humanitarianblog_login_failed');

/**
 * Custom Empty Login Redirect
 * Redirect empty login attempts to the frontend login page
 */
function humanitarianblog_authenticate_empty($user, $username, $password) {
    // Get the referrer
    $referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';

    // Check if login came from frontend login page
    if (strpos($referrer, '/login/') !== false) {
        if (empty($username) || empty($password)) {
            wp_redirect(home_url('/login/?login=empty'));
            exit;
        }
    }

    return $user;
}
add_filter('authenticate', 'humanitarianblog_authenticate_empty', 30, 3);

/**
 * Redirect subscribers away from wp-admin
 * Subscribers should use frontend account page
 */
function humanitarianblog_redirect_subscribers() {
    if (is_admin() && !defined('DOING_AJAX') && current_user_can('subscriber') && !current_user_can('edit_posts')) {
        wp_redirect(home_url('/my-account/'));
        exit;
    }
}
add_action('admin_init', 'humanitarianblog_redirect_subscribers');

/**
 * Custom login redirect based on user role
 */
function humanitarianblog_login_redirect($redirect_to, $request, $user) {
    // Is there a user to check?
    if (isset($user->roles) && is_array($user->roles)) {
        // Check for subscribers - send to frontend account
        if (in_array('subscriber', $user->roles)) {
            return home_url('/my-account/');
        }
    }

    return $redirect_to;
}
add_filter('login_redirect', 'humanitarianblog_login_redirect', 10, 3);

/**
 * Enqueue bookmark nonce for AJAX
 */
function humanitarianblog_bookmark_scripts() {
    if (is_single() && is_user_logged_in()) {
        wp_localize_script('humanitarianblog-main', 'humanitarianBookmark', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bookmark_nonce'),
            'isLoggedIn' => true,
            'loginUrl' => home_url('/login/'),
        ));
    } elseif (is_single()) {
        wp_localize_script('humanitarianblog-main', 'humanitarianBookmark', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => '',
            'isLoggedIn' => false,
            'loginUrl' => home_url('/login/'),
        ));
    }
}
add_action('wp_enqueue_scripts', 'humanitarianblog_bookmark_scripts', 20);

/**
 * ============================================
 * AUTHOR PROFILE CUSTOM FIELDS
 * Additional fields for author pages
 * ============================================
 */

/**
 * Add custom contact methods to user profile
 */
function humanitarian_custom_contact_methods($contactmethods) {
    $contactmethods['twitter'] = __('Twitter Username (without @)', 'humanitarianblog');
    $contactmethods['linkedin'] = __('LinkedIn Profile URL', 'humanitarianblog');
    $contactmethods['facebook'] = __('Facebook Profile URL', 'humanitarianblog');
    $contactmethods['instagram'] = __('Instagram Username (without @)', 'humanitarianblog');
    $contactmethods['author_website'] = __('Personal Website', 'humanitarianblog');

    return $contactmethods;
}
add_filter('user_contactmethods', 'humanitarian_custom_contact_methods');

/**
 * Add custom profile fields for authors
 */
function humanitarian_extra_profile_fields($user) {
    // Only show for users who can edit posts
    if (!current_user_can('edit_posts')) {
        return;
    }
    ?>
    <h3><?php _e('Author Profile Information', 'humanitarianblog'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="user_title"><?php _e('Title / Role', 'humanitarianblog'); ?></label></th>
            <td>
                <input type="text" name="user_title" id="user_title"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'user_title', true)); ?>"
                       class="regular-text" />
                <p class="description"><?php _e('e.g., Senior Correspondent, Editor-in-Chief, Investigative Journalist', 'humanitarianblog'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="location"><?php _e('Location', 'humanitarianblog'); ?></label></th>
            <td>
                <input type="text" name="location" id="location"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'location', true)); ?>"
                       class="regular-text" />
                <p class="description"><?php _e('e.g., Istanbul, Turkey or Middle East Bureau', 'humanitarianblog'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="cover_image"><?php _e('Cover Image URL', 'humanitarianblog'); ?></label></th>
            <td>
                <input type="url" name="cover_image" id="cover_image"
                       value="<?php echo esc_url(get_user_meta($user->ID, 'cover_image', true)); ?>"
                       class="regular-text" />
                <p class="description"><?php _e('URL of the cover/banner image for your author page (1200x400 recommended)', 'humanitarianblog'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="expertise"><?php _e('Areas of Expertise', 'humanitarianblog'); ?></label></th>
            <td>
                <input type="text" name="expertise" id="expertise"
                       value="<?php echo esc_attr(get_user_meta($user->ID, 'expertise', true)); ?>"
                       class="regular-text" />
                <p class="description"><?php _e('Comma-separated: e.g., Refugee Crisis, Human Rights, Middle East Politics', 'humanitarianblog'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'humanitarian_extra_profile_fields');
add_action('edit_user_profile', 'humanitarian_extra_profile_fields');

/**
 * Save custom profile fields
 */
function humanitarian_save_extra_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    $fields = array('user_title', 'location', 'cover_image', 'expertise');

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            if ($field === 'cover_image') {
                update_user_meta($user_id, $field, esc_url_raw($_POST[$field]));
            } else {
                update_user_meta($user_id, $field, sanitize_text_field($_POST[$field]));
            }
        }
    }
}
add_action('personal_options_update', 'humanitarian_save_extra_profile_fields');
add_action('edit_user_profile_update', 'humanitarian_save_extra_profile_fields');

/**
 * Get total views for an author's posts
 */
function humanitarian_get_author_total_views($author_id) {
    $args = array(
        'author' => $author_id,
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    $posts = get_posts($args);
    $total_views = 0;

    foreach ($posts as $post_id) {
        $views = get_post_meta($post_id, '_post_views_count', true);
        if ($views) {
            $total_views += intval($views);
        }
    }

    return $total_views;
}

/**
 * ============================================
 * SMTP CONFIGURATION FOR EMAIL
 * Uses SiteGround SMTP server
 * ============================================
 */

/**
 * Configure PHPMailer to use SMTP
 */
function humanitarian_configure_smtp($phpmailer) {
    // Only configure if SMTP constants are defined
    if (!defined('SMTP_HOST') || !defined('SMTP_USER') || !defined('SMTP_PASS')) {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host       = SMTP_HOST;
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Port       = defined('SMTP_PORT') ? SMTP_PORT : 465;
    $phpmailer->Username   = SMTP_USER;
    $phpmailer->Password   = SMTP_PASS;
    $phpmailer->SMTPSecure = defined('SMTP_SECURE') ? SMTP_SECURE : 'ssl';

    // From address
    if (defined('SMTP_FROM_EMAIL')) {
        $phpmailer->From = SMTP_FROM_EMAIL;
    }
    if (defined('SMTP_FROM_NAME')) {
        $phpmailer->FromName = SMTP_FROM_NAME;
    }

    // Debug mode (disable in production)
    // $phpmailer->SMTPDebug = 2;
}
add_action('phpmailer_init', 'humanitarian_configure_smtp');

/**
 * Test email function (for admin use)
 */
function humanitarian_test_email_admin_page() {
    add_management_page(
        __('Test Email', 'humanitarianblog'),
        __('Test Email', 'humanitarianblog'),
        'manage_options',
        'test-email',
        'humanitarian_test_email_page'
    );
}
add_action('admin_menu', 'humanitarian_test_email_admin_page');

function humanitarian_test_email_page() {
    if (!current_user_can('manage_options')) {
        wp_die('Access denied');
    }

    echo '<div class="wrap">';
    echo '<h1>' . __('Test Email Configuration', 'humanitarianblog') . '</h1>';

    // Show current configuration
    echo '<h2>' . __('Current SMTP Settings', 'humanitarianblog') . '</h2>';
    echo '<table class="widefat" style="max-width: 600px;">';
    echo '<tr><th>SMTP Host</th><td>' . (defined('SMTP_HOST') ? SMTP_HOST : '<span style="color:red;">Not configured</span>') . '</td></tr>';
    echo '<tr><th>SMTP Port</th><td>' . (defined('SMTP_PORT') ? SMTP_PORT : '<span style="color:red;">Not configured</span>') . '</td></tr>';
    echo '<tr><th>SMTP User</th><td>' . (defined('SMTP_USER') ? SMTP_USER : '<span style="color:red;">Not configured</span>') . '</td></tr>';
    echo '<tr><th>SMTP Pass</th><td>' . (defined('SMTP_PASS') ? '********' : '<span style="color:red;">Not configured</span>') . '</td></tr>';
    echo '<tr><th>DeepL API Key</th><td>' . (defined('DEEPL_API_KEY') ? substr(DEEPL_API_KEY, 0, 10) . '...' : '<span style="color:red;">Not configured</span>') . '</td></tr>';
    echo '</table>';

    // Test email form
    if (isset($_POST['send_test_email']) && wp_verify_nonce($_POST['test_email_nonce'], 'send_test_email')) {
        $to = sanitize_email($_POST['test_email_to']);

        if (is_email($to)) {
            $subject = __('Test Email from Humanitarian Blog', 'humanitarianblog');
            $message = '<html><body>';
            $message .= '<h2>' . __('Test Email Successful!', 'humanitarianblog') . '</h2>';
            $message .= '<p>' . __('If you are reading this, your SMTP configuration is working correctly.', 'humanitarianblog') . '</p>';
            $message .= '<p><strong>' . __('Server:', 'humanitarianblog') . '</strong> ' . SMTP_HOST . '</p>';
            $message .= '<p><strong>' . __('Sent at:', 'humanitarianblog') . '</strong> ' . current_time('mysql') . '</p>';
            $message .= '</body></html>';

            $headers = array('Content-Type: text/html; charset=UTF-8');

            $sent = wp_mail($to, $subject, $message, $headers);

            if ($sent) {
                echo '<div class="notice notice-success"><p>' . sprintf(__('Test email sent to %s!', 'humanitarianblog'), esc_html($to)) . '</p></div>';
            } else {
                echo '<div class="notice notice-error"><p>' . __('Failed to send test email. Check your SMTP settings.', 'humanitarianblog') . '</p></div>';
            }
        } else {
            echo '<div class="notice notice-error"><p>' . __('Invalid email address.', 'humanitarianblog') . '</p></div>';
        }
    }

    ?>
    <h2><?php _e('Send Test Email', 'humanitarianblog'); ?></h2>
    <form method="post" style="max-width: 400px;">
        <?php wp_nonce_field('send_test_email', 'test_email_nonce'); ?>
        <table class="form-table">
            <tr>
                <th><label for="test_email_to"><?php _e('Send to:', 'humanitarianblog'); ?></label></th>
                <td>
                    <input type="email" name="test_email_to" id="test_email_to"
                           value="<?php echo esc_attr(wp_get_current_user()->user_email); ?>"
                           class="regular-text" required />
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="send_test_email" class="button button-primary"
                   value="<?php esc_attr_e('Send Test Email', 'humanitarianblog'); ?>" />
        </p>
    </form>
    <?php

    echo '</div>';
}
