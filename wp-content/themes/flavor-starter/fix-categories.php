<?php
/**
 * Fix Categories & Add Featured Images
 */

require_once(__DIR__ . '/../../../wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/taxonomy.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');

if (!current_user_can('administrator')) {
    die('Admin access required.');
}

// Increase time limit for image downloads
set_time_limit(300);

?>
<!DOCTYPE html>
<html>
<head>
    <title>Fixing Categories & Adding Images</title>
    <style>
        body { font-family: system-ui, sans-serif; padding: 40px; max-width: 900px; margin: 0 auto; background: #f5f5f5; }
        h1 { color: #c0392b; }
        .card { background: white; padding: 20px; margin: 15px 0; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .success { border-left: 4px solid #27ae60; }
        .item { padding: 8px 0; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; }
        .cat { background: #3498db; color: white; padding: 2px 8px; border-radius: 3px; font-size: 12px; }
        .img { background: #27ae60; color: white; padding: 2px 8px; border-radius: 3px; font-size: 12px; }
        .thumb { width: 60px; height: 40px; object-fit: cover; border-radius: 4px; }
    </style>
</head>
<body>
<h1>🏷️ Fixing Categories & Adding Featured Images</h1>

<?php

/**
 * Download image from URL and attach to post
 */
function attach_remote_image($image_url, $post_id, $desc = '') {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    require_once(ABSPATH . 'wp-admin/includes/image.php');

    // Download file
    $tmp = download_url($image_url);
    if (is_wp_error($tmp)) {
        return false;
    }

    $file_array = [
        'name' => 'featured-' . $post_id . '-' . rand(1000, 9999) . '.jpg',
        'tmp_name' => $tmp,
    ];

    // Upload and attach
    $id = media_handle_sideload($file_array, $post_id, $desc);

    if (is_wp_error($id)) {
        @unlink($tmp);
        return false;
    }

    return $id;
}

// Create categories if they don't exist
$categories_to_create = [
    'News' => 'Breaking news and current events from around the world',
    'Opinion' => 'Opinion pieces, editorials and commentary',
    'Investigation' => 'In-depth investigative journalism',
    'Analysis' => 'Comprehensive analysis and expert insights',
    'Feature' => 'Long-form feature stories and human interest',
    'Breaking' => 'Breaking news alerts and developing stories',
    'Report' => 'Special reports and in-depth coverage',
    'Interview' => 'Exclusive interviews with key figures',
];

echo '<div class="card"><h3>📁 Creating Categories...</h3>';

$category_ids = [];
foreach ($categories_to_create as $name => $description) {
    $existing = get_category_by_slug(sanitize_title($name));
    if ($existing) {
        $category_ids[$name] = $existing->term_id;
        echo "<div class='item'>✓ Exists: <span class='cat'>$name</span></div>";
    } else {
        $cat_id = wp_create_category($name);
        if ($cat_id) {
            $category_ids[$name] = $cat_id;
            wp_update_term($cat_id, 'category', ['description' => $description]);
            echo "<div class='item'>✓ Created: <span class='cat'>$name</span></div>";
        }
    }
}
echo '</div>';

// Map Article Types to Categories
$type_to_category = [
    'news' => 'News',
    'opinion' => 'Opinion',
    'investigation' => 'Investigation',
    'in-depth-analysis' => 'Analysis',
    'analysis' => 'Analysis',
    'feature' => 'Feature',
    'breaking' => 'Breaking',
    'report' => 'Report',
    'interview' => 'Interview',
];

// Get all posts
$posts = get_posts([
    'post_type' => 'post',
    'posts_per_page' => -1,
    'post_status' => 'publish',
]);

echo '<div class="card"><h3>📝 Updating ' . count($posts) . ' Posts (categories + images)...</h3>';
echo '<p><em>This may take a few minutes for image downloads...</em></p>';

// Flush output
if (ob_get_level()) ob_flush();
flush();

$updated = 0;
$images_added = 0;

foreach ($posts as $index => $post) {
    $status = [];

    // === CATEGORIES ===
    $article_types = wp_get_post_terms($post->ID, 'article_type');
    $cat_name = 'News'; // default

    if (!empty($article_types) && !is_wp_error($article_types)) {
        $type_slug = strtolower($article_types[0]->slug);
        if (isset($type_to_category[$type_slug])) {
            $cat_name = $type_to_category[$type_slug];
        }
    } else {
        // Check title for category hints
        $title = strtolower($post->post_title);
        if (strpos($title, 'opinion:') !== false) $cat_name = 'Opinion';
        elseif (strpos($title, 'analysis:') !== false) $cat_name = 'Analysis';
        elseif (strpos($title, 'investigation:') !== false) $cat_name = 'Investigation';
        elseif (strpos($title, 'feature:') !== false) $cat_name = 'Feature';
        elseif (strpos($title, 'breaking:') !== false || strpos($title, 'breaking') === 0) $cat_name = 'Breaking';
        elseif (strpos($title, 'interview:') !== false) $cat_name = 'Interview';
    }

    if (isset($category_ids[$cat_name])) {
        wp_set_post_categories($post->ID, [$category_ids[$cat_name]]);
        $status[] = "<span class='cat'>$cat_name</span>";
        $updated++;
    }

    // === FEATURED IMAGE ===
    $thumb_html = '';
    if (!has_post_thumbnail($post->ID)) {
        // Use picsum.photos for random images - humanitarian/documentary style
        $image_url = "https://picsum.photos/1200/800?random=" . ($post->ID + rand(1, 10000));

        $attach_id = attach_remote_image($image_url, $post->ID, $post->post_title);

        if ($attach_id) {
            set_post_thumbnail($post->ID, $attach_id);
            $thumb_url = wp_get_attachment_thumb_url($attach_id);
            $thumb_html = "<img src='$thumb_url' class='thumb' alt=''>";
            $status[] = "<span class='img'>+Image</span>";
            $images_added++;
        }
    } else {
        $thumb_url = get_the_post_thumbnail_url($post->ID, 'thumbnail');
        if ($thumb_url) {
            $thumb_html = "<img src='$thumb_url' class='thumb' alt=''>";
        }
        $status[] = "<span class='img'>Has Image</span>";
    }

    $title_short = mb_substr($post->post_title, 0, 45) . (mb_strlen($post->post_title) > 45 ? '...' : '');
    echo "<div class='item'>$thumb_html <strong>" . esc_html($title_short) . "</strong> " . implode(' ', $status) . "</div>";

    // Flush every 5 posts
    if ($index % 5 === 0) {
        if (ob_get_level()) ob_flush();
        flush();
    }
}

echo '</div>';

echo '<div class="card success">
<h2>✅ Done!</h2>
<ul>
<li>Categories updated: <strong>' . $updated . '</strong> posts</li>
<li>Images added: <strong>' . $images_added . '</strong> posts</li>
</ul>
<p>
<a href="' . admin_url('edit.php') . '" target="_blank" style="color:#3498db;">View Posts</a> |
<a href="' . home_url() . '" target="_blank" style="color:#3498db;">View Site</a>
</p>
</div>';

echo '<div class="card" style="border-left: 4px solid #e74c3c;">
<h3>⚠️ Security Reminder</h3>
<p>Delete these files after use:</p>
<code>setup-demo-content.php</code><br>
<code>fix-categories.php</code>
</div>';

?>
</body>
</html>
