<?php
/**
 * PDF Generator
 *
 * Provides print-friendly view for PDF export
 * Uses browser's native print-to-PDF functionality
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AJAX Handler: Get print-friendly content
 * Returns HTML optimized for printing/PDF export
 */
function humanitarianblog_ajax_generate_pdf() {
    // Verify nonce
    check_ajax_referer('humanitarian_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);
    $format = sanitize_text_field($_POST['format'] ?? 'standard');

    // Validate format
    $allowed_formats = ['standard', 'light', 'print'];
    if (!in_array($format, $allowed_formats)) {
        $format = 'standard';
    }

    // Rate limiting (10 requests per hour per IP)
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'pdf_rate_' . md5($user_ip);
    $pdf_count = get_transient($rate_limit_key);

    if ($pdf_count && $pdf_count > 10) {
        wp_send_json_error(__('Too many PDF requests. Please try again later.', 'humanitarianblog'));
    }

    // Increment rate limit counter
    set_transient($rate_limit_key, $pdf_count ? $pdf_count + 1 : 1, HOUR_IN_SECONDS);

    // Validate post
    $post = get_post($post_id);
    if (!$post || $post->post_status !== 'publish') {
        wp_send_json_error(__('Invalid post ID or post not published.', 'humanitarianblog'));
    }

    // Success - tell JS to open print dialog
    wp_send_json_success([
        'action' => 'print',
        'message' => __('Opening print dialog...', 'humanitarianblog'),
    ]);
}
add_action('wp_ajax_generate_pdf', 'humanitarianblog_ajax_generate_pdf');
add_action('wp_ajax_nopriv_generate_pdf', 'humanitarianblog_ajax_generate_pdf');
