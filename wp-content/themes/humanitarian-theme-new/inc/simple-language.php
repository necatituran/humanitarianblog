<?php
/**
 * Simple Language System
 *
 * A lightweight multi-language solution without Polylang
 * Uses a custom 'language' taxonomy for posts
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Available languages
 */
function humanitarian_get_languages() {
    return array(
        'en' => array(
            'name' => 'English',
            'native' => 'English',
            'locale' => 'en_US',
            'rtl' => false,
            'flag' => 'us'
        ),
        'ar' => array(
            'name' => 'Arabic',
            'native' => 'العربية',
            'locale' => 'ar',
            'rtl' => true,
            'flag' => 'sa'
        ),
        'fr' => array(
            'name' => 'French',
            'native' => 'Français',
            'locale' => 'fr_FR',
            'rtl' => false,
            'flag' => 'fr'
        )
    );
}

/**
 * Register the language taxonomy
 */
function humanitarian_register_language_taxonomy() {
    $labels = array(
        'name'              => __('Languages', 'humanitarian'),
        'singular_name'     => __('Language', 'humanitarian'),
        'search_items'      => __('Search Languages', 'humanitarian'),
        'all_items'         => __('All Languages', 'humanitarian'),
        'edit_item'         => __('Edit Language', 'humanitarian'),
        'update_item'       => __('Update Language', 'humanitarian'),
        'add_new_item'      => __('Add New Language', 'humanitarian'),
        'new_item_name'     => __('New Language Name', 'humanitarian'),
        'menu_name'         => __('Languages', 'humanitarian'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'language'),
        'show_in_rest'      => true,
    );

    register_taxonomy('language', array('post', 'page'), $args);
}
add_action('init', 'humanitarian_register_language_taxonomy', 0);

/**
 * Create default language terms on theme activation
 */
function humanitarian_create_language_terms() {
    $languages = humanitarian_get_languages();

    foreach ($languages as $slug => $lang) {
        if (!term_exists($slug, 'language')) {
            wp_insert_term(
                $lang['native'],
                'language',
                array(
                    'slug' => $slug,
                    'description' => serialize(array(
                        'locale' => $lang['locale'],
                        'rtl' => $lang['rtl'],
                        'flag' => $lang['flag']
                    ))
                )
            );
        }
    }
}
add_action('after_switch_theme', 'humanitarian_create_language_terms');
add_action('init', 'humanitarian_create_language_terms', 10);

/**
 * Get current language from URL path, URL parameter, or cookie
 */
function humanitarian_get_current_language() {
    $languages = humanitarian_get_languages();

    // Check URL path first (e.g., /en/home/, /fr/article/, /ar/...)
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (preg_match('#^/([a-z]{2})(?:/|$)#', $request_uri, $matches)) {
        $path_lang = $matches[1];
        if (array_key_exists($path_lang, $languages)) {
            // Set cookie for persistence
            if (!headers_sent()) {
                setcookie('humanitarian_lang', $path_lang, time() + (365 * 24 * 60 * 60), '/');
            }
            return $path_lang;
        }
    }

    // Check URL parameter (e.g., ?lang=fr)
    if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $languages)) {
        $lang = sanitize_text_field($_GET['lang']);
        // Set cookie for persistence
        if (!headers_sent()) {
            setcookie('humanitarian_lang', $lang, time() + (365 * 24 * 60 * 60), '/');
        }
        return $lang;
    }

    // Check cookie
    if (isset($_COOKIE['humanitarian_lang']) && array_key_exists($_COOKIE['humanitarian_lang'], $languages)) {
        return sanitize_text_field($_COOKIE['humanitarian_lang']);
    }

    // Check browser language
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $browser_lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        if (array_key_exists($browser_lang, $languages)) {
            return $browser_lang;
        }
    }

    // Default to English
    return 'en';
}

/**
 * Switch WordPress locale based on current language
 * This enables theme translation files (.mo) to be loaded
 */
function humanitarian_switch_locale($locale) {
    // Don't change admin locale
    if (is_admin()) {
        return $locale;
    }

    $current_lang = humanitarian_get_current_language();
    $languages = humanitarian_get_languages();

    if (isset($languages[$current_lang])) {
        return $languages[$current_lang]['locale'];
    }

    return $locale;
}
add_filter('locale', 'humanitarian_switch_locale');

/**
 * Add rewrite rules for language prefixes
 * Handles URLs like /en/home/, /fr/article/, /ar/page/
 */
function humanitarian_add_language_rewrite_rules() {
    $languages = array_keys(humanitarian_get_languages());
    $lang_pattern = '(' . implode('|', $languages) . ')';

    // Language prefix for any URL
    add_rewrite_rule(
        '^' . $lang_pattern . '/(.*)$',
        'index.php?lang=$matches[1]&pagename=$matches[2]',
        'top'
    );

    // Language prefix only (homepage)
    add_rewrite_rule(
        '^' . $lang_pattern . '/?$',
        'index.php?lang=$matches[1]',
        'top'
    );
}
add_action('init', 'humanitarian_add_language_rewrite_rules');

/**
 * Register lang as a query variable
 */
function humanitarian_add_query_vars($vars) {
    $vars[] = 'lang';
    return $vars;
}
add_filter('query_vars', 'humanitarian_add_query_vars');

/**
 * Handle language prefix URLs early in WordPress initialization
 * Sets the language before any queries run
 */
function humanitarian_handle_language_prefix_early() {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $languages = humanitarian_get_languages();

    // Check if URL starts with a language code
    if (preg_match('#^/([a-z]{2})(/.*)?$#', $request_uri, $matches)) {
        $lang_code = $matches[1];

        if (array_key_exists($lang_code, $languages)) {
            // Set the language in superglobals
            $_GET['lang'] = $lang_code;
            $_REQUEST['lang'] = $lang_code;

            // Set cookie
            if (!headers_sent()) {
                setcookie('humanitarian_lang', $lang_code, time() + (365 * 24 * 60 * 60), '/');
            }
        }
    }
}
add_action('plugins_loaded', 'humanitarian_handle_language_prefix_early', 1);

/**
 * Prevent WordPress canonical redirect for language-prefixed URLs
 */
function humanitarian_prevent_canonical_redirect($redirect_url, $requested_url) {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $languages = humanitarian_get_languages();

    // Check if original URL has language prefix
    if (preg_match('#^/([a-z]{2})(/.*)?$#', $request_uri, $matches)) {
        $lang_code = $matches[1];
        if (array_key_exists($lang_code, $languages)) {
            // Don't redirect - let the page load
            return false;
        }
    }

    return $redirect_url;
}
add_filter('redirect_canonical', 'humanitarian_prevent_canonical_redirect', 10, 2);

/**
 * Load theme text domain for translations
 */
function humanitarian_load_theme_textdomain() {
    $current_lang = humanitarian_get_current_language();
    $languages = humanitarian_get_languages();

    // Get locale for current language
    $locale = isset($languages[$current_lang]) ? $languages[$current_lang]['locale'] : 'en_US';

    // Load theme translations
    $theme_dir = get_template_directory();

    // Unload first if already loaded
    unload_textdomain('humanitarian');

    // Try to load the translation file
    $mofile = $theme_dir . '/languages/' . $locale . '.mo';
    if (file_exists($mofile)) {
        load_textdomain('humanitarian', $mofile);
    } else {
        // Try without country code (e.g., 'ar' instead of 'ar_AR')
        $short_locale = substr($locale, 0, 2);
        $mofile_short = $theme_dir . '/languages/' . $short_locale . '.mo';
        if (file_exists($mofile_short)) {
            load_textdomain('humanitarian', $mofile_short);
        }
    }
}
add_action('after_setup_theme', 'humanitarian_load_theme_textdomain', 20);

/**
 * Filter posts by language on frontend
 */
function humanitarian_filter_posts_by_language($query) {
    // Only on frontend, main query, and for posts
    if (is_admin() || !$query->is_main_query()) {
        return;
    }

    // Only filter on home, archive, and search pages
    if (!is_home() && !is_archive() && !is_search()) {
        return;
    }

    // Don't filter if already filtering by language
    if ($query->get('language')) {
        return;
    }

    $current_lang = humanitarian_get_current_language();

    // Get the language term
    $lang_term = get_term_by('slug', $current_lang, 'language');

    if ($lang_term) {
        $tax_query = $query->get('tax_query') ?: array();
        $tax_query[] = array(
            'taxonomy' => 'language',
            'field'    => 'slug',
            'terms'    => $current_lang,
        );
        $query->set('tax_query', $tax_query);
    }
}
add_action('pre_get_posts', 'humanitarian_filter_posts_by_language');

/**
 * Redirect single posts to correct language version
 * If user is viewing a post in the wrong language, redirect to translation
 */
function humanitarian_redirect_to_correct_language() {
    // Only on single posts/pages
    if (!is_singular(['post', 'page']) || is_admin()) {
        return;
    }

    $post_id = get_the_ID();
    $post_lang = humanitarian_get_post_language($post_id);
    $user_lang = humanitarian_get_current_language();

    // If post is already in user's language, do nothing
    if ($post_lang === $user_lang) {
        return;
    }

    // Try to find translation in user's language
    $translation_id = humanitarian_get_translation($post_id, $user_lang);

    // If translation exists and is different from current post, redirect
    if ($translation_id && $translation_id !== $post_id) {
        wp_redirect(get_permalink($translation_id), 301);
        exit;
    }
}
add_action('template_redirect', 'humanitarian_redirect_to_correct_language');

/**
 * Add RTL support based on current language - FRONTEND ONLY
 * Dashboard RTL is controlled by user profile settings, not site language
 */
function humanitarian_language_rtl_support() {
    // Don't apply RTL in admin - admin language is set per user in profile
    if (is_admin()) {
        return;
    }

    $current_lang = humanitarian_get_current_language();
    $languages = humanitarian_get_languages();

    if (isset($languages[$current_lang]) && $languages[$current_lang]['rtl']) {
        add_filter('language_attributes', function($output) {
            return $output . ' dir="rtl"';
        });
    }
}
add_action('wp', 'humanitarian_language_rtl_support');

/**
 * Language switcher output
 */
function humanitarian_language_switcher() {
    $languages = humanitarian_get_languages();
    $current_lang = humanitarian_get_current_language();

    $output = '';

    foreach ($languages as $slug => $lang) {
        $url = add_query_arg('lang', $slug, remove_query_arg('lang'));
        $active_class = ($slug === $current_lang) ? ' active' : '';

        $output .= sprintf(
            '<a href="%s" class="language-dropdown__item%s" role="menuitem" data-lang="%s">%s</a>',
            esc_url($url),
            esc_attr($active_class),
            esc_attr($slug),
            esc_html($lang['native'])
        );
    }

    return $output;
}

/**
 * Get post language
 */
function humanitarian_get_post_language($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $terms = get_the_terms($post_id, 'language');

    if ($terms && !is_wp_error($terms)) {
        return $terms[0]->slug;
    }

    return 'en'; // Default to English
}

/**
 * Set default language for new posts and pages
 */
function humanitarian_set_default_language($post_id, $post, $update) {
    // Only for new content
    if ($update) {
        return;
    }

    // Only for posts and pages
    if (!in_array($post->post_type, ['post', 'page'])) {
        return;
    }

    // Check if already has language
    $terms = get_the_terms($post_id, 'language');
    if ($terms && !is_wp_error($terms)) {
        return;
    }

    // Set default to English
    wp_set_object_terms($post_id, 'en', 'language');
}
add_action('wp_insert_post', 'humanitarian_set_default_language', 10, 3);

/**
 * Add language column to admin posts list
 */
function humanitarian_add_language_column($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Add language column after title
        if ($key === 'title') {
            $new_columns['post_language'] = __('Language', 'humanitarian');
        }
    }

    return $new_columns;
}
add_filter('manage_posts_columns', 'humanitarian_add_language_column');
add_filter('manage_pages_columns', 'humanitarian_add_language_column');

/**
 * Display language in admin column
 */
function humanitarian_display_language_column($column, $post_id) {
    if ($column !== 'post_language') {
        return;
    }

    $terms = get_the_terms($post_id, 'language');

    if ($terms && !is_wp_error($terms)) {
        $languages = humanitarian_get_languages();
        $lang_slug = $terms[0]->slug;

        if (isset($languages[$lang_slug])) {
            echo '<span class="language-badge language-' . esc_attr($lang_slug) . '">';
            echo esc_html($languages[$lang_slug]['native']);
            echo '</span>';
        } else {
            echo esc_html($terms[0]->name);
        }
    } else {
        echo '<span class="language-badge language-none">' . __('Not set', 'humanitarian') . '</span>';
    }
}
add_action('manage_posts_custom_column', 'humanitarian_display_language_column', 10, 2);
add_action('manage_pages_custom_column', 'humanitarian_display_language_column', 10, 2);

/**
 * Add language filter dropdown in admin
 */
function humanitarian_admin_language_filter() {
    global $typenow;

    if (!in_array($typenow, ['post', 'page'])) {
        return;
    }

    $current = isset($_GET['language']) ? sanitize_text_field($_GET['language']) : '';
    $languages = humanitarian_get_languages();

    echo '<select name="language" id="language-filter">';
    echo '<option value="">' . __('All Languages', 'humanitarian') . '</option>';

    foreach ($languages as $slug => $lang) {
        $selected = ($current === $slug) ? ' selected="selected"' : '';
        echo '<option value="' . esc_attr($slug) . '"' . $selected . '>' . esc_html($lang['native']) . '</option>';
    }

    echo '</select>';
}
add_action('restrict_manage_posts', 'humanitarian_admin_language_filter');
add_action('restrict_manage_pages', 'humanitarian_admin_language_filter');

/**
 * Filter admin posts by language
 */
function humanitarian_admin_filter_by_language($query) {
    global $pagenow, $typenow;

    // Only run on admin edit.php for posts/pages and main query
    if (!is_admin() || $pagenow !== 'edit.php' || !in_array($typenow, ['post', 'page'])) {
        return;
    }

    // Only modify the main query
    if (!$query->is_main_query()) {
        return;
    }

    // Check if language filter is set
    if (!isset($_GET['language']) || empty($_GET['language'])) {
        return;
    }

    $language = sanitize_text_field($_GET['language']);

    // Set the tax_query
    $query->set('tax_query', array(
        array(
            'taxonomy' => 'language',
            'field'    => 'slug',
            'terms'    => $language,
        ),
    ));
}
add_action('pre_get_posts', 'humanitarian_admin_filter_by_language');

/**
 * Admin CSS for language badges and buttons
 */
function humanitarian_language_admin_styles() {
    echo '<style>
        .language-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .language-en { background: #dbeafe; color: #1e40af; }
        .language-ar { background: #d1fae5; color: #065f46; }
        .language-fr { background: #fef3c7; color: #92400e; }
        .language-none { background: #f3f4f6; color: #6b7280; }

        #language-filter {
            margin-right: 6px;
        }

        /* Language buttons in Articles header */
        .humanitarian-lang-buttons {
            display: inline-flex;
            gap: 8px;
            margin-left: 15px;
            vertical-align: middle;
        }
        .humanitarian-lang-btn {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            border: 2px solid transparent;
        }
        .humanitarian-lang-btn.lang-en {
            background: #dbeafe;
            color: #1e40af;
        }
        .humanitarian-lang-btn.lang-ar {
            background: #d1fae5;
            color: #065f46;
        }
        .humanitarian-lang-btn.lang-fr {
            background: #fef3c7;
            color: #92400e;
        }
        .humanitarian-lang-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .humanitarian-lang-btn.active {
            border-color: #0D5C63;
        }
    </style>';
}
add_action('admin_head', 'humanitarian_language_admin_styles');

/**
 * Add language filter buttons to Articles admin header
 */
function humanitarian_add_language_buttons_to_admin() {
    global $typenow, $pagenow;

    // Only on posts/pages list page
    if ($pagenow !== 'edit.php' || !in_array($typenow, ['post', 'page'])) {
        return;
    }

    $current_filter = isset($_GET['language']) ? sanitize_text_field($_GET['language']) : '';
    $languages = humanitarian_get_languages();

    $base_url = admin_url('edit.php?post_type=' . $typenow);

    ?>
    <script>
    jQuery(document).ready(function($) {
        var langButtons = '<span class="humanitarian-lang-buttons">';
        <?php foreach ($languages as $slug => $lang) :
            $url = add_query_arg('language', $slug, $base_url);
            $active = ($current_filter === $slug) ? ' active' : '';
        ?>
        langButtons += '<a href="<?php echo esc_url($url); ?>" class="humanitarian-lang-btn lang-<?php echo esc_attr($slug); ?><?php echo $active; ?>"><?php echo esc_html($lang['native']); ?></a>';
        <?php endforeach; ?>
        langButtons += '</span>';

        // Insert after the page title
        $('h1.wp-heading-inline').after(langButtons);
    });
    </script>
    <?php
}
add_action('admin_footer', 'humanitarian_add_language_buttons_to_admin');

/**
 * Get translated version of a post
 *
 * @param int $post_id The post ID to get translation for
 * @param string $target_lang Target language code (en, fr, ar)
 * @return int|false The translated post ID or false if not found
 */
function humanitarian_get_translation($post_id, $target_lang) {
    $post = get_post($post_id);
    if (!$post) {
        return false;
    }

    // Check if this post is already in the target language
    $current_lang = humanitarian_get_post_language($post_id);
    if ($current_lang === $target_lang) {
        return $post_id;
    }

    // Get the original post ID (if this is a translation)
    $original_id = get_post_meta($post_id, '_original_post_id', true);

    // If this post has an original, use that
    if ($original_id) {
        // Check if original is in target language
        $original_lang = humanitarian_get_post_language($original_id);
        if ($original_lang === $target_lang) {
            return intval($original_id);
        }

        // Otherwise, look for translation from original
        $base_id = $original_id;
    } else {
        // This is an original post
        $base_id = $post_id;
    }

    // Find translation
    $translations = get_posts([
        'post_type' => $post->post_type,
        'post_status' => 'publish',
        'meta_key' => '_original_post_id',
        'meta_value' => $base_id,
        'tax_query' => [
            [
                'taxonomy' => 'language',
                'field' => 'slug',
                'terms' => $target_lang,
            ]
        ],
        'posts_per_page' => 1,
    ]);

    if (!empty($translations)) {
        return $translations[0]->ID;
    }

    return false;
}

/**
 * Get all translations of a post
 *
 * @param int $post_id The post ID
 * @return array Array of translations with lang code as key and post ID as value
 */
function humanitarian_get_all_translations($post_id) {
    $translations = [];
    $languages = humanitarian_get_languages();

    foreach ($languages as $lang_code => $lang) {
        $translation_id = humanitarian_get_translation($post_id, $lang_code);
        if ($translation_id) {
            $translations[$lang_code] = $translation_id;
        }
    }

    return $translations;
}

/**
 * Enhanced language switcher for single posts/pages
 * Links to actual translated posts when available
 */
function humanitarian_language_switcher_with_translations() {
    $languages = humanitarian_get_languages();
    $current_lang = humanitarian_get_current_language();
    $output = '';

    // Check if we're on a single post or page
    $is_single = is_singular(['post', 'page']);
    $current_post_id = $is_single ? get_the_ID() : 0;

    foreach ($languages as $slug => $lang) {
        $active_class = ($slug === $current_lang) ? ' active' : '';

        if ($is_single && $current_post_id) {
            // Try to get translated version
            $translation_id = humanitarian_get_translation($current_post_id, $slug);
            if ($translation_id) {
                $url = get_permalink($translation_id);
            } else {
                // No translation available, show disabled link
                $url = '#';
                $active_class .= ' disabled';
            }
        } else {
            // Archive/home pages - just add lang parameter
            $url = add_query_arg('lang', $slug, remove_query_arg('lang'));
        }

        $output .= sprintf(
            '<a href="%s" class="language-dropdown__item%s" role="menuitem" data-lang="%s">%s</a>',
            esc_url($url),
            esc_attr($active_class),
            esc_attr($slug),
            esc_html($lang['native'])
        );
    }

    return $output;
}
