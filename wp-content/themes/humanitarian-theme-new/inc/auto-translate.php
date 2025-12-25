<?php
/**
 * Automatic Translation System
 *
 * Polylang + DeepL API Integration for automatic post translation
 * Supports: English (EN), Arabic (AR), French (FR)
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Supported languages for translation
 */
define('HUMANITARIAN_SUPPORTED_LANGUAGES', ['en', 'ar', 'fr']);
define('HUMANITARIAN_DEFAULT_LANGUAGE', 'en');

/**
 * DeepL API Configuration
 * Add your API key to wp-config.php:
 * define('DEEPL_API_KEY', 'your-api-key-here');
 */
function humanitarian_get_deepl_api_key() {
    return defined('DEEPL_API_KEY') ? DEEPL_API_KEY : '';
}

/**
 * Get DeepL API URL (Free or Pro)
 */
function humanitarian_get_deepl_api_url() {
    $api_key = humanitarian_get_deepl_api_key();

    // Free API keys end with ':fx'
    if (strpos($api_key, ':fx') !== false) {
        return 'https://api-free.deepl.com/v2/translate';
    }

    return 'https://api.deepl.com/v2/translate';
}

/**
 * Translate text using DeepL API
 *
 * @param string $text Text to translate
 * @param string $target_lang Target language code (EN, AR, FR)
 * @param string $source_lang Source language code (optional)
 * @return string Translated text or original if translation fails
 */
function humanitarian_translate_text($text, $target_lang, $source_lang = null) {
    $api_key = humanitarian_get_deepl_api_key();

    if (empty($text) || empty($api_key)) {
        return $text;
    }

    // DeepL language codes mapping
    $deepl_codes = [
        'en' => 'EN',
        'ar' => 'AR',
        'fr' => 'FR'
    ];

    $target = $deepl_codes[$target_lang] ?? 'EN';
    $source = $source_lang ? ($deepl_codes[$source_lang] ?? null) : null;

    // Build request parameters
    $params = [
        'auth_key' => $api_key,
        'text' => $text,
        'target_lang' => $target,
        'preserve_formatting' => '1'
    ];

    if ($source) {
        $params['source_lang'] = $source;
    }

    // Make API request
    $response = wp_remote_post(humanitarian_get_deepl_api_url(), [
        'body' => $params,
        'timeout' => 60,
        'headers' => [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ]
    ]);

    if (is_wp_error($response)) {
        error_log('DeepL API Error: ' . $response->get_error_message());
        return $text;
    }

    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code !== 200) {
        error_log('DeepL API Error: HTTP ' . $response_code);
        return $text;
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (isset($body['translations'][0]['text'])) {
        return $body['translations'][0]['text'];
    }

    return $text;
}

/**
 * Check if Polylang is active
 */
function humanitarian_is_polylang_active() {
    return function_exists('pll_get_post_language');
}

/**
 * Auto-translate post when published
 *
 * @param int $post_id Post ID
 * @param WP_Post $post Post object
 * @param bool $update Is this an update?
 */
function humanitarian_auto_translate_post($post_id, $post, $update) {
    // Skip if updating
    if ($update) {
        return;
    }

    // Skip if post type is not 'post'
    if ($post->post_type !== 'post') {
        return;
    }

    // Skip if Polylang is not active
    if (!humanitarian_is_polylang_active()) {
        return;
    }

    // Skip if no API key
    if (empty(humanitarian_get_deepl_api_key())) {
        return;
    }

    // Get current language
    $current_lang = pll_get_post_language($post_id);
    if (!$current_lang) {
        $current_lang = pll_default_language();
    }

    // Schedule async translation
    wp_schedule_single_event(time() + 5, 'humanitarian_create_translations', [$post_id, $current_lang]);
}
// DISABLED: Causes infinite loop - auto-creates posts endlessly
// add_action('wp_insert_post', 'humanitarian_auto_translate_post', 10, 3);

/**
 * Create translations for other languages (runs async)
 *
 * @param int $post_id Original post ID
 * @param string $source_lang Source language code
 */
function humanitarian_do_create_translations($post_id, $source_lang) {
    $post = get_post($post_id);

    if (!$post || !humanitarian_is_polylang_active()) {
        return;
    }

    foreach (HUMANITARIAN_SUPPORTED_LANGUAGES as $target_lang) {
        // Skip source language
        if ($target_lang === $source_lang) {
            continue;
        }

        // Check if translation already exists
        $translations = pll_get_post_translations($post_id);
        if (isset($translations[$target_lang])) {
            continue;
        }

        // Create translation
        humanitarian_create_single_translation($post_id, $post, $source_lang, $target_lang);
    }
}
// DISABLED: Part of the auto-translate system that causes infinite loop
// add_action('humanitarian_create_translations', 'humanitarian_do_create_translations', 10, 2);

/**
 * Create a single translation
 *
 * @param int $original_id Original post ID
 * @param WP_Post $original_post Original post object
 * @param string $source_lang Source language code
 * @param string $target_lang Target language code
 * @return int|false Translated post ID or false on failure
 */
function humanitarian_create_single_translation($original_id, $original_post, $source_lang, $target_lang) {
    // Translate title
    $translated_title = humanitarian_translate_text(
        $original_post->post_title,
        $target_lang,
        $source_lang
    );

    // Translate content
    $translated_content = humanitarian_translate_text(
        $original_post->post_content,
        $target_lang,
        $source_lang
    );

    // Translate excerpt if exists
    $translated_excerpt = '';
    if (!empty($original_post->post_excerpt)) {
        $translated_excerpt = humanitarian_translate_text(
            $original_post->post_excerpt,
            $target_lang,
            $source_lang
        );
    }

    // Create translated post (as draft for review)
    $translated_post_data = [
        'post_title'   => $translated_title,
        'post_content' => $translated_content,
        'post_excerpt' => $translated_excerpt,
        'post_status'  => 'draft', // Draft for review
        'post_type'    => 'post',
        'post_author'  => $original_post->post_author,
    ];

    $translated_id = wp_insert_post($translated_post_data);

    if (!$translated_id || is_wp_error($translated_id)) {
        error_log('Failed to create translation for post #' . $original_id . ' to ' . $target_lang);
        return false;
    }

    // Set language in Polylang
    pll_set_post_language($translated_id, $target_lang);

    // Link translations
    $translations = pll_get_post_translations($original_id);
    $translations[$target_lang] = $translated_id;
    $translations[$source_lang] = $original_id;
    pll_save_post_translations($translations);

    // Copy featured image
    $thumbnail_id = get_post_thumbnail_id($original_id);
    if ($thumbnail_id) {
        set_post_thumbnail($translated_id, $thumbnail_id);
    }

    // Copy/translate categories
    humanitarian_copy_translated_terms($original_id, $translated_id, 'category', $target_lang);

    // Copy/translate taxonomies
    humanitarian_copy_translated_terms($original_id, $translated_id, 'article_type', $target_lang);
    humanitarian_copy_translated_terms($original_id, $translated_id, 'region', $target_lang);
    humanitarian_copy_translated_terms($original_id, $translated_id, 'post_tag', $target_lang);

    // Copy custom meta (translate text fields)
    $meta_to_translate = ['_subtitle', '_photo_caption'];
    foreach ($meta_to_translate as $meta_key) {
        $value = get_post_meta($original_id, $meta_key, true);
        if (!empty($value)) {
            $translated_value = humanitarian_translate_text($value, $target_lang, $source_lang);
            update_post_meta($translated_id, $meta_key, $translated_value);
        }
    }

    // Copy non-text meta
    $meta_to_copy = ['_is_featured', '_is_editors_pick', '_article_audio_id'];
    foreach ($meta_to_copy as $meta_key) {
        $value = get_post_meta($original_id, $meta_key, true);
        if (!empty($value)) {
            update_post_meta($translated_id, $meta_key, $value);
        }
    }

    // Mark as auto-translated
    update_post_meta($translated_id, '_auto_translated', 1);
    update_post_meta($translated_id, '_translation_source', $original_id);
    update_post_meta($translated_id, '_translation_date', current_time('mysql'));
    update_post_meta($translated_id, '_translation_source_lang', $source_lang);

    // Notify admin about new translation
    humanitarian_notify_new_translation($translated_id, $target_lang, $original_id);

    return $translated_id;
}

/**
 * Copy terms from original post to translated post
 *
 * @param int $original_id Original post ID
 * @param int $translated_id Translated post ID
 * @param string $taxonomy Taxonomy name
 * @param string $target_lang Target language
 */
function humanitarian_copy_translated_terms($original_id, $translated_id, $taxonomy, $target_lang) {
    if (!humanitarian_is_polylang_active()) {
        return;
    }

    $terms = wp_get_post_terms($original_id, $taxonomy, ['fields' => 'ids']);

    if (empty($terms) || is_wp_error($terms)) {
        return;
    }

    $translated_terms = [];

    foreach ($terms as $term_id) {
        // Try to get translated term
        $translated_term_id = pll_get_term($term_id, $target_lang);

        if ($translated_term_id) {
            $translated_terms[] = $translated_term_id;
        } else {
            // If no translation exists, use original term
            $translated_terms[] = $term_id;
        }
    }

    if (!empty($translated_terms)) {
        wp_set_post_terms($translated_id, $translated_terms, $taxonomy);
    }
}

/**
 * Notify admin about new translation ready for review
 *
 * @param int $post_id Translated post ID
 * @param string $lang Language code
 * @param int $original_id Original post ID
 */
function humanitarian_notify_new_translation($post_id, $lang, $original_id) {
    $admin_email = get_option('admin_email');
    $post = get_post($post_id);
    $original_post = get_post($original_id);
    $edit_link = admin_url('post.php?post=' . $post_id . '&action=edit');

    $lang_names = [
        'en' => 'English',
        'ar' => 'Arabic',
        'fr' => 'French'
    ];

    $lang_name = $lang_names[$lang] ?? $lang;

    $subject = sprintf(
        /* translators: 1: Site name 2: Language name */
        __('[%1$s] New %2$s translation ready for review', 'humanitarianblog'),
        get_bloginfo('name'),
        $lang_name
    );

    $message = sprintf(
        /* translators: 1: Language name 2: Original title 3: Translated title 4: Edit link */
        __("A new %1\$s translation has been automatically created.\n\nOriginal: %2\$s\nTranslation: %3\$s\n\nReview and publish: %4\$s", 'humanitarianblog'),
        $lang_name,
        $original_post->post_title,
        $post->post_title,
        $edit_link
    );

    wp_mail($admin_email, $subject, $message);
}

/**
 * Add language switcher to header
 */
function humanitarian_language_switcher() {
    if (!humanitarian_is_polylang_active()) {
        return;
    }

    $languages = pll_the_languages([
        'raw' => 1,
        'hide_if_empty' => 0
    ]);

    if (empty($languages)) {
        return;
    }

    $current_lang = pll_current_language('slug');

    $flag_emojis = [
        'en' => '????????',
        'ar' => '????????',
        'fr' => '????????'
    ];
    ?>
    <div class="language-switcher">
        <button class="lang-toggle" aria-expanded="false" aria-haspopup="true" aria-label="<?php esc_attr_e('Change language', 'humanitarianblog'); ?>">
            <span class="lang-current">
                <span class="lang-flag"><?php echo $flag_emojis[$current_lang] ?? '????'; ?></span>
                <span class="lang-code"><?php echo strtoupper(esc_html($current_lang)); ?></span>
            </span>
            <svg class="lang-arrow" width="12" height="12" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
            </svg>
        </button>

        <div class="lang-dropdown" role="menu">
            <?php foreach ($languages as $lang) : ?>
                <a href="<?php echo esc_url($lang['url']); ?>"
                   class="lang-option <?php echo $lang['current_lang'] ? 'active' : ''; ?>"
                   role="menuitem"
                   lang="<?php echo esc_attr($lang['locale']); ?>"
                   hreflang="<?php echo esc_attr($lang['slug']); ?>">
                    <span class="lang-flag"><?php echo $flag_emojis[$lang['slug']] ?? '????'; ?></span>
                    <span class="lang-name"><?php echo esc_html($lang['name']); ?></span>
                    <?php if ($lang['current_lang']) : ?>
                        <svg class="lang-check" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                        </svg>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}

/**
 * Add translation status column to posts list in admin
 */
function humanitarian_add_translation_column($columns) {
    if (!humanitarian_is_polylang_active()) {
        return $columns;
    }

    $new_columns = [];

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        if ($key === 'title') {
            $new_columns['translations_status'] = __('Translations', 'humanitarianblog');
        }
    }

    return $new_columns;
}
// DISABLED: Polylang already provides translation columns
// add_filter('manage_posts_columns', 'humanitarian_add_translation_column', 20);

/**
 * Display translation status in admin posts list
 */
function humanitarian_translation_column_content($column, $post_id) {
    if ($column !== 'translations_status') {
        return;
    }

    if (!humanitarian_is_polylang_active()) {
        echo '???';
        return;
    }

    $translations = pll_get_post_translations($post_id);
    $current_lang = pll_get_post_language($post_id);

    $output = [];

    foreach (HUMANITARIAN_SUPPORTED_LANGUAGES as $lang) {
        if ($lang === $current_lang) {
            continue;
        }

        if (isset($translations[$lang])) {
            $trans_post = get_post($translations[$lang]);
            $status = $trans_post ? $trans_post->post_status : 'missing';

            if ($status === 'publish') {
                $output[] = '<span style="color:#059669" title="' . esc_attr__('Published', 'humanitarianblog') . '">??? ' . strtoupper($lang) . '</span>';
            } else {
                $edit_link = admin_url('post.php?post=' . $translations[$lang] . '&action=edit');
                $output[] = '<a href="' . esc_url($edit_link) . '" style="color:#d97706" title="' . esc_attr__('Draft - click to edit', 'humanitarianblog') . '">??? ' . strtoupper($lang) . '</a>';
            }
        } else {
            $output[] = '<span style="color:#dc2626" title="' . esc_attr__('No translation', 'humanitarianblog') . '">??? ' . strtoupper($lang) . '</span>';
        }
    }

    echo implode(' ', $output);
}
// DISABLED: Polylang already provides translation columns
// add_action('manage_posts_custom_column', 'humanitarian_translation_column_content', 10, 2);

/**
 * Admin page for translation settings
 */
function humanitarian_translation_settings_page() {
    add_options_page(
        __('Translation Settings', 'humanitarianblog'),
        __('Translations', 'humanitarianblog'),
        'manage_options',
        'humanitarian-translations',
        'humanitarian_translation_settings_html'
    );
}
add_action('admin_menu', 'humanitarian_translation_settings_page');

/**
 * Render translation settings page
 */
function humanitarian_translation_settings_html() {
    $api_key = humanitarian_get_deepl_api_key();
    $has_key = !empty($api_key);
    $polylang_active = humanitarian_is_polylang_active();
    ?>
    <div class="wrap">
        <h1><?php _e('Translation Settings', 'humanitarianblog'); ?></h1>

        <div class="card" style="max-width: 800px; padding: 20px;">
            <h2><?php _e('System Status', 'humanitarianblog'); ?></h2>

            <table class="form-table">
                <tr>
                    <th><?php _e('Polylang Plugin', 'humanitarianblog'); ?></th>
                    <td>
                        <?php if ($polylang_active) : ?>
                            <span style="color: #059669;">
                                <span class="dashicons dashicons-yes-alt"></span>
                                <?php _e('Active', 'humanitarianblog'); ?>
                            </span>
                        <?php else : ?>
                            <span style="color: #dc2626;">
                                <span class="dashicons dashicons-warning"></span>
                                <?php _e('Not installed or inactive', 'humanitarianblog'); ?>
                            </span>
                            <p class="description">
                                <?php _e('Please install and activate the Polylang plugin for multi-language support.', 'humanitarianblog'); ?>
                            </p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('DeepL API Key', 'humanitarianblog'); ?></th>
                    <td>
                        <?php if ($has_key) : ?>
                            <span style="color: #059669;">
                                <span class="dashicons dashicons-yes-alt"></span>
                                <?php _e('Configured', 'humanitarianblog'); ?>
                            </span>
                            <p class="description">
                                <?php
                                if (strpos($api_key, ':fx') !== false) {
                                    _e('Using DeepL Free API', 'humanitarianblog');
                                } else {
                                    _e('Using DeepL Pro API', 'humanitarianblog');
                                }
                                ?>
                            </p>
                        <?php else : ?>
                            <span style="color: #dc2626;">
                                <span class="dashicons dashicons-warning"></span>
                                <?php _e('Not configured', 'humanitarianblog'); ?>
                            </span>
                            <p class="description">
                                <?php _e('Add this line to your wp-config.php:', 'humanitarianblog'); ?>
                                <br>
                                <code>define('DEEPL_API_KEY', 'your-api-key-here');</code>
                            </p>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <th><?php _e('Supported Languages', 'humanitarianblog'); ?></th>
                    <td>
                        <ul style="margin: 0;">
                            <li>???????? <?php _e('English (EN)', 'humanitarianblog'); ?></li>
                            <li>???????? <?php _e('Arabic (AR)', 'humanitarianblog'); ?></li>
                            <li>???????? <?php _e('French (FR)', 'humanitarianblog'); ?></li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>

        <div class="card" style="max-width: 800px; padding: 20px; margin-top: 20px;">
            <h2><?php _e('How It Works', 'humanitarianblog'); ?></h2>

            <ol>
                <li><?php _e('When a new article is published, the system automatically creates translations.', 'humanitarianblog'); ?></li>
                <li><?php _e('Translations are saved as drafts for editor review.', 'humanitarianblog'); ?></li>
                <li><?php _e('Editors receive email notifications about new translations.', 'humanitarianblog'); ?></li>
                <li><?php _e('After review, editors can publish the translations.', 'humanitarianblog'); ?></li>
            </ol>

            <p class="description">
                <strong><?php _e('Note:', 'humanitarianblog'); ?></strong>
                <?php _e('Machine translations may need human review for accuracy, especially for technical or cultural content.', 'humanitarianblog'); ?>
            </p>
        </div>

        <?php if ($has_key) : ?>
        <div class="card" style="max-width: 800px; padding: 20px; margin-top: 20px;">
            <h2><?php _e('Test Translation', 'humanitarianblog'); ?></h2>

            <form method="post" action="">
                <?php wp_nonce_field('test_translation', 'test_translation_nonce'); ?>
                <p>
                    <label for="test_text"><strong><?php _e('Enter text to translate:', 'humanitarianblog'); ?></strong></label>
                    <textarea name="test_text" id="test_text" rows="3" class="large-text" placeholder="<?php esc_attr_e('Enter some text...', 'humanitarianblog'); ?>"></textarea>
                </p>
                <p>
                    <label for="test_lang"><strong><?php _e('Target language:', 'humanitarianblog'); ?></strong></label>
                    <select name="test_lang" id="test_lang">
                        <option value="ar"><?php _e('Arabic', 'humanitarianblog'); ?></option>
                        <option value="fr"><?php _e('French', 'humanitarianblog'); ?></option>
                        <option value="en"><?php _e('English', 'humanitarianblog'); ?></option>
                    </select>
                </p>
                <p>
                    <button type="submit" name="test_translate" class="button button-primary">
                        <?php _e('Test Translation', 'humanitarianblog'); ?>
                    </button>
                </p>
            </form>

            <?php
            if (isset($_POST['test_translate']) && wp_verify_nonce($_POST['test_translation_nonce'], 'test_translation')) {
                $test_text = sanitize_textarea_field($_POST['test_text']);
                $test_lang = sanitize_key($_POST['test_lang']);

                if (!empty($test_text)) {
                    $result = humanitarian_translate_text($test_text, $test_lang);
                    echo '<div style="background: #f0fdf4; padding: 15px; border-radius: 4px; margin-top: 15px;">';
                    echo '<strong>' . __('Result:', 'humanitarianblog') . '</strong><br>';
                    echo '<p style="margin: 10px 0 0;">' . esc_html($result) . '</p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
        <?php endif; ?>
    </div>
    <?php
}
