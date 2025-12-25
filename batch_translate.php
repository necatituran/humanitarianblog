<?php
/**
 * Batch Translation Script using DeepSeek API
 * Token-efficient approach: translates multiple items in one request
 */

// Increase limits
set_time_limit(600);
ini_set('memory_limit', '512M');

// Load WordPress
require_once __DIR__ . '/wp-load.php';

// Configuration
$DEEPSEEK_API_KEY = 'sk-e583e34633024e919c85dba1016e371c';
$DEEPSEEK_API_URL = 'https://api.deepseek.com/v1/chat/completions';

// Get action from query
$action = isset($_GET['action']) ? $_GET['action'] : 'status';
$lang = isset($_GET['lang']) ? $_GET['lang'] : 'fr';
$batch = isset($_GET['batch']) ? intval($_GET['batch']) : 0;
$type = isset($_GET['type']) ? $_GET['type'] : 'post';

header('Content-Type: text/plain; charset=utf-8');

/**
 * Call DeepSeek API
 */
function call_deepseek($prompt, $system_prompt = '') {
    global $DEEPSEEK_API_KEY, $DEEPSEEK_API_URL;

    $messages = [];
    if ($system_prompt) {
        $messages[] = ['role' => 'system', 'content' => $system_prompt];
    }
    $messages[] = ['role' => 'user', 'content' => $prompt];

    $data = [
        'model' => 'deepseek-chat',
        'messages' => $messages,
        'temperature' => 0.3,
        'max_tokens' => 8000,
    ];

    $ch = curl_init($DEEPSEEK_API_URL);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $DEEPSEEK_API_KEY,
        ],
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_TIMEOUT => 300,
        CURLOPT_CONNECTTIMEOUT => 30,
    ]);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => $error];
    }

    $result = json_decode($response, true);
    if (isset($result['choices'][0]['message']['content'])) {
        return ['content' => $result['choices'][0]['message']['content']];
    }

    return ['error' => 'Invalid response', 'raw' => $response];
}

/**
 * Translate a batch of posts
 */
function translate_batch($posts, $target_lang) {
    $lang_name = $target_lang === 'ar' ? 'Arabic' : 'French';
    $lang_code = $target_lang;

    // Prepare items for translation - ONLY title and excerpt for efficiency
    $items = [];
    foreach ($posts as $post) {
        $items[] = [
            'id' => $post->ID,
            'title' => $post->post_title,
            'excerpt' => $post->post_excerpt ?: wp_trim_words(strip_tags($post->post_content), 25),
        ];
    }

    // Create batch prompt
    $json_input = json_encode($items, JSON_UNESCAPED_UNICODE);

    $system = "You are a translator. Translate to {$lang_name}. Return ONLY valid JSON array.";

    $prompt = "Translate to {$lang_name}. Return same JSON structure with translated 'title' and 'excerpt'. Keep 'id' unchanged.

{$json_input}";

    $result = call_deepseek($prompt, $system);

    if (isset($result['error'])) {
        return $result;
    }

    // Parse response
    $content = $result['content'];
    // Clean markdown code blocks if present
    $content = preg_replace('/^```json?\s*/m', '', $content);
    $content = preg_replace('/\s*```$/m', '', $content);
    $content = trim($content);

    $translated = json_decode($content, true);
    if (!$translated) {
        return ['error' => 'Failed to parse JSON response', 'raw' => $content];
    }

    return ['translations' => $translated];
}

/**
 * Save translated post
 */
function save_translation($original_id, $translated, $lang) {
    $original = get_post($original_id);
    if (!$original) return false;

    // Check if translation already exists
    $existing = get_posts([
        'post_type' => $original->post_type,
        'meta_key' => '_original_post_id',
        'meta_value' => $original_id,
        'tax_query' => [
            [
                'taxonomy' => 'language',
                'field' => 'slug',
                'terms' => $lang,
            ]
        ],
        'posts_per_page' => 1,
    ]);

    // Get content from original if not translated
    $content = isset($translated['content']) ? $translated['content'] : $original->post_content;
    $excerpt = isset($translated['excerpt']) ? $translated['excerpt'] : '';

    if (!empty($existing)) {
        // Update existing
        $post_id = $existing[0]->ID;
        wp_update_post([
            'ID' => $post_id,
            'post_title' => $translated['title'],
            'post_content' => $content,
            'post_excerpt' => $excerpt,
        ]);
    } else {
        // Create new
        $post_id = wp_insert_post([
            'post_type' => $original->post_type,
            'post_status' => 'publish',
            'post_author' => $original->post_author,
            'post_title' => $translated['title'],
            'post_content' => $content,
            'post_excerpt' => $excerpt,
            'post_name' => $original->post_name . '-' . $lang,
        ]);

        if ($post_id) {
            // Set meta
            update_post_meta($post_id, '_original_post_id', $original_id);
            update_post_meta($post_id, '_translation_language', $lang);

            // Set language taxonomy
            wp_set_object_terms($post_id, $lang, 'language');

            // Copy categories
            $categories = wp_get_post_categories($original_id);
            if ($categories) {
                wp_set_post_categories($post_id, $categories);
            }

            // Copy featured image
            $thumb_id = get_post_thumbnail_id($original_id);
            if ($thumb_id) {
                set_post_thumbnail($post_id, $thumb_id);
            }
        }
    }

    return $post_id;
}

// Main logic
switch ($action) {
    case 'status':
        $posts = get_posts(['post_type' => 'post', 'post_status' => 'publish', 'numberposts' => -1]);
        $pages = get_posts(['post_type' => 'page', 'post_status' => 'publish', 'numberposts' => -1]);

        // Count translations
        $fr_posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_key' => '_translation_language',
            'meta_value' => 'fr',
        ]);
        $ar_posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_key' => '_translation_language',
            'meta_value' => 'ar',
        ]);

        echo "=== TRANSLATION STATUS ===\n";
        echo "Original posts: " . count($posts) . "\n";
        echo "Original pages: " . count($pages) . "\n";
        echo "French translations: " . count($fr_posts) . "\n";
        echo "Arabic translations: " . count($ar_posts) . "\n";
        echo "\n";
        echo "To translate, use:\n";
        echo "?action=translate&lang=fr&batch=0&type=post\n";
        echo "?action=translate&lang=ar&batch=0&type=post\n";
        break;

    case 'translate':
        $batch_size = 2; // 2 posts per batch for faster response
        $offset = $batch * $batch_size;

        $items = get_posts([
            'post_type' => $type,
            'post_status' => 'publish',
            'numberposts' => $batch_size,
            'offset' => $offset,
            'orderby' => 'ID',
            'order' => 'ASC',
        ]);

        if (empty($items)) {
            echo "No more {$type}s to translate at batch {$batch}\n";
            break;
        }

        echo "=== TRANSLATING BATCH {$batch} ({$lang}) ===\n";
        echo "Items: " . count($items) . "\n\n";

        // Show what we're translating
        foreach ($items as $item) {
            echo "- [{$item->ID}] " . substr($item->post_title, 0, 50) . "...\n";
        }
        echo "\n";

        // Translate
        echo "Calling DeepSeek API...\n";
        $result = translate_batch($items, $lang);

        if (isset($result['error'])) {
            echo "ERROR: " . $result['error'] . "\n";
            if (isset($result['raw'])) {
                echo "Raw: " . substr($result['raw'], 0, 500) . "\n";
            }
            break;
        }

        echo "Translation received, saving...\n\n";

        // Save translations
        foreach ($result['translations'] as $translated) {
            $post_id = save_translation($translated['id'], $translated, $lang);
            if ($post_id) {
                echo "✓ Saved: [{$translated['id']}] -> [{$post_id}] " . substr($translated['title'], 0, 40) . "...\n";
            } else {
                echo "✗ Failed: [{$translated['id']}]\n";
            }
        }

        echo "\n";
        echo "Next batch: ?action=translate&lang={$lang}&batch=" . ($batch + 1) . "&type={$type}\n";
        break;

    case 'translate_all':
        echo "=== TRANSLATING ALL ===\n";
        echo "Language: {$lang}\n";
        echo "Type: {$type}\n\n";

        $batch_size = 2;
        $batch_num = 0;
        $total_translated = 0;

        while (true) {
            $offset = $batch_num * $batch_size;
            $items = get_posts([
                'post_type' => $type,
                'post_status' => 'publish',
                'numberposts' => $batch_size,
                'offset' => $offset,
                'orderby' => 'ID',
                'order' => 'ASC',
            ]);

            if (empty($items)) {
                break;
            }

            echo "--- Batch {$batch_num} ---\n";
            $result = translate_batch($items, $lang);

            if (isset($result['error'])) {
                echo "ERROR: " . $result['error'] . "\n";
                break;
            }

            foreach ($result['translations'] as $translated) {
                $post_id = save_translation($translated['id'], $translated, $lang);
                if ($post_id) {
                    echo "✓ [{$translated['id']}] " . substr($translated['title'], 0, 30) . "\n";
                    $total_translated++;
                }
            }

            $batch_num++;
            sleep(1); // Rate limiting
        }

        echo "\n=== COMPLETE ===\n";
        echo "Total translated: {$total_translated}\n";
        break;

    default:
        echo "Unknown action: {$action}\n";
}
