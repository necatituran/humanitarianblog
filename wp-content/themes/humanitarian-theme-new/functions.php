<?php
/**
 * HumanitarianBlog Theme Functions
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define theme constants
 */
define('HUMANITARIAN_VERSION', '1.0.0');
define('HUMANITARIAN_DIR', get_template_directory());
define('HUMANITARIAN_URI', get_template_directory_uri());

/**
 * Include required files
 */
require_once HUMANITARIAN_DIR . '/inc/theme-setup.php';
require_once HUMANITARIAN_DIR . '/inc/template-tags.php';
require_once HUMANITARIAN_DIR . '/inc/customizer.php';
require_once HUMANITARIAN_DIR . '/inc/admin-cleanup.php';
require_once HUMANITARIAN_DIR . '/inc/gutenberg.php';
require_once HUMANITARIAN_DIR . '/inc/demo-content.php';
require_once HUMANITARIAN_DIR . '/inc/custom-taxonomies.php';

// Simple language system (replaces Polylang)
require_once HUMANITARIAN_DIR . '/inc/simple-language.php';

// Additional functionality
require_once HUMANITARIAN_DIR . '/inc/ajax-handlers.php';
require_once HUMANITARIAN_DIR . '/inc/shortcodes.php';
require_once HUMANITARIAN_DIR . '/inc/frontend-auth.php';
// DISABLED: Causes infinite post creation loop
// require_once HUMANITARIAN_DIR . '/inc/auto-translate.php';
// DISABLED: Needs update for simple-language system
// require_once HUMANITARIAN_DIR . '/inc/deepl-translation.php';
require_once HUMANITARIAN_DIR . '/inc/email-verification.php';
require_once HUMANITARIAN_DIR . '/inc/pdf-generator.php';
require_once HUMANITARIAN_DIR . '/inc/post-notifications.php';
require_once HUMANITARIAN_DIR . '/inc/qr-generator.php';
require_once HUMANITARIAN_DIR . '/inc/admin-simplify.php';
require_once HUMANITARIAN_DIR . '/inc/translations-panel.php';

/**
 * Enqueue scripts and styles
 */
function humanitarian_enqueue_assets() {
    // Google Fonts
    wp_enqueue_style(
        'humanitarian-google-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400&display=swap',
        array(),
        null
    );

    // Main stylesheet
    wp_enqueue_style(
        'humanitarian-style',
        get_stylesheet_uri(),
        array('humanitarian-google-fonts'),
        HUMANITARIAN_VERSION
    );

    // Main JavaScript
    wp_enqueue_script(
        'humanitarian-main',
        HUMANITARIAN_URI . '/assets/js/main.js',
        array(),
        HUMANITARIAN_VERSION,
        true
    );

    // Accessibility JavaScript
    wp_enqueue_script(
        'humanitarian-accessibility',
        HUMANITARIAN_URI . '/assets/js/accessibility.js',
        array(),
        HUMANITARIAN_VERSION,
        true
    );

    // Audio Player (for voice article feature)
    wp_enqueue_script(
        'humanitarian-audio-player',
        HUMANITARIAN_URI . '/assets/js/audio-player.js',
        array(),
        HUMANITARIAN_VERSION,
        true
    );

    // Modals JavaScript
    wp_enqueue_script(
        'humanitarian-modals',
        HUMANITARIAN_URI . '/assets/js/modals.js',
        array(),
        HUMANITARIAN_VERSION,
        true
    );

    // Reading Experience JavaScript
    if (is_singular('post')) {
        wp_enqueue_script(
            'humanitarian-reading',
            HUMANITARIAN_URI . '/assets/js/reading-experience.js',
            array(),
            HUMANITARIAN_VERSION,
            true
        );
    }

    // Search JavaScript
    wp_enqueue_script(
        'humanitarian-search',
        HUMANITARIAN_URI . '/assets/js/search.js',
        array(),
        HUMANITARIAN_VERSION,
        true
    );

    // Localize scripts for AJAX
    wp_localize_script('humanitarian-main', 'humanitarianAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('humanitarian_nonce'),
        'homeUrl' => home_url(),
        'themeUrl' => HUMANITARIAN_URI,
    ));
}
add_action('wp_enqueue_scripts', 'humanitarian_enqueue_assets');

/**
 * Add preconnect for Google Fonts
 */
function humanitarian_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.googleapis.com',
        );
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        );
    }
    return $urls;
}
add_filter('wp_resource_hints', 'humanitarian_resource_hints', 10, 2);

/**
 * Add body classes
 */
function humanitarian_body_classes($classes) {
    // Add class for singular pages
    if (is_singular()) {
        $classes[] = 'singular';
    }

    // Add class for front page
    if (is_front_page()) {
        $classes[] = 'front-page';
    }

    // Add class for archive pages
    if (is_archive()) {
        $classes[] = 'archive-page';
    }

    return $classes;
}
add_filter('body_class', 'humanitarian_body_classes');

/**
 * Modify excerpt length
 */
function humanitarian_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'humanitarian_excerpt_length');

/**
 * Modify excerpt more text
 */
function humanitarian_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'humanitarian_excerpt_more');

/**
 * Add meta boxes for special post features
 */
function humanitarian_add_meta_boxes() {
    add_meta_box(
        'humanitarian_post_options',
        __('Post Options', 'humanitarian'),
        'humanitarian_post_options_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'humanitarian_add_meta_boxes');

/**
 * Meta box callback
 */
function humanitarian_post_options_callback($post) {
    wp_nonce_field('humanitarian_post_options', 'humanitarian_post_options_nonce');

    $is_featured = get_post_meta($post->ID, '_humanitarian_featured', true);
    $is_analysis = get_post_meta($post->ID, '_humanitarian_analysis', true);
    $is_editors_pick = get_post_meta($post->ID, '_humanitarian_editors_pick', true);

    ?>
    <p>
        <label>
            <input type="checkbox" name="humanitarian_featured" value="1" <?php checked($is_featured, '1'); ?>>
            <?php esc_html_e('Featured Article', 'humanitarian'); ?>
        </label>
    </p>
    <p class="description"><?php esc_html_e('Display in Featured Articles section below hero on homepage.', 'humanitarian'); ?></p>

    <p style="margin-top: 15px;">
        <label>
            <input type="checkbox" name="humanitarian_analysis" value="1" <?php checked($is_analysis, '1'); ?>>
            <?php esc_html_e('In-Depth Analysis', 'humanitarian'); ?>
        </label>
    </p>
    <p class="description"><?php esc_html_e('Display in the In-Depth Analysis section on homepage.', 'humanitarian'); ?></p>

    <p style="margin-top: 15px;">
        <label>
            <input type="checkbox" name="humanitarian_editors_pick" value="1" <?php checked($is_editors_pick, '1'); ?>>
            <?php esc_html_e("Editor's Pick", 'humanitarian'); ?>
        </label>
    </p>
    <p class="description"><?php esc_html_e("Display in the Editor's Picks section on homepage.", 'humanitarian'); ?></p>
    <?php
}

/**
 * Save meta box data
 */
function humanitarian_save_post_options($post_id) {
    // Check nonce
    if (!isset($_POST['humanitarian_post_options_nonce']) ||
        !wp_verify_nonce($_POST['humanitarian_post_options_nonce'], 'humanitarian_post_options')) {
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

    // Save featured option
    $featured = isset($_POST['humanitarian_featured']) ? '1' : '';
    update_post_meta($post_id, '_humanitarian_featured', $featured);

    // Save analysis option
    $analysis = isset($_POST['humanitarian_analysis']) ? '1' : '';
    update_post_meta($post_id, '_humanitarian_analysis', $analysis);

    // Save editors pick option
    $editors_pick = isset($_POST['humanitarian_editors_pick']) ? '1' : '';
    update_post_meta($post_id, '_humanitarian_editors_pick', $editors_pick);
}
add_action('save_post', 'humanitarian_save_post_options');

/**
 * Add image sizes note to media uploader
 */
function humanitarian_admin_notices() {
    $screen = get_current_screen();
    if ($screen && 'upload' === $screen->id) {
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <?php esc_html_e('Recommended image sizes for HumanitarianBlog theme:', 'humanitarian'); ?>
                <strong><?php esc_html_e('Hero: 1200x750px', 'humanitarian'); ?></strong>,
                <strong><?php esc_html_e('Cards: 800x600px', 'humanitarian'); ?></strong>,
                <strong><?php esc_html_e('Square: 400x400px', 'humanitarian'); ?></strong>
            </p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'humanitarian_admin_notices');

/**
 * Disable WordPress emoji scripts
 */
function humanitarian_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'humanitarian_disable_emojis');

/**
 * Remove WordPress version from head
 */
remove_action('wp_head', 'wp_generator');

/**
 * Add basic SEO meta tags
 */
function humanitarian_seo_meta() {
    if (is_singular()) {
        global $post;

        // Meta description
        $description = '';
        if (has_excerpt($post->ID)) {
            $description = get_the_excerpt($post->ID);
        } else {
            $description = wp_trim_words(strip_tags($post->post_content), 30, '...');
        }

        if ($description) {
            printf('<meta name="description" content="%s">' . "\n", esc_attr($description));
        }

        // Open Graph tags
        ?>
        <meta property="og:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?php echo esc_url(get_permalink()); ?>">
        <?php if (has_post_thumbnail()) : ?>
        <meta property="og:image" content="<?php echo esc_url(get_the_post_thumbnail_url($post->ID, 'large')); ?>">
        <?php endif; ?>
        <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <?php
    } elseif (is_front_page()) {
        $description = get_bloginfo('description');
        if ($description) {
            printf('<meta name="description" content="%s">' . "\n", esc_attr($description));
        }
        ?>
        <meta property="og:title" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <meta property="og:type" content="website">
        <meta property="og:url" content="<?php echo esc_url(home_url('/')); ?>">
        <meta property="og:site_name" content="<?php echo esc_attr(get_bloginfo('name')); ?>">
        <?php
    }
}
add_action('wp_head', 'humanitarian_seo_meta');

/**
 * Add Twitter card meta tags
 */
function humanitarian_twitter_meta() {
    if (is_singular()) {
        ?>
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="<?php echo esc_attr(get_the_title()); ?>">
        <?php if (has_excerpt()) : ?>
        <meta name="twitter:description" content="<?php echo esc_attr(get_the_excerpt()); ?>">
        <?php endif; ?>
        <?php if (has_post_thumbnail()) : ?>
        <meta name="twitter:image" content="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')); ?>">
        <?php endif; ?>
        <?php
    }
}
add_action('wp_head', 'humanitarian_twitter_meta');
