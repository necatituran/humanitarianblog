<?php
/**
 * QR Code Generator
 *
 * Uses QRServer.com API for reliable QR code generation
 * No external PHP libraries required
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate QR Code URL for post
 *
 * Uses QRServer.com free API - reliable and fast
 *
 * @param int $post_id Post ID
 * @param string $size QR code size (small, medium, large)
 * @return string QR code image URL or empty string on failure
 */
function humanitarianblog_generate_qr_code($post_id, $size = 'medium') {
    // Validate post ID
    if (!$post_id || get_post_status($post_id) !== 'publish') {
        return '';
    }

    // Get post URL
    $post_url = get_permalink($post_id);
    if (!$post_url) {
        return '';
    }

    // Size mapping (pixels)
    $sizes = [
        'small'  => 150,
        'medium' => 200,
        'large'  => 300,
    ];

    $pixel_size = isset($sizes[$size]) ? $sizes[$size] : $sizes['medium'];

    // Generate QR code URL using QRServer.com API
    // This is a free, reliable API that doesn't require any keys
    $qr_url = add_query_arg([
        'size' => $pixel_size . 'x' . $pixel_size,
        'data' => urlencode($post_url),
        'format' => 'png',
        'margin' => 10,
        'ecc' => 'M', // Error correction level: L, M, Q, H
    ], 'https://api.qrserver.com/v1/create-qr-code/');

    return $qr_url;
}

/**
 * AJAX Handler: Generate QR Code
 */
function humanitarianblog_ajax_generate_qr() {
    // Verify nonce
    check_ajax_referer('humanitarian_nonce', 'nonce');

    $post_id = intval($_POST['post_id']);
    $size = sanitize_text_field($_POST['size'] ?? 'medium');

    // Validate size
    $allowed_sizes = ['small', 'medium', 'large'];
    if (!in_array($size, $allowed_sizes)) {
        $size = 'medium';
    }

    // Rate limiting (20 QR codes per minute per IP)
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'qr_rate_' . md5($user_ip);
    $qr_count = get_transient($rate_limit_key);

    if ($qr_count && $qr_count > 20) {
        wp_send_json_error('Too many requests. Please wait a moment.');
    }

    // Increment rate limit counter
    set_transient($rate_limit_key, $qr_count ? $qr_count + 1 : 1, 60);

    // Validate post
    $post = get_post($post_id);
    if (!$post || $post->post_status !== 'publish') {
        wp_send_json_error('Invalid post.');
    }

    // Generate QR code URL
    $qr_url = humanitarianblog_generate_qr_code($post_id, $size);

    if (empty($qr_url)) {
        wp_send_json_error('Failed to generate QR code.');
    }

    wp_send_json_success([
        'qr_code' => $qr_url,
        'post_url' => get_permalink($post_id),
        'post_title' => get_the_title($post_id),
    ]);
}
add_action('wp_ajax_generate_qr', 'humanitarianblog_ajax_generate_qr');
add_action('wp_ajax_nopriv_generate_qr', 'humanitarianblog_ajax_generate_qr');
