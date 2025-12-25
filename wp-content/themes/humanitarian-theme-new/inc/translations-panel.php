<?php
/**
 * Translations Panel
 *
 * Admin menu for translation management with DeepL integration
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Translations menu to admin
 */
function humanitarian_add_translations_menu() {
    // Get current user's locale for menu label
    $locale = get_user_locale();

    // Menu labels in different languages
    $menu_labels = array(
        'en_US' => 'Translations',
        'ar'    => 'الترجمات',
        'fr_FR' => 'Traductions',
    );

    $menu_label = isset($menu_labels[$locale]) ? $menu_labels[$locale] : 'Translations';

    add_menu_page(
        __('Translations', 'humanitarian'),
        $menu_label,
        'edit_posts',
        'humanitarian-translations',
        'humanitarian_translations_page',
        'dashicons-translation',
        26
    );
}
add_action('admin_menu', 'humanitarian_add_translations_menu');

/**
 * Translations admin page
 */
function humanitarian_translations_page() {
    // Get DeepL API key from options
    $api_key = get_option('humanitarian_deepl_api_key', '');
    $is_free_api = get_option('humanitarian_deepl_free_api', true);

    // Get translation stats
    $total_translations = get_option('humanitarian_translation_count', 0);
    $characters_used = get_option('humanitarian_deepl_characters_used', 0);

    // API usage info
    $api_usage = null;
    if (!empty($api_key)) {
        $api_usage = humanitarian_get_deepl_usage($api_key, $is_free_api);
    }

    ?>
    <div class="wrap humanitarian-translations-wrap">
        <h1>
            <span class="dashicons dashicons-translation"></span>
            <?php _e('Translations', 'humanitarian'); ?>
        </h1>

        <div class="translations-dashboard">
            <!-- API Status Card -->
            <div class="translation-card api-status-card">
                <h2><?php _e('DeepL API Status', 'humanitarian'); ?></h2>

                <?php if (empty($api_key)) : ?>
                    <div class="api-status not-configured">
                        <span class="status-icon">⚠️</span>
                        <p><?php _e('API key not configured', 'humanitarian'); ?></p>
                    </div>
                <?php elseif ($api_usage && !isset($api_usage['error'])) : ?>
                    <div class="api-status configured">
                        <span class="status-icon">✅</span>
                        <p><?php _e('API Connected', 'humanitarian'); ?></p>
                    </div>

                    <div class="usage-stats">
                        <div class="stat">
                            <span class="stat-label"><?php _e('Characters Used', 'humanitarian'); ?></span>
                            <span class="stat-value"><?php echo number_format($api_usage['character_count']); ?></span>
                        </div>
                        <div class="stat">
                            <span class="stat-label"><?php _e('Character Limit', 'humanitarian'); ?></span>
                            <span class="stat-value"><?php echo number_format($api_usage['character_limit']); ?></span>
                        </div>
                        <div class="stat">
                            <span class="stat-label"><?php _e('Remaining', 'humanitarian'); ?></span>
                            <span class="stat-value remaining"><?php echo number_format($api_usage['character_limit'] - $api_usage['character_count']); ?></span>
                        </div>

                        <?php
                        $usage_percent = ($api_usage['character_limit'] > 0)
                            ? round(($api_usage['character_count'] / $api_usage['character_limit']) * 100, 1)
                            : 0;
                        ?>
                        <div class="usage-bar">
                            <div class="usage-bar-fill" style="width: <?php echo $usage_percent; ?>%"></div>
                        </div>
                        <p class="usage-percent"><?php echo $usage_percent; ?>% <?php _e('used', 'humanitarian'); ?></p>
                    </div>
                <?php else : ?>
                    <div class="api-status error">
                        <span class="status-icon">❌</span>
                        <p><?php _e('API Error', 'humanitarian'); ?>: <?php echo isset($api_usage['error']) ? esc_html($api_usage['error']) : ''; ?></p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Translation Stats Card -->
            <div class="translation-card stats-card">
                <h2><?php _e('Translation Statistics', 'humanitarian'); ?></h2>

                <div class="stats-grid">
                    <div class="stat-box">
                        <span class="stat-number"><?php echo intval($total_translations); ?></span>
                        <span class="stat-label"><?php _e('Total Translations', 'humanitarian'); ?></span>
                    </div>
                    <div class="stat-box">
                        <span class="stat-number"><?php echo number_format(intval($characters_used)); ?></span>
                        <span class="stat-label"><?php _e('Characters Translated', 'humanitarian'); ?></span>
                    </div>
                </div>

                <?php
                // Count articles by language
                $languages = humanitarian_get_languages();
                ?>
                <h3><?php _e('Articles by Language', 'humanitarian'); ?></h3>
                <div class="language-counts">
                    <?php foreach ($languages as $slug => $lang) :
                        $term = get_term_by('slug', $slug, 'language');
                        $count = $term ? $term->count : 0;
                    ?>
                        <div class="lang-count">
                            <span class="lang-name"><?php echo esc_html($lang['native']); ?></span>
                            <span class="lang-number"><?php echo intval($count); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- API Settings Card -->
            <div class="translation-card settings-card">
                <h2><?php _e('API Settings', 'humanitarian'); ?></h2>

                <form method="post" action="<?php echo admin_url('admin-post.php'); ?>">
                    <?php wp_nonce_field('humanitarian_deepl_settings', 'deepl_nonce'); ?>
                    <input type="hidden" name="action" value="humanitarian_save_deepl_settings">

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="deepl_api_key"><?php _e('DeepL API Key', 'humanitarian'); ?></label>
                            </th>
                            <td>
                                <input type="password"
                                       id="deepl_api_key"
                                       name="deepl_api_key"
                                       value="<?php echo esc_attr($api_key); ?>"
                                       class="regular-text"
                                       placeholder="<?php _e('Enter your DeepL API key', 'humanitarian'); ?>">
                                <p class="description">
                                    <?php _e('Get your API key from', 'humanitarian'); ?>
                                    <a href="https://www.deepl.com/pro-api" target="_blank">DeepL API</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e('API Type', 'humanitarian'); ?></th>
                            <td>
                                <label>
                                    <input type="radio" name="deepl_free_api" value="1" <?php checked($is_free_api, true); ?>>
                                    <?php _e('Free API', 'humanitarian'); ?>
                                </label>
                                <br>
                                <label>
                                    <input type="radio" name="deepl_free_api" value="0" <?php checked($is_free_api, false); ?>>
                                    <?php _e('Pro API', 'humanitarian'); ?>
                                </label>
                            </td>
                        </tr>
                    </table>

                    <?php submit_button(__('Save Settings', 'humanitarian')); ?>
                </form>
            </div>
        </div>
    </div>

    <style>
        .humanitarian-translations-wrap h1 {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .humanitarian-translations-wrap h1 .dashicons {
            font-size: 30px;
            width: 30px;
            height: 30px;
        }

        .translations-dashboard {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .translation-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .translation-card h2 {
            margin: 0 0 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 18px;
            color: #1e293b;
        }

        .api-status {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .api-status.configured { background: #d1fae5; }
        .api-status.not-configured { background: #fef3c7; }
        .api-status.error { background: #fee2e2; }

        .status-icon { font-size: 24px; }

        .usage-stats .stat {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .stat-label { color: #64748b; }
        .stat-value { font-weight: 600; color: #1e293b; }
        .stat-value.remaining { color: #059669; }

        .usage-bar {
            height: 8px;
            background: #e2e8f0;
            border-radius: 4px;
            margin-top: 15px;
            overflow: hidden;
        }

        .usage-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #0D5C63, #10b981);
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .usage-percent {
            text-align: center;
            margin-top: 8px;
            color: #64748b;
            font-size: 13px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-box {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }

        .stat-number {
            display: block;
            font-size: 28px;
            font-weight: 700;
            color: #0D5C63;
        }

        .stat-box .stat-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .translation-card h3 {
            font-size: 14px;
            color: #64748b;
            margin: 20px 0 10px;
        }

        .language-counts {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .lang-count {
            display: flex;
            justify-content: space-between;
            padding: 10px 15px;
            background: #f8fafc;
            border-radius: 6px;
        }

        .lang-name { font-weight: 500; }
        .lang-number {
            background: #0D5C63;
            color: #fff;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 13px;
        }

        .settings-card .form-table th {
            padding: 15px 0;
        }
    </style>
    <?php
}

/**
 * Get DeepL API usage
 */
function humanitarian_get_deepl_usage($api_key, $is_free = true) {
    $base_url = $is_free
        ? 'https://api-free.deepl.com/v2/usage'
        : 'https://api.deepl.com/v2/usage';

    $response = wp_remote_get($base_url, array(
        'headers' => array(
            'Authorization' => 'DeepL-Auth-Key ' . $api_key
        ),
        'timeout' => 15
    ));

    if (is_wp_error($response)) {
        return array('error' => $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (isset($data['character_count'])) {
        return $data;
    }

    return array('error' => __('Invalid API response', 'humanitarian'));
}

/**
 * Save DeepL settings
 */
function humanitarian_save_deepl_settings() {
    if (!isset($_POST['deepl_nonce']) || !wp_verify_nonce($_POST['deepl_nonce'], 'humanitarian_deepl_settings')) {
        wp_die(__('Security check failed', 'humanitarian'));
    }

    if (!current_user_can('manage_options')) {
        wp_die(__('Permission denied', 'humanitarian'));
    }

    $api_key = isset($_POST['deepl_api_key']) ? sanitize_text_field($_POST['deepl_api_key']) : '';
    $is_free = isset($_POST['deepl_free_api']) ? (bool) $_POST['deepl_free_api'] : true;

    update_option('humanitarian_deepl_api_key', $api_key);
    update_option('humanitarian_deepl_free_api', $is_free);

    wp_redirect(admin_url('admin.php?page=humanitarian-translations&saved=1'));
    exit;
}
add_action('admin_post_humanitarian_save_deepl_settings', 'humanitarian_save_deepl_settings');

/**
 * Add translate buttons to post editor sidebar
 */
function humanitarian_add_translation_meta_box() {
    add_meta_box(
        'humanitarian_translate',
        __('DeepL Auto Translation', 'humanitarian'),
        'humanitarian_translate_meta_box_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'humanitarian_add_translation_meta_box');

/**
 * Translation meta box callback
 */
function humanitarian_translate_meta_box_callback($post) {
    $api_key = get_option('humanitarian_deepl_api_key', '');
    $post_lang = humanitarian_get_post_language($post->ID);
    $languages = humanitarian_get_languages();

    if (empty($api_key)) {
        echo '<p class="description">';
        echo __('DeepL API key not configured.', 'humanitarian') . ' ';
        echo '<a href="' . admin_url('admin.php?page=humanitarian-translations') . '">' . __('Configure now', 'humanitarian') . '</a>';
        echo '</p>';
        return;
    }

    wp_nonce_field('humanitarian_translate_post', 'translate_nonce');

    echo '<div class="translate-buttons">';

    foreach ($languages as $slug => $lang) {
        if ($slug === $post_lang) {
            continue; // Skip current language
        }

        $button_text = sprintf(__('Translate to %s', 'humanitarian'), $lang['native']);

        echo '<button type="button" class="button translate-btn" data-lang="' . esc_attr($slug) . '" data-post-id="' . esc_attr($post->ID) . '">';
        echo esc_html($button_text);
        echo '</button>';
    }

    echo '</div>';

    echo '<p class="description" style="margin-top: 10px;">';
    echo __('Creates a new translated article', 'humanitarian');
    echo '</p>';

    // Add inline styles
    echo '<style>
        .translate-buttons {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .translate-btn {
            width: 100%;
            justify-content: center;
        }
        .translate-btn:hover {
            background: #0D5C63 !important;
            color: #fff !important;
            border-color: #0D5C63 !important;
        }
    </style>';
}

/**
 * Add language selector to post editor sidebar
 */
function humanitarian_add_language_meta_box() {
    add_meta_box(
        'humanitarian_post_language',
        __('Language', 'humanitarian'),
        'humanitarian_language_meta_box_callback',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'humanitarian_add_language_meta_box');

/**
 * Language meta box callback
 */
function humanitarian_language_meta_box_callback($post) {
    $post_lang = humanitarian_get_post_language($post->ID);
    $languages = humanitarian_get_languages();

    wp_nonce_field('humanitarian_post_language', 'post_language_nonce');

    echo '<select name="post_language" id="post_language" class="widefat">';

    foreach ($languages as $slug => $lang) {
        $selected = ($slug === $post_lang) ? ' selected' : '';
        echo '<option value="' . esc_attr($slug) . '"' . $selected . '>' . esc_html($lang['native']) . '</option>';
    }

    echo '</select>';
    echo '<p class="description">' . __('Select the language this article is written in', 'humanitarian') . '</p>';
}

/**
 * Save post language from meta box
 */
function humanitarian_save_post_language($post_id) {
    if (!isset($_POST['post_language_nonce']) || !wp_verify_nonce($_POST['post_language_nonce'], 'humanitarian_post_language')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['post_language'])) {
        $lang = sanitize_text_field($_POST['post_language']);
        wp_set_object_terms($post_id, $lang, 'language');
    }
}
add_action('save_post', 'humanitarian_save_post_language');
