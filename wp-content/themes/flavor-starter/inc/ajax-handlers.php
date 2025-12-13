<?php
/**
 * AJAX Handlers
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Live Search AJAX Handler
 */
function humanitarianblog_live_search() {
    // Verify nonce
    check_ajax_referer('search_nonce', 'nonce');

    $query = sanitize_text_field($_POST['query']);

    // Input validation
    if (strlen($query) < 3) {
        wp_send_json_error('Query too short');
    }

    if (strlen($query) > 100) {
        wp_send_json_error('Query too long');
    }

    // Rate limiting (10 searches per minute per IP)
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'search_rate_' . md5($user_ip);
    $search_count = get_transient($rate_limit_key);

    if ($search_count && $search_count > 10) {
        wp_send_json_error('Too many requests. Please wait a moment.');
    }

    // Increment rate limit counter
    set_transient($rate_limit_key, $search_count ? $search_count + 1 : 1, 60);

    // Check cache first (5 minutes)
    $cache_key = 'search_' . md5($query);
    $cached_results = get_transient($cache_key);

    if ($cached_results !== false) {
        wp_send_json_success($cached_results);
    }

    // Optimized search query
    $search_query = new WP_Query(array(
        's'                      => $query,
        'posts_per_page'         => 5,
        'post_status'            => 'publish',
        'no_found_rows'          => true,  // Skip pagination COUNT(*) query
        'update_post_meta_cache' => false, // Skip meta cache
        'update_post_term_cache' => true,  // Keep term cache (needed for categories)
    ));

    $results = array();

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();

            $categories = get_the_category();
            $category_name = !empty($categories) ? $categories[0]->name : '';

            // Fallback for thumbnail
            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'card-small');
            if (!$thumbnail) {
                $thumbnail = '';
            }

            $results[] = array(
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'excerpt'   => wp_trim_words(get_the_excerpt(), 20),
                'category'  => $category_name,
                'thumbnail' => $thumbnail,
            );
        }
        wp_reset_postdata();
    }

    // Cache results for 5 minutes
    set_transient($cache_key, $results, 5 * MINUTE_IN_SECONDS);

    wp_send_json_success($results);
}
add_action('wp_ajax_live_search', 'humanitarianblog_live_search');
add_action('wp_ajax_nopriv_live_search', 'humanitarianblog_live_search');

/**
 * Newsletter Signup AJAX Handler
 */
function humanitarianblog_newsletter_signup() {
    // Verify nonce
    check_ajax_referer('humanitarian_nonce', 'nonce');

    $email = sanitize_email($_POST['email']);
    $frequency = sanitize_text_field($_POST['frequency']);

    if (!is_email($email)) {
        wp_send_json_error('Invalid email address');
    }

    // Rate limiting (3 signups per hour per IP)
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'newsletter_rate_' . md5($user_ip);
    $signup_count = get_transient($rate_limit_key);

    if ($signup_count && $signup_count > 3) {
        wp_send_json_error('Too many signup attempts. Please try again later.');
    }

    // Increment rate limit counter
    set_transient($rate_limit_key, $signup_count ? $signup_count + 1 : 1, HOUR_IN_SECONDS);

    global $wpdb;
    $table_name = $wpdb->prefix . 'humanitarian_newsletters';

    // Check if table exists, if not create it
    humanitarianblog_maybe_create_newsletter_table();

    // Check if email already exists
    $exists = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE email = %s",
        $email
    ));

    if ($exists > 0) {
        // Update existing subscription
        $wpdb->update(
            $table_name,
            [
                'frequency' => $frequency,
                'updated_at' => current_time('mysql'),
            ],
            ['email' => $email],
            ['%s', '%s'],
            ['%s']
        );
        wp_send_json_success('Subscription updated!');
    } else {
        // Insert new subscription
        $wpdb->insert(
            $table_name,
            [
                'email' => $email,
                'frequency' => $frequency,
                'created_at' => current_time('mysql'),
                'updated_at' => current_time('mysql'),
            ],
            ['%s', '%s', '%s', '%s']
        );

        if ($wpdb->insert_id) {
            wp_send_json_success('Successfully subscribed!');
        } else {
            wp_send_json_error('Database error. Please try again.');
        }
    }
}
add_action('wp_ajax_newsletter_signup', 'humanitarianblog_newsletter_signup');
add_action('wp_ajax_nopriv_newsletter_signup', 'humanitarianblog_newsletter_signup');

/**
 * Create newsletter table if it doesn't exist
 */
function humanitarianblog_maybe_create_newsletter_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'humanitarian_newsletters';
    $charset_collate = $wpdb->get_charset_collate();

    // Check if table exists
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            email varchar(100) NOT NULL,
            frequency varchar(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY email (email)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

/**
 * Validate Bookmarks AJAX Handler
 */
function humanitarianblog_validate_bookmarks() {
    // Verify nonce
    check_ajax_referer('humanitarian_nonce', 'nonce');

    $post_ids = json_decode(stripslashes($_POST['post_ids']), true);

    if (!is_array($post_ids) || empty($post_ids)) {
        wp_send_json_error('Invalid post IDs');
    }

    // Validate post IDs (check if they exist and are published)
    $valid_ids = [];

    foreach ($post_ids as $post_id) {
        $post_id = intval($post_id);

        if ($post_id > 0 && get_post_status($post_id) === 'publish') {
            $valid_ids[] = (string) $post_id; // Keep as string for localStorage compatibility
        }
    }

    wp_send_json_success([
        'valid_ids' => $valid_ids,
        'removed_count' => count($post_ids) - count($valid_ids),
    ]);
}
add_action('wp_ajax_validate_bookmarks', 'humanitarianblog_validate_bookmarks');
add_action('wp_ajax_nopriv_validate_bookmarks', 'humanitarianblog_validate_bookmarks');

/**
 * Get Bookmarked Posts AJAX Handler
 * Returns full post data for bookmarked posts
 */
function humanitarianblog_get_bookmarked_posts() {
    // Verify nonce
    check_ajax_referer('humanitarian_nonce', 'nonce');

    $post_ids = json_decode(stripslashes($_POST['post_ids']), true);

    if (!is_array($post_ids) || empty($post_ids)) {
        wp_send_json_error('Invalid post IDs');
    }

    // Limit to 100 bookmarks per request
    if (count($post_ids) > 100) {
        $post_ids = array_slice($post_ids, 0, 100);
    }

    // Rate limiting (30 requests per minute per IP)
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'bookmarks_rate_' . md5($user_ip);
    $request_count = get_transient($rate_limit_key);

    if ($request_count && $request_count > 30) {
        wp_send_json_error('Too many requests. Please wait a moment.');
    }

    // Increment rate limit counter
    set_transient($rate_limit_key, $request_count ? $request_count + 1 : 1, 60);

    // Sanitize post IDs
    $post_ids = array_map('intval', $post_ids);
    $post_ids = array_filter($post_ids, function($id) {
        return $id > 0;
    });

    if (empty($post_ids)) {
        wp_send_json_error('No valid post IDs');
    }

    // Query posts
    $query = new WP_Query([
        'post__in' => $post_ids,
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => 100,
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => true,
        'orderby' => 'post__in', // Maintain order from post_ids
    ]);

    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $categories = get_the_category();
            $category_name = !empty($categories) ? $categories[0]->name : '';

            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'card-medium');

            $posts[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'excerpt' => wp_trim_words(get_the_excerpt(), 30),
                'date' => get_the_date('c'), // ISO 8601 format for sorting
                'date_formatted' => get_the_date('F j, Y'),
                'category' => $category_name,
                'thumbnail' => $thumbnail ?: '',
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success([
        'posts' => $posts,
        'total' => count($posts),
    ]);
}
add_action('wp_ajax_get_bookmarked_posts', 'humanitarianblog_get_bookmarked_posts');
add_action('wp_ajax_nopriv_get_bookmarked_posts', 'humanitarianblog_get_bookmarked_posts');