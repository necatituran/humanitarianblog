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
 * Google Fonts URL
 *
 * @return string Google Fonts URL
 */
function humanitarianblog_fonts_url() {
    $fonts_url = '';
    $fonts     = array();
    $subsets   = 'latin,latin-ext';

    // Source Serif 4 (Headlines)
    if ('off' !== _x('on', 'Source Serif 4 font: on or off', 'humanitarianblog')) {
        $fonts[] = 'Source Serif 4:ital,wght@0,400;0,600;0,700;1,400;1,600;1,700';
    }

    // Inter (Body & UI)
    if ('off' !== _x('on', 'Inter font: on or off', 'humanitarianblog')) {
        $fonts[] = 'Inter:wght@400;500;600;700';
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

    // Main stylesheet
    wp_enqueue_style(
        'humanitarianblog-style',
        HUMANITARIAN_THEME_URI . '/assets/css/style.css',
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
 * Include required files
 */
require_once HUMANITARIAN_THEME_DIR . '/inc/custom-taxonomies.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/admin-simplify.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/ajax-handlers.php';

// Additional includes will be added in future phases
// require_once HUMANITARIAN_THEME_DIR . '/inc/template-functions.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/pdf-generator.php';
require_once HUMANITARIAN_THEME_DIR . '/inc/qr-generator.php';
