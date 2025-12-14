<?php
/**
 * PDF Generator
 *
 * Generates PDF versions of articles for offline reading
 * Uses mPDF library (needs to be installed via Composer)
 *
 * Installation: composer require mpdf/mpdf
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if mPDF library is available
 *
 * @return bool True if mPDF is available
 */
function humanitarianblog_is_mpdf_available() {
    // Check if Composer autoload exists
    $autoload_path = HUMANITARIAN_THEME_DIR . '/vendor/autoload.php';

    if (file_exists($autoload_path)) {
        require_once $autoload_path;
        return class_exists('Mpdf\Mpdf');
    }

    // NOTE: mPDF requires Composer installation
    // Run: composer install in theme directory
    // For now, return false (feature disabled until Composer is set up)
    return false;
}

/**
 * Generate PDF for a post
 *
 * @param int $post_id Post ID
 * @param string $format PDF format (standard, light, print)
 * @return array Result with success status and file path or error message
 */
function humanitarianblog_generate_pdf($post_id, $format = 'standard') {
    // Check if mPDF is available
    if (!humanitarianblog_is_mpdf_available()) {
        return [
            'success' => false,
            'message' => 'PDF library not installed. Please run: composer require mpdf/mpdf',
        ];
    }

    // Validate post
    $post = get_post($post_id);
    if (!$post || $post->post_status !== 'publish') {
        return [
            'success' => false,
            'message' => 'Invalid post ID or post not published',
        ];
    }

    // Check cache first (24 hours)
    $cache_key = 'pdf_' . $post_id . '_' . $format;
    $cached_pdf = get_transient($cache_key);

    if ($cached_pdf !== false && file_exists($cached_pdf)) {
        return [
            'success' => true,
            'file_path' => $cached_pdf,
            'file_url' => humanitarianblog_get_pdf_url($cached_pdf),
            'from_cache' => true,
        ];
    }

    try {
        // Create uploads directory for PDFs
        $upload_dir = wp_upload_dir();
        $pdf_dir = $upload_dir['basedir'] . '/pdfs';

        if (!file_exists($pdf_dir)) {
            wp_mkdir_p($pdf_dir);
        }

        // Generate filename
        $filename = sanitize_file_name($post->post_name . '-' . $format . '.pdf');
        $file_path = $pdf_dir . '/' . $filename;

        // Format configuration
        $format_config = humanitarianblog_get_pdf_format_config($format);

        // Initialize mPDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_header' => 10,
            'margin_footer' => 10,
        ]);

        // Set metadata
        $mpdf->SetTitle($post->post_title);
        $mpdf->SetAuthor(get_the_author_meta('display_name', $post->post_author));
        $mpdf->SetCreator('HumanitarianBlog');
        $mpdf->SetSubject('Article: ' . $post->post_title);

        // Generate HTML content
        $html = humanitarianblog_get_pdf_html($post, $format_config);

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output to file
        $mpdf->Output($file_path, \Mpdf\Output\Destination::FILE);

        // Cache file path for 24 hours
        set_transient($cache_key, $file_path, 24 * HOUR_IN_SECONDS);

        return [
            'success' => true,
            'file_path' => $file_path,
            'file_url' => humanitarianblog_get_pdf_url($file_path),
            'from_cache' => false,
        ];

    } catch (Exception $e) {
        error_log('PDF generation error: ' . $e->getMessage());
        return [
            'success' => false,
            'message' => 'PDF generation failed: ' . $e->getMessage(),
        ];
    }
}

/**
 * Get PDF format configuration
 *
 * @param string $format Format name
 * @return array Configuration array
 */
function humanitarianblog_get_pdf_format_config($format) {
    $configs = [
        'standard' => [
            'color' => true,
            'images' => true,
            'styles' => true,
            'header' => true,
            'footer' => true,
        ],
        'light' => [
            'color' => false,
            'images' => false,
            'styles' => true,
            'header' => true,
            'footer' => true,
        ],
        'print' => [
            'color' => false,
            'images' => true,
            'styles' => true,
            'header' => true,
            'footer' => true,
        ],
    ];

    return isset($configs[$format]) ? $configs[$format] : $configs['standard'];
}

/**
 * Generate PDF HTML content
 *
 * @param WP_Post $post Post object
 * @param array $config Format configuration
 * @return string HTML content
 */
function humanitarianblog_get_pdf_html($post, $config) {
    ob_start();

    // Get post metadata
    $author = get_the_author_meta('display_name', $post->post_author);
    $date = get_the_date('F j, Y', $post);
    $categories = get_the_category($post->ID);
    $category_name = !empty($categories) ? $categories[0]->name : '';

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: 'DejaVu Sans', sans-serif;
                font-size: 11pt;
                line-height: 1.6;
                color: <?php echo $config['color'] ? '#333' : '#000'; ?>;
            }
            h1 {
                font-size: 24pt;
                margin-bottom: 10pt;
                color: <?php echo $config['color'] ? '#2c3e50' : '#000'; ?>;
            }
            .meta {
                font-size: 9pt;
                color: <?php echo $config['color'] ? '#7f8c8d' : '#666'; ?>;
                margin-bottom: 20pt;
                border-bottom: 1px solid <?php echo $config['color'] ? '#ecf0f1' : '#ccc'; ?>;
                padding-bottom: 10pt;
            }
            .content {
                text-align: justify;
            }
            .content p {
                margin-bottom: 10pt;
            }
            .content img {
                max-width: 100%;
                height: auto;
                <?php if (!$config['images']) echo 'display: none;'; ?>
            }
            .content blockquote {
                border-left: 3px solid <?php echo $config['color'] ? '#3498db' : '#666'; ?>;
                padding-left: 15pt;
                margin-left: 0;
                font-style: italic;
                color: <?php echo $config['color'] ? '#555' : '#333'; ?>;
            }
            .footer {
                margin-top: 30pt;
                padding-top: 10pt;
                border-top: 1px solid <?php echo $config['color'] ? '#ecf0f1' : '#ccc'; ?>;
                font-size: 9pt;
                color: <?php echo $config['color'] ? '#95a5a6' : '#666'; ?>;
            }
        </style>
    </head>
    <body>
        <h1><?php echo esc_html($post->post_title); ?></h1>

        <div class="meta">
            <strong>Author:</strong> <?php echo esc_html($author); ?><br>
            <strong>Published:</strong> <?php echo esc_html($date); ?><br>
            <?php if ($category_name) : ?>
                <strong>Category:</strong> <?php echo esc_html($category_name); ?>
            <?php endif; ?>
        </div>

        <div class="content">
            <?php
            // Apply content filters (wpautop, shortcodes, etc.)
            $content = apply_filters('the_content', $post->post_content);

            // Remove images if not in config
            if (!$config['images']) {
                $content = preg_replace('/<img[^>]+>/i', '', $content);
            }

            echo $content;
            ?>
        </div>

        <div class="footer">
            <p><strong>Read online:</strong> <?php echo esc_url(get_permalink($post)); ?></p>
            <p>&copy; <?php echo date('Y'); ?> HumanitarianBlog. All rights reserved.</p>
            <p>Generated on <?php echo date('F j, Y \a\t g:i a'); ?></p>
        </div>
    </body>
    </html>
    <?php

    return ob_get_clean();
}

/**
 * Convert file path to URL
 *
 * @param string $file_path File path
 * @return string File URL
 */
function humanitarianblog_get_pdf_url($file_path) {
    $upload_dir = wp_upload_dir();
    return str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $file_path);
}

/**
 * AJAX Handler: Generate PDF
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

    // Rate limiting (5 PDFs per hour per IP)
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'pdf_rate_' . md5($user_ip);
    $pdf_count = get_transient($rate_limit_key);

    if ($pdf_count && $pdf_count > 5) {
        wp_send_json_error('Too many PDF requests. Please try again later.');
    }

    // Increment rate limit counter
    set_transient($rate_limit_key, $pdf_count ? $pdf_count + 1 : 1, HOUR_IN_SECONDS);

    // Generate PDF
    $result = humanitarianblog_generate_pdf($post_id, $format);

    if ($result['success']) {
        wp_send_json_success([
            'file_url' => $result['file_url'],
            'from_cache' => $result['from_cache'] ?? false,
        ]);
    } else {
        wp_send_json_error($result['message']);
    }
}
add_action('wp_ajax_generate_pdf', 'humanitarianblog_ajax_generate_pdf');
add_action('wp_ajax_nopriv_generate_pdf', 'humanitarianblog_ajax_generate_pdf');

/**
 * Cleanup old PDF files (run daily via cron)
 */
function humanitarianblog_cleanup_old_pdfs() {
    $upload_dir = wp_upload_dir();
    $pdf_dir = $upload_dir['basedir'] . '/pdfs';

    if (!file_exists($pdf_dir)) {
        return;
    }

    $files = glob($pdf_dir . '/*.pdf');
    $now = time();

    foreach ($files as $file) {
        // Delete files older than 7 days
        if (is_file($file) && ($now - filemtime($file)) > (7 * DAY_IN_SECONDS)) {
            unlink($file);
        }
    }
}

// Schedule cleanup (run daily)
if (!wp_next_scheduled('humanitarianblog_pdf_cleanup')) {
    wp_schedule_event(time(), 'daily', 'humanitarianblog_pdf_cleanup');
}
add_action('humanitarianblog_pdf_cleanup', 'humanitarianblog_cleanup_old_pdfs');
