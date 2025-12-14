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
