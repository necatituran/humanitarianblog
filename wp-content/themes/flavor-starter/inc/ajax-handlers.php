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

    if (strlen($query) < 3) {
        wp_send_json_error('Query too short');
    }

    // Search query
    $search_query = new WP_Query(array(
        's'              => $query,
        'posts_per_page' => 5,
        'post_status'    => 'publish',
    ));

    $results = array();

    if ($search_query->have_posts()) {
        while ($search_query->have_posts()) {
            $search_query->the_post();

            $categories = get_the_category();
            $category_name = !empty($categories) ? $categories[0]->name : '';

            $results[] = array(
                'title'     => get_the_title(),
                'url'       => get_permalink(),
                'excerpt'   => wp_trim_words(get_the_excerpt(), 20),
                'category'  => $category_name,
                'thumbnail' => get_the_post_thumbnail_url(get_the_ID(), 'card-small'),
            );
        }
        wp_reset_postdata();
    }

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

    // Save to database or send to email service
    // This is a placeholder - integrate with your email service (Mailchimp, etc.)

    $newsletter_data = array(
        'email'     => $email,
        'frequency' => $frequency,
        'date'      => current_time('mysql'),
    );

    // Example: Save to options (not recommended for production)
    $newsletters = get_option('humanitarian_newsletters', array());
    $newsletters[] = $newsletter_data;
    update_option('humanitarian_newsletters', $newsletters);

    wp_send_json_success('Successfully subscribed!');
}
add_action('wp_ajax_newsletter_signup', 'humanitarianblog_newsletter_signup');
add_action('wp_ajax_nopriv_newsletter_signup', 'humanitarianblog_newsletter_signup');