<?php
/**
 * QR Code Generator
 *
 * Uses phpqrcode library (included in WordPress core since 5.6)
 * For offline reading - generates QR codes for article URLs
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate QR Code for post URL
 *
 * @param int $post_id Post ID
 * @param string $size QR code size (small, medium, large)
 * @return string Base64 encoded PNG image or empty string on failure
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
        'small'  => 200,
        'medium' => 300,
        'large'  => 400,
    ];

    $pixel_size = isset($sizes[$size]) ? $sizes[$size] : $sizes['medium'];

    // Check cache first (24 hours)
    $cache_key = 'qr_code_' . md5($post_url . $size);
    $cached_qr = get_transient($cache_key);

    if ($cached_qr !== false) {
        return $cached_qr;
    }

    // Generate QR code using phpqrcode
    try {
        // Include phpqrcode library
        if (!class_exists('QRcode')) {
            require_once ABSPATH . 'wp-includes/ID3/phpqrcode.php';
        }

        // Create temporary file for QR code
        $temp_file = tempnam(sys_get_temp_dir(), 'qr_');

        // Generate QR code
        // Parameters: data, filename, error_correction_level, size, margin
        // L = ~7% error correction (fastest, smallest)
        // M = ~15% error correction (balanced) - DEFAULT
        // Q = ~25% error correction
        // H = ~30% error correction (slowest, largest)
        QRcode::png($post_url, $temp_file, 'M', 10, 2);

        // Read the generated file
        $qr_data = file_get_contents($temp_file);

        // Delete temporary file
        unlink($temp_file);

        if (!$qr_data) {
            return '';
        }

        // Resize image to desired size
        $image = imagecreatefromstring($qr_data);
        if (!$image) {
            return '';
        }

        $original_width = imagesx($image);
        $original_height = imagesy($image);

        // Create new image with desired size
        $new_image = imagecreatetruecolor($pixel_size, $pixel_size);

        // Set white background
        $white = imagecolorallocate($new_image, 255, 255, 255);
        imagefill($new_image, 0, 0, $white);

        // Copy and resize
        imagecopyresampled(
            $new_image,
            $image,
            0, 0, 0, 0,
            $pixel_size,
            $pixel_size,
            $original_width,
            $original_height
        );

        // Convert to base64
        ob_start();
        imagepng($new_image);
        $image_data = ob_get_clean();

        // Free memory
        imagedestroy($image);
        imagedestroy($new_image);

        if (!$image_data) {
            return '';
        }

        $base64_qr = 'data:image/png;base64,' . base64_encode($image_data);

        // Cache for 24 hours
        set_transient($cache_key, $base64_qr, 24 * HOUR_IN_SECONDS);

        return $base64_qr;

    } catch (Exception $e) {
        error_log('QR Code generation error: ' . $e->getMessage());
        return '';
    }
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

    // Generate QR code
    $qr_code = humanitarianblog_generate_qr_code($post_id, $size);

    if (empty($qr_code)) {
        wp_send_json_error('Failed to generate QR code.');
    }

    wp_send_json_success([
        'qr_code' => $qr_code,
        'post_url' => get_permalink($post_id),
        'post_title' => get_the_title($post_id),
    ]);
}
add_action('wp_ajax_generate_qr', 'humanitarianblog_ajax_generate_qr');
add_action('wp_ajax_nopriv_generate_qr', 'humanitarianblog_ajax_generate_qr');
