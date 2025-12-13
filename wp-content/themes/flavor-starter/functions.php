<?php
/**
 * Flavor Starter Theme Functions
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Define Constants
 */
define('FLAVOR_THEME_VERSION', '1.0.0');
define('FLAVOR_THEME_DIR', get_template_directory());
define('FLAVOR_THEME_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function flavor_starter_theme_setup() {

    // Make theme available for translation
    load_theme_textdomain('flavor-starter', FLAVOR_THEME_DIR . '/languages');

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
        'primary'   => __('Primary Menu', 'flavor-starter'),
        'footer'    => __('Footer Menu', 'flavor-starter'),
        'social'    => __('Social Links', 'flavor-starter'),
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
add_action('after_setup_theme', 'flavor_starter_theme_setup');

/**
 * Set the content width
 */
function flavor_starter_content_width() {
    $GLOBALS['content_width'] = apply_filters('flavor_starter_content_width', 800);
}
add_action('after_setup_theme', 'flavor_starter_content_width', 0);

/**
 * Register Widget Areas
 */
function flavor_starter_widgets_init() {

    register_sidebar(array(
        'name'          => __('Sidebar', 'flavor-starter'),
        'id'            => 'sidebar-1',
        'description'   => __('Add widgets here to appear in your sidebar.', 'flavor-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 1', 'flavor-starter'),
        'id'            => 'footer-1',
        'description'   => __('Appears in the footer section.', 'flavor-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 2', 'flavor-starter'),
        'id'            => 'footer-2',
        'description'   => __('Appears in the footer section.', 'flavor-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 3', 'flavor-starter'),
        'id'            => 'footer-3',
        'description'   => __('Appears in the footer section.', 'flavor-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));

    register_sidebar(array(
        'name'          => __('Footer Widget Area 4', 'flavor-starter'),
        'id'            => 'footer-4',
        'description'   => __('Appears in the footer section.', 'flavor-starter'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'flavor_starter_widgets_init');

/**
 * Enqueue Scripts and Styles
 */
function flavor_starter_enqueue_scripts() {

    // Main stylesheet
    wp_enqueue_style(
        'flavor-starter-style',
        FLAVOR_THEME_URI . '/assets/css/style.css',
        array(),
        FLAVOR_THEME_VERSION
    );

    // RTL support
    if (is_rtl()) {
        wp_enqueue_style(
            'flavor-starter-rtl',
            FLAVOR_THEME_URI . '/assets/css/rtl.css',
            array('flavor-starter-style'),
            FLAVOR_THEME_VERSION
        );
    }

    // Print styles
    wp_enqueue_style(
        'flavor-starter-print',
        FLAVOR_THEME_URI . '/assets/css/print.css',
        array(),
        FLAVOR_THEME_VERSION,
        'print'
    );

    // Main JavaScript
    wp_enqueue_script(
        'flavor-starter-main',
        FLAVOR_THEME_URI . '/assets/js/main.js',
        array(),
        FLAVOR_THEME_VERSION,
        true
    );

    // Comments script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Localize script for AJAX
    wp_localize_script('flavor-starter-main', 'flavorAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('flavor_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'flavor_starter_enqueue_scripts');

/**
 * Include required files
 */
require_once FLAVOR_THEME_DIR . '/inc/custom-taxonomies.php';
require_once FLAVOR_THEME_DIR . '/inc/admin-simplify.php';

// Additional includes will be added in future phases
// require_once FLAVOR_THEME_DIR . '/inc/template-functions.php';
// require_once FLAVOR_THEME_DIR . '/inc/ajax-handlers.php';
// require_once FLAVOR_THEME_DIR . '/inc/pdf-generator.php';
// require_once FLAVOR_THEME_DIR . '/inc/qr-generator.php';
