<?php
/**
 * DeepL Auto Translation for Polylang
 *
 * Adds automatic translation functionality using DeepL API
 * integrated with Polylang multi-language plugin.
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * DeepL Translation Class
 */
class Humanitarian_DeepL_Translation {

    /**
     * DeepL API endpoint
     */
    private $api_endpoint = 'https://api-free.deepl.com/v2/translate';

    /**
     * Supported language mappings (Polylang code => DeepL code)
     */
    private $lang_map = array(
        'en' => 'EN',
        'tr' => 'TR',
        'ar' => 'AR',
        'de' => 'DE',
        'fr' => 'FR',
        'es' => 'ES',
        'it' => 'IT',
        'pt' => 'PT',
        'ru' => 'RU',
        'zh' => 'ZH',
        'ja' => 'JA',
        'ko' => 'KO',
        'nl' => 'NL',
        'pl' => 'PL',
        'uk' => 'UK',
    );

    /**
     * Constructor
     */
    public function __construct() {
        // Add meta box to post editor
        add_action('add_meta_boxes', array($this, 'add_translation_metabox'));

        // AJAX handlers
        add_action('wp_ajax_deepl_translate_post', array($this, 'ajax_translate_post'));
        add_action('wp_ajax_deepl_translate_text', array($this, 'ajax_translate_text'));

        // Admin scripts
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Add bulk action for posts and pages
        add_filter('bulk_actions-edit-post', array($this, 'add_bulk_translate_action'));
        add_filter('bulk_actions-edit-page', array($this, 'add_bulk_translate_action'));
        add_filter('handle_bulk_actions-edit-post', array($this, 'handle_bulk_translate'), 10, 3);
        add_filter('handle_bulk_actions-edit-page', array($this, 'handle_bulk_translate'), 10, 3);

        // Admin notice for bulk action
        add_action('admin_notices', array($this, 'bulk_translate_notice'));

        // Add translate button to Polylang columns
        add_action('admin_footer-edit.php', array($this, 'add_translate_buttons_script'));
    }

    /**
     * Add translation metabox
     */
    public function add_translation_metabox() {
        if (!function_exists('pll_languages_list')) {
            return;
        }

        $post_types = array('post', 'page');

        foreach ($post_types as $post_type) {
            add_meta_box(
                'deepl_translation',
                __('🌐 DeepL Auto Translation', 'humanitarianblog'),
                array($this, 'render_translation_metabox'),
                $post_type,
                'side',
                'high'
            );
        }
    }

    /**
     * Render translation metabox
     */
    public function render_translation_metabox($post) {
        if (!function_exists('pll_get_post_language') || !function_exists('pll_languages_list')) {
            echo '<p>' . __('Polylang plugin is required.', 'humanitarianblog') . '</p>';
            return;
        }

        $current_lang = pll_get_post_language($post->ID);
        $all_languages = pll_languages_list(array('fields' => 'slug'));
        $translations = pll_get_post_translations($post->ID);

        wp_nonce_field('deepl_translate_nonce', 'deepl_nonce');
        ?>
        <div class="deepl-translation-box">
            <p class="description">
                <?php printf(__('Current language: %s', 'humanitarianblog'), '<strong>' . strtoupper($current_lang) . '</strong>'); ?>
            </p>

            <div class="deepl-actions" style="margin-top: 12px;">
                <?php foreach ($all_languages as $lang) : ?>
                    <?php if ($lang !== $current_lang) : ?>
                        <?php
                        $has_translation = isset($translations[$lang]) && $translations[$lang] > 0;
                        $button_class = $has_translation ? 'button' : 'button button-primary';
                        $button_text = $has_translation
                            ? sprintf(__('Update %s', 'humanitarianblog'), strtoupper($lang))
                            : sprintf(__('Translate to %s', 'humanitarianblog'), strtoupper($lang));
                        ?>
                        <button type="button"
                                class="<?php echo $button_class; ?> deepl-translate-btn"
                                data-post-id="<?php echo $post->ID; ?>"
                                data-source-lang="<?php echo $current_lang; ?>"
                                data-target-lang="<?php echo $lang; ?>"
                                data-has-translation="<?php echo $has_translation ? '1' : '0'; ?>"
                                style="margin: 4px 4px 4px 0; width: 100%;">
                            <?php echo $button_text; ?>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="deepl-status" style="margin-top: 12px; display: none;">
                <span class="spinner" style="float: left; margin: 0 8px 0 0;"></span>
                <span class="status-text"><?php _e('Translating...', 'humanitarianblog'); ?></span>
            </div>

            <div class="deepl-result" style="margin-top: 12px; display: none;"></div>
        </div>

        <style>
            .deepl-translation-box .spinner {
                visibility: visible;
            }
            .deepl-translation-box .deepl-status {
                background: #f0f6fc;
                padding: 10px;
                border-radius: 4px;
                border-left: 4px solid #2271b1;
            }
            .deepl-translation-box .deepl-result.success {
                background: #d4edda;
                padding: 10px;
                border-radius: 4px;
                border-left: 4px solid #28a745;
            }
            .deepl-translation-box .deepl-result.error {
                background: #f8d7da;
                padding: 10px;
                border-radius: 4px;
                border-left: 4px solid #dc3545;
            }
        </style>
        <?php
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if (!in_array($hook, array('post.php', 'post-new.php', 'edit.php'))) {
            return;
        }

        wp_enqueue_script(
            'deepl-translation',
            get_template_directory_uri() . '/assets/js/deepl-translation.js',
            array('jquery'),
            '1.0.0',
            true
        );

        wp_localize_script('deepl-translation', 'deeplTranslation', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('deepl_translate_nonce'),
            'strings' => array(
                'translating' => __('Translating...', 'humanitarianblog'),
                'success' => __('Translation created successfully!', 'humanitarianblog'),
                'updated' => __('Translation updated successfully!', 'humanitarianblog'),
                'error' => __('Translation failed. Please try again.', 'humanitarianblog'),
                'confirm_update' => __('This will overwrite the existing translation. Continue?', 'humanitarianblog'),
                'view_translation' => __('View Translation', 'humanitarianblog'),
                'edit_translation' => __('Edit Translation', 'humanitarianblog'),
            )
        ));
    }

    /**
     * AJAX handler for translating a post
     */
    public function ajax_translate_post() {
        check_ajax_referer('deepl_translate_nonce', 'nonce');

        if (!current_user_can('edit_posts')) {
            wp_send_json_error(array('message' => __('Permission denied.', 'humanitarianblog')));
        }

        $post_id = intval($_POST['post_id']);
        $source_lang = sanitize_text_field($_POST['source_lang']);
        $target_lang = sanitize_text_field($_POST['target_lang']);

        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error(array('message' => __('Post not found.', 'humanitarianblog')));
        }

        // Translate title
        $translated_title = $this->translate_text($post->post_title, $source_lang, $target_lang);
        if (is_wp_error($translated_title)) {
            wp_send_json_error(array('message' => $translated_title->get_error_message()));
        }

        // Translate content
        $translated_content = $this->translate_text($post->post_content, $source_lang, $target_lang);
        if (is_wp_error($translated_content)) {
            wp_send_json_error(array('message' => $translated_content->get_error_message()));
        }

        // Translate excerpt if exists
        $translated_excerpt = '';
        if (!empty($post->post_excerpt)) {
            $translated_excerpt = $this->translate_text($post->post_excerpt, $source_lang, $target_lang);
            if (is_wp_error($translated_excerpt)) {
                $translated_excerpt = '';
            }
        }

        // Check if translation already exists
        $translations = pll_get_post_translations($post_id);
        $existing_translation_id = isset($translations[$target_lang]) ? $translations[$target_lang] : 0;

        if ($existing_translation_id) {
            // Update existing translation
            $updated_post = array(
                'ID' => $existing_translation_id,
                'post_title' => $translated_title,
                'post_content' => $translated_content,
                'post_excerpt' => $translated_excerpt,
            );

            $result = wp_update_post($updated_post, true);

            if (is_wp_error($result)) {
                wp_send_json_error(array('message' => $result->get_error_message()));
            }

            $new_post_id = $existing_translation_id;
            $is_update = true;
        } else {
            // Create new translation
            $new_post = array(
                'post_title' => $translated_title,
                'post_content' => $translated_content,
                'post_excerpt' => $translated_excerpt,
                'post_status' => 'draft',
                'post_type' => $post->post_type,
                'post_author' => $post->post_author,
            );

            $new_post_id = wp_insert_post($new_post, true);

            if (is_wp_error($new_post_id)) {
                wp_send_json_error(array('message' => $new_post_id->get_error_message()));
            }

            // Set language
            pll_set_post_language($new_post_id, $target_lang);

            // Link translations
            $translations[$target_lang] = $new_post_id;
            $translations[$source_lang] = $post_id;
            pll_save_post_translations($translations);

            // Copy featured image
            $thumbnail_id = get_post_thumbnail_id($post_id);
            if ($thumbnail_id) {
                set_post_thumbnail($new_post_id, $thumbnail_id);
            }

            // Copy categories and tags
            $taxonomies = get_object_taxonomies($post->post_type);
            foreach ($taxonomies as $taxonomy) {
                if ($taxonomy === 'language' || $taxonomy === 'post_translations') {
                    continue;
                }
                $terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'ids'));
                if (!empty($terms) && !is_wp_error($terms)) {
                    // Get translated terms if they exist
                    $translated_terms = array();
                    foreach ($terms as $term_id) {
                        $translated_term = pll_get_term($term_id, $target_lang);
                        if ($translated_term) {
                            $translated_terms[] = $translated_term;
                        } else {
                            $translated_terms[] = $term_id;
                        }
                    }
                    wp_set_object_terms($new_post_id, $translated_terms, $taxonomy);
                }
            }

            $is_update = false;
        }

        wp_send_json_success(array(
            'message' => $is_update
                ? __('Translation updated successfully!', 'humanitarianblog')
                : __('Translation created successfully!', 'humanitarianblog'),
            'post_id' => $new_post_id,
            'edit_url' => get_edit_post_link($new_post_id, 'raw'),
            'view_url' => get_permalink($new_post_id),
            'is_update' => $is_update,
        ));
    }

    /**
     * AJAX handler for translating text
     */
    public function ajax_translate_text() {
        check_ajax_referer('deepl_translate_nonce', 'nonce');

        $text = wp_kses_post($_POST['text']);
        $source_lang = sanitize_text_field($_POST['source_lang']);
        $target_lang = sanitize_text_field($_POST['target_lang']);

        $translated = $this->translate_text($text, $source_lang, $target_lang);

        if (is_wp_error($translated)) {
            wp_send_json_error(array('message' => $translated->get_error_message()));
        }

        wp_send_json_success(array('translated' => $translated));
    }

    /**
     * Translate text using DeepL API
     */
    public function translate_text($text, $source_lang, $target_lang) {
        if (empty($text)) {
            return '';
        }

        $api_key = defined('DEEPL_API_KEY') ? DEEPL_API_KEY : '';

        if (empty($api_key)) {
            return new WP_Error('no_api_key', __('DeepL API key is not configured.', 'humanitarianblog'));
        }

        // Map language codes
        $source = isset($this->lang_map[$source_lang]) ? $this->lang_map[$source_lang] : strtoupper($source_lang);
        $target = isset($this->lang_map[$target_lang]) ? $this->lang_map[$target_lang] : strtoupper($target_lang);

        // Prepare request
        $response = wp_remote_post($this->api_endpoint, array(
            'timeout' => 60,
            'headers' => array(
                'Authorization' => 'DeepL-Auth-Key ' . $api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'text' => array($text),
                'source_lang' => $source,
                'target_lang' => $target,
                'tag_handling' => 'html',
                'preserve_formatting' => true,
            )),
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($status_code !== 200) {
            $error_message = isset($body['message']) ? $body['message'] : __('DeepL API error', 'humanitarianblog');
            return new WP_Error('api_error', $error_message . ' (Code: ' . $status_code . ')');
        }

        if (!isset($body['translations'][0]['text'])) {
            return new WP_Error('invalid_response', __('Invalid response from DeepL API.', 'humanitarianblog'));
        }

        return $body['translations'][0]['text'];
    }

    /**
     * Add bulk translate action
     */
    public function add_bulk_translate_action($actions) {
        if (function_exists('pll_languages_list')) {
            $languages = pll_languages_list(array('fields' => 'slug'));
            foreach ($languages as $lang) {
                $actions['translate_to_' . $lang] = sprintf(__('Translate to %s (DeepL)', 'humanitarianblog'), strtoupper($lang));
            }
        }
        return $actions;
    }

    /**
     * Handle bulk translate action
     */
    public function handle_bulk_translate($redirect_to, $action, $post_ids) {
        if (strpos($action, 'translate_to_') !== 0) {
            return $redirect_to;
        }

        $target_lang = str_replace('translate_to_', '', $action);
        $translated = 0;
        $errors = 0;

        foreach ($post_ids as $post_id) {
            $source_lang = pll_get_post_language($post_id);

            if ($source_lang === $target_lang) {
                continue;
            }

            $post = get_post($post_id);

            // Translate
            $translated_title = $this->translate_text($post->post_title, $source_lang, $target_lang);
            $translated_content = $this->translate_text($post->post_content, $source_lang, $target_lang);

            if (is_wp_error($translated_title) || is_wp_error($translated_content)) {
                $errors++;
                continue;
            }

            // Check existing translation
            $translations = pll_get_post_translations($post_id);

            if (isset($translations[$target_lang]) && $translations[$target_lang] > 0) {
                // Update existing
                wp_update_post(array(
                    'ID' => $translations[$target_lang],
                    'post_title' => $translated_title,
                    'post_content' => $translated_content,
                ));
            } else {
                // Create new
                $new_post_id = wp_insert_post(array(
                    'post_title' => $translated_title,
                    'post_content' => $translated_content,
                    'post_status' => 'draft',
                    'post_type' => $post->post_type,
                    'post_author' => $post->post_author,
                ));

                if (!is_wp_error($new_post_id)) {
                    pll_set_post_language($new_post_id, $target_lang);
                    $translations[$target_lang] = $new_post_id;
                    $translations[$source_lang] = $post_id;
                    pll_save_post_translations($translations);

                    // Copy featured image
                    $thumbnail_id = get_post_thumbnail_id($post_id);
                    if ($thumbnail_id) {
                        set_post_thumbnail($new_post_id, $thumbnail_id);
                    }
                }
            }

            $translated++;
        }

        $redirect_to = add_query_arg(array(
            'bulk_translated' => $translated,
            'bulk_translate_errors' => $errors,
            'bulk_translate_lang' => $target_lang,
        ), $redirect_to);

        return $redirect_to;
    }

    /**
     * Show bulk translate notice
     */
    public function bulk_translate_notice() {
        if (!empty($_REQUEST['bulk_translated'])) {
            $translated = intval($_REQUEST['bulk_translated']);
            $errors = intval($_REQUEST['bulk_translate_errors']);
            $lang = sanitize_text_field($_REQUEST['bulk_translate_lang']);

            $message = sprintf(
                _n(
                    '%d post translated to %s.',
                    '%d posts translated to %s.',
                    $translated,
                    'humanitarianblog'
                ),
                $translated,
                strtoupper($lang)
            );

            if ($errors > 0) {
                $message .= ' ' . sprintf(
                    _n('%d error occurred.', '%d errors occurred.', $errors, 'humanitarianblog'),
                    $errors
                );
            }

            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
        }
    }

    /**
     * Add translate buttons to post list
     */
    public function add_translate_buttons_script() {
        global $post_type;

        if (!in_array($post_type, array('post', 'page')) || !function_exists('pll_languages_list')) {
            return;
        }
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Convert + icons to translate buttons with DeepL
            $('.pll_icon_add').each(function() {
                var $link = $(this);
                var href = $link.attr('href');

                if (href) {
                    $link.attr('title', $link.attr('title') + ' (DeepL)');
                }
            });
        });
        </script>
        <?php
    }
}

// Initialize
new Humanitarian_DeepL_Translation();

/**
 * Admin page for bulk translation
 */
function humanitarian_add_translation_menu() {
    add_submenu_page(
        'tools.php',
        __('DeepL Bulk Translate', 'humanitarianblog'),
        __('DeepL Translate', 'humanitarianblog'),
        'manage_options',
        'deepl-translate',
        'humanitarian_render_translation_page'
    );
}
add_action('admin_menu', 'humanitarian_add_translation_menu');

/**
 * Render bulk translation page
 */
function humanitarian_render_translation_page() {
    if (!function_exists('pll_languages_list')) {
        echo '<div class="wrap"><h1>DeepL Translation</h1>';
        echo '<div class="notice notice-error"><p>' . __('Polylang plugin is required.', 'humanitarianblog') . '</p></div>';
        echo '</div>';
        return;
    }

    $languages = pll_languages_list(array('fields' => 'slug'));
    ?>
    <div class="wrap">
        <h1><?php _e('🌐 DeepL Bulk Translation', 'humanitarianblog'); ?></h1>

        <div class="card" style="max-width: 600px; padding: 20px; margin-top: 20px;">
            <h2><?php _e('Translate All Content', 'humanitarianblog'); ?></h2>
            <p class="description">
                <?php _e('Automatically translate all posts and pages that don\'t have translations yet.', 'humanitarianblog'); ?>
            </p>

            <form id="bulk-translate-form" style="margin-top: 20px;">
                <?php wp_nonce_field('deepl_translate_nonce', 'deepl_nonce'); ?>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="source_lang"><?php _e('Source Language', 'humanitarianblog'); ?></label>
                        </th>
                        <td>
                            <select name="source_lang" id="source_lang">
                                <?php foreach ($languages as $lang) : ?>
                                    <option value="<?php echo esc_attr($lang); ?>" <?php selected($lang, 'en'); ?>>
                                        <?php echo strtoupper($lang); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="target_lang"><?php _e('Target Language', 'humanitarianblog'); ?></label>
                        </th>
                        <td>
                            <select name="target_lang" id="target_lang">
                                <?php foreach ($languages as $lang) : ?>
                                    <option value="<?php echo esc_attr($lang); ?>" <?php selected($lang, 'tr'); ?>>
                                        <?php echo strtoupper($lang); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="content_type"><?php _e('Content Type', 'humanitarianblog'); ?></label>
                        </th>
                        <td>
                            <select name="content_type" id="content_type">
                                <option value="post"><?php _e('Articles (Posts)', 'humanitarianblog'); ?></option>
                                <option value="page"><?php _e('Pages', 'humanitarianblog'); ?></option>
                                <option value="all"><?php _e('All Content', 'humanitarianblog'); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e('Options', 'humanitarianblog'); ?></label>
                        </th>
                        <td>
                            <label>
                                <input type="checkbox" name="skip_existing" value="1" checked>
                                <?php _e('Skip posts that already have translations', 'humanitarianblog'); ?>
                            </label>
                            <br><br>
                            <label>
                                <input type="checkbox" name="publish_immediately" value="1">
                                <?php _e('Publish translations immediately (otherwise save as draft)', 'humanitarianblog'); ?>
                            </label>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" class="button button-primary button-hero" id="start-bulk-translate">
                        <?php _e('🚀 Start Translation', 'humanitarianblog'); ?>
                    </button>
                </p>
            </form>

            <div id="translation-progress" style="display: none; margin-top: 20px;">
                <h3><?php _e('Translation Progress', 'humanitarianblog'); ?></h3>
                <div class="progress-bar-wrapper" style="background: #e0e0e0; border-radius: 4px; height: 24px; overflow: hidden;">
                    <div class="progress-bar" style="background: #2271b1; height: 100%; width: 0%; transition: width 0.3s;"></div>
                </div>
                <p class="progress-text" style="margin-top: 10px;"></p>
                <div class="translation-log" style="background: #f5f5f5; padding: 15px; margin-top: 15px; max-height: 300px; overflow-y: auto; font-family: monospace; font-size: 12px;"></div>
            </div>
        </div>

        <div class="card" style="max-width: 600px; padding: 20px; margin-top: 20px;">
            <h2><?php _e('API Status', 'humanitarianblog'); ?></h2>
            <?php
            $api_key = defined('DEEPL_API_KEY') ? DEEPL_API_KEY : '';
            if (empty($api_key)) {
                echo '<p style="color: #dc3545;">❌ ' . __('DeepL API key is not configured in wp-config.php', 'humanitarianblog') . '</p>';
            } else {
                // Check API usage
                $response = wp_remote_get('https://api-free.deepl.com/v2/usage', array(
                    'headers' => array(
                        'Authorization' => 'DeepL-Auth-Key ' . $api_key,
                    ),
                ));

                if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
                    $usage = json_decode(wp_remote_retrieve_body($response), true);
                    $used = isset($usage['character_count']) ? $usage['character_count'] : 0;
                    $limit = isset($usage['character_limit']) ? $usage['character_limit'] : 500000;
                    $percent = round(($used / $limit) * 100, 1);

                    echo '<p style="color: #28a745;">✅ ' . __('DeepL API is connected', 'humanitarianblog') . '</p>';
                    echo '<p><strong>' . __('Usage:', 'humanitarianblog') . '</strong> ' . number_format($used) . ' / ' . number_format($limit) . ' ' . __('characters', 'humanitarianblog') . ' (' . $percent . '%)</p>';
                    echo '<div style="background: #e0e0e0; border-radius: 4px; height: 10px; overflow: hidden;">';
                    echo '<div style="background: ' . ($percent > 80 ? '#dc3545' : '#28a745') . '; height: 100%; width: ' . $percent . '%;"></div>';
                    echo '</div>';
                } else {
                    echo '<p style="color: #dc3545;">❌ ' . __('Could not connect to DeepL API', 'humanitarianblog') . '</p>';
                }
            }
            ?>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#bulk-translate-form').on('submit', function(e) {
            e.preventDefault();

            var sourceLang = $('#source_lang').val();
            var targetLang = $('#target_lang').val();
            var contentType = $('#content_type').val();
            var skipExisting = $('input[name="skip_existing"]').is(':checked');
            var publishImmediately = $('input[name="publish_immediately"]').is(':checked');

            if (sourceLang === targetLang) {
                alert('<?php _e('Source and target languages must be different.', 'humanitarianblog'); ?>');
                return;
            }

            $('#start-bulk-translate').prop('disabled', true).text('<?php _e('Processing...', 'humanitarianblog'); ?>');
            $('#translation-progress').show();

            // Get posts to translate
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'get_posts_for_translation',
                    nonce: $('#deepl_nonce').val(),
                    source_lang: sourceLang,
                    target_lang: targetLang,
                    content_type: contentType,
                    skip_existing: skipExisting ? 1 : 0
                },
                success: function(response) {
                    if (response.success && response.data.posts.length > 0) {
                        translatePosts(response.data.posts, targetLang, publishImmediately, 0);
                    } else {
                        $('.progress-text').text('<?php _e('No posts found to translate.', 'humanitarianblog'); ?>');
                        $('#start-bulk-translate').prop('disabled', false).text('<?php _e('🚀 Start Translation', 'humanitarianblog'); ?>');
                    }
                },
                error: function() {
                    $('.progress-text').text('<?php _e('Error fetching posts.', 'humanitarianblog'); ?>');
                    $('#start-bulk-translate').prop('disabled', false).text('<?php _e('🚀 Start Translation', 'humanitarianblog'); ?>');
                }
            });
        });

        function translatePosts(posts, targetLang, publish, index) {
            if (index >= posts.length) {
                $('.progress-bar').css('width', '100%');
                $('.progress-text').text('<?php _e('Translation complete!', 'humanitarianblog'); ?> (' + posts.length + ' <?php _e('posts', 'humanitarianblog'); ?>)');
                $('#start-bulk-translate').prop('disabled', false).text('<?php _e('🚀 Start Translation', 'humanitarianblog'); ?>');
                return;
            }

            var post = posts[index];
            var percent = Math.round(((index + 1) / posts.length) * 100);

            $('.progress-bar').css('width', percent + '%');
            $('.progress-text').text('<?php _e('Translating', 'humanitarianblog'); ?>: ' + post.title + ' (' + (index + 1) + '/' + posts.length + ')');

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'deepl_translate_post',
                    nonce: deeplTranslation.nonce,
                    post_id: post.id,
                    source_lang: post.lang,
                    target_lang: targetLang,
                    publish: publish ? 1 : 0
                },
                success: function(response) {
                    var status = response.success ? '✅' : '❌';
                    var message = response.success ? response.data.message : response.data.message;
                    $('.translation-log').prepend('<div>' + status + ' ' + post.title + ' - ' + message + '</div>');
                    translatePosts(posts, targetLang, publish, index + 1);
                },
                error: function() {
                    $('.translation-log').prepend('<div>❌ ' + post.title + ' - <?php _e('Error', 'humanitarianblog'); ?></div>');
                    translatePosts(posts, targetLang, publish, index + 1);
                }
            });
        }
    });
    </script>
    <?php
}

/**
 * AJAX handler to get posts for translation
 */
function humanitarian_get_posts_for_translation() {
    check_ajax_referer('deepl_translate_nonce', 'nonce');

    $source_lang = sanitize_text_field($_POST['source_lang']);
    $target_lang = sanitize_text_field($_POST['target_lang']);
    $content_type = sanitize_text_field($_POST['content_type']);
    $skip_existing = intval($_POST['skip_existing']);

    $post_types = ($content_type === 'all') ? array('post', 'page') : array($content_type);

    $args = array(
        'post_type' => $post_types,
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'lang' => $source_lang,
    );

    $posts = get_posts($args);
    $result = array();

    foreach ($posts as $post) {
        if ($skip_existing) {
            $translations = pll_get_post_translations($post->ID);
            if (isset($translations[$target_lang]) && $translations[$target_lang] > 0) {
                continue;
            }
        }

        $result[] = array(
            'id' => $post->ID,
            'title' => $post->post_title,
            'lang' => $source_lang,
        );
    }

    wp_send_json_success(array('posts' => $result));
}
add_action('wp_ajax_get_posts_for_translation', 'humanitarian_get_posts_for_translation');
