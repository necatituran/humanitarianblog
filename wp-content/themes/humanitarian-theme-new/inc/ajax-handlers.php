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

    // Optimized search query - include both posts and pages
    $search_query = new WP_Query(array(
        's'                      => $query,
        'posts_per_page'         => 8,
        'post_type'              => array('post', 'page'), // Include pages
        'post_status'            => 'publish',
        'no_found_rows'          => true,  // Skip pagination COUNT(*) query
        'update_post_meta_cache' => false, // Skip meta cache
        'update_post_term_cache' => true,  // Keep term cache (needed for categories)
    ));

    $results = array();

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();

            $post_type = get_post_type();

            // Get category for posts, or "Page" label for pages
            if ($post_type === 'page') {
                $category_name = __('Page', 'humanitarian');
            } else {
                $categories = get_the_category();
                $category_name = !empty($categories) ? $categories[0]->name : __('Article', 'humanitarian');
            }

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
                'type'      => $post_type,
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

/**
 * Blog AJAX Pagination & Filtering Handler
 * Allows pagination and sorting without page refresh
 */
function humanitarianblog_load_blog_posts() {
    // Verify nonce
    check_ajax_referer('humanitarian_nonce', 'nonce');

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date';
    $posts_per_page = 9;

    // Validate orderby
    $allowed_orderby = ['date', 'title', 'comment_count'];
    if (!in_array($orderby, $allowed_orderby)) {
        $orderby = 'date';
    }

    // Build query args
    $args = [
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'orderby' => $orderby,
        'order' => ($orderby === 'title') ? 'ASC' : 'DESC',
    ];

    $query = new WP_Query($args);

    $posts = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $categories = get_the_category();
            $category_name = !empty($categories) ? $categories[0]->name : '';
            $category_link = !empty($categories) ? get_category_link($categories[0]->term_id) : '';

            // Get photo caption if available
            $photo_caption = '';
            if (function_exists('humanitarian_get_photo_caption')) {
                $photo_caption = humanitarian_get_photo_caption();
            }

            // Category badge colors
            $cat_colors = ['category-badge--red', 'category-badge--blue', 'category-badge--green', 'category-badge--orange'];
            $cat_color = $cat_colors[array_rand($cat_colors)];

            $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'card-medium');

            // Calculate reading time
            $reading_time = function_exists('humanitarianblog_reading_time')
                ? humanitarianblog_reading_time()
                : '1 min read';

            $posts[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_permalink(),
                'date' => get_the_date(),
                'reading_time' => $reading_time,
                'category' => $category_name,
                'category_link' => $category_link,
                'category_color' => $cat_color,
                'thumbnail' => $thumbnail ?: '',
                'photo_caption' => $photo_caption,
                'classes' => implode(' ', get_post_class('article-card')),
            ];
        }
        wp_reset_postdata();
    }

    // Calculate pagination
    $total_pages = $query->max_num_pages;
    $pagination = [];

    // Build pagination array
    if ($total_pages > 1) {
        // Previous
        if ($page > 1) {
            $pagination[] = ['type' => 'prev', 'page' => $page - 1];
        }

        // Page numbers
        $range = 2;
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i === 1 || $i === $total_pages || ($i >= $page - $range && $i <= $page + $range)) {
                $pagination[] = [
                    'type' => 'page',
                    'page' => $i,
                    'current' => ($i === $page),
                ];
            } elseif ($i === $page - $range - 1 || $i === $page + $range + 1) {
                $pagination[] = ['type' => 'dots'];
            }
        }

        // Next
        if ($page < $total_pages) {
            $pagination[] = ['type' => 'next', 'page' => $page + 1];
        }
    }

    wp_send_json_success([
        'posts' => $posts,
        'current_page' => $page,
        'total_pages' => $total_pages,
        'total_posts' => $query->found_posts,
        'pagination' => $pagination,
    ]);
}
add_action('wp_ajax_load_blog_posts', 'humanitarianblog_load_blog_posts');
add_action('wp_ajax_nopriv_load_blog_posts', 'humanitarianblog_load_blog_posts');

/**
 * Frontend Registration AJAX Handler
 */
function humanitarianblog_frontend_register() {
    // Verify nonce
    if (!isset($_POST['frontend_register_nonce']) || !wp_verify_nonce($_POST['frontend_register_nonce'], 'frontend_register')) {
        wp_redirect(home_url('/register/?register=failed&message=' . urlencode('Security check failed.')));
        exit;
    }

    // Check if registration is enabled
    if (!get_option('users_can_register')) {
        wp_redirect(home_url('/register/?register=failed&message=' . urlencode('Registration is currently disabled.')));
        exit;
    }

    // Rate limiting (5 registrations per hour per IP)
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'register_rate_' . md5($user_ip);
    $register_count = get_transient($rate_limit_key);

    if ($register_count && $register_count > 5) {
        wp_redirect(home_url('/register/?register=failed&message=' . urlencode('Too many registration attempts. Please try again later.')));
        exit;
    }

    // Get and sanitize input
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $user_email = sanitize_email($_POST['user_email']);
    $user_login = sanitize_user($_POST['user_login']);
    $user_pass = $_POST['user_pass'];
    $user_pass_confirm = $_POST['user_pass_confirm'];
    $newsletter = isset($_POST['newsletter_subscribe']) ? '1' : '0';

    // Validation
    $errors = [];

    if (empty($first_name)) {
        $errors[] = 'First name is required.';
    }

    if (empty($last_name)) {
        $errors[] = 'Last name is required.';
    }

    if (!is_email($user_email)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (email_exists($user_email)) {
        $errors[] = 'This email address is already registered.';
    }

    if (empty($user_login) || strlen($user_login) < 3) {
        $errors[] = 'Username must be at least 3 characters.';
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $user_login)) {
        $errors[] = 'Username can only contain letters, numbers, and underscores.';
    }

    if (username_exists($user_login)) {
        $errors[] = 'This username is already taken.';
    }

    if (strlen($user_pass) < 8) {
        $errors[] = 'Password must be at least 8 characters.';
    }

    if ($user_pass !== $user_pass_confirm) {
        $errors[] = 'Passwords do not match.';
    }

    if (!empty($errors)) {
        wp_redirect(home_url('/register/?register=failed&message=' . urlencode(implode(' ', $errors))));
        exit;
    }

    // Increment rate limit counter
    set_transient($rate_limit_key, $register_count ? $register_count + 1 : 1, HOUR_IN_SECONDS);

    // Create user
    $user_id = wp_create_user($user_login, $user_pass, $user_email);

    if (is_wp_error($user_id)) {
        wp_redirect(home_url('/register/?register=failed&message=' . urlencode($user_id->get_error_message())));
        exit;
    }

    // Update user meta
    wp_update_user([
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . ' ' . $last_name,
    ]);

    // Set role to subscriber
    $user = new WP_User($user_id);
    $user->set_role('subscriber');

    // Store newsletter preference
    update_user_meta($user_id, 'newsletter_subscribed', $newsletter);

    // Auto-login the user
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    // Redirect to account page
    wp_redirect(home_url('/my-account/?welcome=true'));
    exit;
}
add_action('admin_post_nopriv_frontend_register', 'humanitarianblog_frontend_register');
add_action('admin_post_frontend_register', 'humanitarianblog_frontend_register');

/**
 * Frontend Profile Update AJAX Handler
 */
function humanitarianblog_update_frontend_profile() {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_redirect(home_url('/login/'));
        exit;
    }

    // Verify nonce
    if (!isset($_POST['profile_nonce']) || !wp_verify_nonce($_POST['profile_nonce'], 'update_profile')) {
        wp_redirect(home_url('/my-account/?tab=profile&error=security'));
        exit;
    }

    $current_user = wp_get_current_user();

    // Sanitize input
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $display_name = sanitize_text_field($_POST['display_name']);
    $user_email = sanitize_email($_POST['user_email']);
    $description = sanitize_textarea_field($_POST['description']);

    // Validate email
    if (!is_email($user_email)) {
        wp_redirect(home_url('/my-account/?tab=profile&error=email'));
        exit;
    }

    // Check if email is already used by another user
    $email_user = get_user_by('email', $user_email);
    if ($email_user && $email_user->ID !== $current_user->ID) {
        wp_redirect(home_url('/my-account/?tab=profile&error=email_taken'));
        exit;
    }

    // Update user
    $result = wp_update_user([
        'ID' => $current_user->ID,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $display_name,
        'user_email' => $user_email,
        'description' => $description,
    ]);

    if (is_wp_error($result)) {
        wp_redirect(home_url('/my-account/?tab=profile&error=update'));
        exit;
    }

    wp_redirect(home_url('/my-account/?tab=profile&updated=true'));
    exit;
}
add_action('admin_post_update_frontend_profile', 'humanitarianblog_update_frontend_profile');

/**
 * Frontend Password Change Handler
 */
function humanitarianblog_change_frontend_password() {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_redirect(home_url('/login/'));
        exit;
    }

    // Verify nonce
    if (!isset($_POST['password_nonce']) || !wp_verify_nonce($_POST['password_nonce'], 'change_password')) {
        wp_redirect(home_url('/my-account/?tab=settings&error=security'));
        exit;
    }

    $current_user = wp_get_current_user();
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    if (!wp_check_password($current_password, $current_user->user_pass, $current_user->ID)) {
        wp_redirect(home_url('/my-account/?tab=settings&error=wrong_password'));
        exit;
    }

    // Validate new password
    if (strlen($new_password) < 8) {
        wp_redirect(home_url('/my-account/?tab=settings&error=password_short'));
        exit;
    }

    if ($new_password !== $confirm_password) {
        wp_redirect(home_url('/my-account/?tab=settings&error=password_mismatch'));
        exit;
    }

    // Update password
    wp_set_password($new_password, $current_user->ID);

    // Re-login user
    wp_set_current_user($current_user->ID);
    wp_set_auth_cookie($current_user->ID);

    wp_redirect(home_url('/my-account/?tab=settings&password_updated=true'));
    exit;
}
add_action('admin_post_change_frontend_password', 'humanitarianblog_change_frontend_password');

/**
 * Email Preferences Update Handler
 */
function humanitarianblog_update_email_prefs() {
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_redirect(home_url('/login/'));
        exit;
    }

    // Verify nonce
    if (!isset($_POST['email_prefs_nonce']) || !wp_verify_nonce($_POST['email_prefs_nonce'], 'email_prefs')) {
        wp_redirect(home_url('/my-account/?tab=settings&error=security'));
        exit;
    }

    $current_user = wp_get_current_user();

    $newsletter = isset($_POST['newsletter']) ? '1' : '0';
    $comment_notifications = isset($_POST['comment_notifications']) ? '1' : '0';

    update_user_meta($current_user->ID, 'newsletter_subscribed', $newsletter);
    update_user_meta($current_user->ID, 'comment_notifications', $comment_notifications);

    wp_redirect(home_url('/my-account/?tab=settings&prefs_updated=true'));
    exit;
}
add_action('admin_post_update_email_prefs', 'humanitarianblog_update_email_prefs');

/**
 * Toggle Bookmark AJAX Handler (for logged-in users)
 */
function humanitarianblog_toggle_bookmark() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'bookmark_nonce')) {
        wp_send_json_error('Security check failed.');
    }

    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error('Please log in to bookmark articles.');
    }

    $post_id = intval($_POST['post_id']);

    if (!$post_id || get_post_status($post_id) !== 'publish') {
        wp_send_json_error('Invalid post.');
    }

    $current_user = wp_get_current_user();
    $bookmarks = get_user_meta($current_user->ID, 'bookmarked_posts', true);

    if (!is_array($bookmarks)) {
        $bookmarks = [];
    }

    $is_bookmarked = in_array($post_id, $bookmarks);

    if ($is_bookmarked) {
        // Remove bookmark
        $bookmarks = array_diff($bookmarks, [$post_id]);
        $bookmarks = array_values($bookmarks); // Re-index array
    } else {
        // Add bookmark
        $bookmarks[] = $post_id;
    }

    update_user_meta($current_user->ID, 'bookmarked_posts', $bookmarks);

    wp_send_json_success([
        'bookmarked' => !$is_bookmarked,
        'count' => count($bookmarks),
        'message' => $is_bookmarked ? 'Bookmark removed.' : 'Article bookmarked!',
    ]);
}
add_action('wp_ajax_toggle_bookmark', 'humanitarianblog_toggle_bookmark');