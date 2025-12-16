<?php
/**
 * Email Verification System
 *
 * Custom email verification without plugins
 * Works with SiteGround or any SMTP provider
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate verification token for user
 *
 * @param int $user_id User ID
 * @return string Token
 */
function humanitarian_generate_verification_token($user_id) {
    $token = wp_generate_password(32, false);
    $expiry = time() + (24 * 60 * 60); // 24 hours validity

    update_user_meta($user_id, '_email_verification_token', $token);
    update_user_meta($user_id, '_email_verification_expiry', $expiry);
    update_user_meta($user_id, '_email_verified', 0);

    return $token;
}

/**
 * Send verification email to user
 *
 * @param int $user_id User ID
 * @return bool Success status
 */
function humanitarian_send_verification_email($user_id) {
    $user = get_userdata($user_id);

    if (!$user) {
        return false;
    }

    $token = humanitarian_generate_verification_token($user_id);

    // Build verification URL
    $verify_url = add_query_arg([
        'action' => 'verify_email',
        'user_id' => $user_id,
        'token' => $token
    ], home_url('/'));

    // Email subject
    $subject = sprintf(
        /* translators: %s: Site name */
        __('Verify Your Email - %s', 'humanitarianblog'),
        get_bloginfo('name')
    );

    // Email body (HTML)
    $message = humanitarian_get_verification_email_template($user, $verify_url);

    // Headers
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>'
    ];

    return wp_mail($user->user_email, $subject, $message, $headers);
}

/**
 * Get verification email HTML template
 *
 * @param WP_User $user User object
 * @param string $verify_url Verification URL
 * @return string HTML email content
 */
function humanitarian_get_verification_email_template($user, $verify_url) {
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');

    ob_start();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body style="margin: 0; padding: 0; background-color: #f4f4f4; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
        <table role="presentation" style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="padding: 40px 0;">
                    <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <!-- Header -->
                        <tr>
                            <td style="background: linear-gradient(135deg, #0D5C63 0%, #094147 100%); padding: 30px; text-align: center;">
                                <h1 style="color: #ffffff; margin: 0; font-size: 24px; font-weight: 600;">
                                    <?php echo esc_html($site_name); ?>
                                </h1>
                            </td>
                        </tr>

                        <!-- Content -->
                        <tr>
                            <td style="padding: 40px 30px;">
                                <h2 style="color: #1a1a1a; margin: 0 0 20px; font-size: 22px; font-weight: 600;">
                                    <?php _e('Verify Your Email Address', 'humanitarianblog'); ?>
                                </h2>

                                <p style="color: #4a4a4a; font-size: 16px; line-height: 1.6; margin: 0 0 20px;">
                                    <?php printf(
                                        /* translators: %s: User display name */
                                        __('Hello %s,', 'humanitarianblog'),
                                        esc_html($user->display_name)
                                    ); ?>
                                </p>

                                <p style="color: #4a4a4a; font-size: 16px; line-height: 1.6; margin: 0 0 30px;">
                                    <?php _e('Thank you for registering! Please click the button below to verify your email address and activate your account.', 'humanitarianblog'); ?>
                                </p>

                                <!-- CTA Button -->
                                <table role="presentation" style="margin: 0 auto;">
                                    <tr>
                                        <td style="border-radius: 6px; background: #0D5C63;">
                                            <a href="<?php echo esc_url($verify_url); ?>" style="display: inline-block; padding: 16px 32px; color: #ffffff; text-decoration: none; font-size: 16px; font-weight: 600;">
                                                <?php _e('Verify Email Address', 'humanitarianblog'); ?>
                                            </a>
                                        </td>
                                    </tr>
                                </table>

                                <p style="color: #888888; font-size: 14px; line-height: 1.6; margin: 30px 0 0;">
                                    <?php _e('This link will expire in 24 hours.', 'humanitarianblog'); ?>
                                </p>

                                <p style="color: #888888; font-size: 14px; line-height: 1.6; margin: 20px 0 0;">
                                    <?php _e('If you did not create an account, please ignore this email.', 'humanitarianblog'); ?>
                                </p>

                                <!-- Fallback URL -->
                                <p style="color: #888888; font-size: 12px; line-height: 1.6; margin: 30px 0 0; padding-top: 20px; border-top: 1px solid #eeeeee;">
                                    <?php _e('If the button above doesn\'t work, copy and paste this link into your browser:', 'humanitarianblog'); ?><br>
                                    <a href="<?php echo esc_url($verify_url); ?>" style="color: #0D5C63; word-break: break-all;">
                                        <?php echo esc_url($verify_url); ?>
                                    </a>
                                </p>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="background-color: #f8f8f8; padding: 20px 30px; text-align: center; border-top: 1px solid #eeeeee;">
                                <p style="color: #888888; font-size: 12px; margin: 0;">
                                    &copy; <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>. <?php _e('All rights reserved.', 'humanitarianblog'); ?>
                                </p>
                                <p style="margin: 10px 0 0;">
                                    <a href="<?php echo esc_url($site_url); ?>" style="color: #0D5C63; font-size: 12px; text-decoration: none;">
                                        <?php echo esc_url($site_url); ?>
                                    </a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

/**
 * Handle email verification from URL
 */
function humanitarian_handle_email_verification() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'verify_email') {
        return;
    }

    $user_id = isset($_GET['user_id']) ? absint($_GET['user_id']) : 0;
    $token = isset($_GET['token']) ? sanitize_text_field($_GET['token']) : '';

    // Validate inputs
    if (!$user_id || !$token) {
        wp_redirect(home_url('/login/?verify=invalid'));
        exit;
    }

    // Get stored token and expiry
    $stored_token = get_user_meta($user_id, '_email_verification_token', true);
    $expiry = get_user_meta($user_id, '_email_verification_expiry', true);

    // Check if already verified
    $is_verified = get_user_meta($user_id, '_email_verified', true);
    if ($is_verified == 1) {
        wp_redirect(home_url('/login/?verify=already'));
        exit;
    }

    // Validate token
    if (!$stored_token || $token !== $stored_token) {
        wp_redirect(home_url('/login/?verify=invalid'));
        exit;
    }

    // Check expiry
    if (!$expiry || time() > $expiry) {
        wp_redirect(home_url('/login/?verify=expired'));
        exit;
    }

    // Verification successful
    update_user_meta($user_id, '_email_verified', 1);
    delete_user_meta($user_id, '_email_verification_token');
    delete_user_meta($user_id, '_email_verification_expiry');

    // Log the user in automatically
    $user = get_userdata($user_id);
    if ($user) {
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);
    }

    wp_redirect(home_url('/my-account/?verified=success'));
    exit;
}
add_action('template_redirect', 'humanitarian_handle_email_verification');

/**
 * Check if user email is verified during login
 *
 * @param WP_User|WP_Error $user User object or error
 * @param string $password Password
 * @return WP_User|WP_Error
 */
function humanitarian_check_email_verified_on_login($user, $password) {
    if (is_wp_error($user)) {
        return $user;
    }

    // Skip verification check for admins and editors
    if (in_array('administrator', $user->roles) || in_array('editor', $user->roles)) {
        return $user;
    }

    // Check if email verification is required
    $is_verified = get_user_meta($user->ID, '_email_verified', true);

    // If _email_verified meta doesn't exist, user registered before this feature
    // Consider them verified (backward compatibility)
    if ($is_verified === '') {
        update_user_meta($user->ID, '_email_verified', 1);
        return $user;
    }

    if ($is_verified != 1) {
        return new WP_Error(
            'email_not_verified',
            sprintf(
                /* translators: %s: Resend verification link */
                __('Please verify your email address before logging in. <a href="%s">Resend verification email</a>', 'humanitarianblog'),
                esc_url(add_query_arg([
                    'action' => 'resend_verification',
                    'user_id' => $user->ID,
                    'nonce' => wp_create_nonce('resend_verification_' . $user->ID)
                ], home_url('/')))
            )
        );
    }

    return $user;
}
add_filter('wp_authenticate_user', 'humanitarian_check_email_verified_on_login', 10, 2);

/**
 * Handle resend verification email request
 */
function humanitarian_handle_resend_verification() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'resend_verification') {
        return;
    }

    $user_id = isset($_GET['user_id']) ? absint($_GET['user_id']) : 0;
    $nonce = isset($_GET['nonce']) ? sanitize_text_field($_GET['nonce']) : '';

    // Validate
    if (!$user_id || !wp_verify_nonce($nonce, 'resend_verification_' . $user_id)) {
        wp_redirect(home_url('/login/?verify=error'));
        exit;
    }

    // Rate limiting - max 3 resends per hour
    $resend_count_key = '_verification_resend_count';
    $resend_time_key = '_verification_resend_time';

    $resend_count = get_user_meta($user_id, $resend_count_key, true) ?: 0;
    $resend_time = get_user_meta($user_id, $resend_time_key, true) ?: 0;

    // Reset counter if more than 1 hour has passed
    if (time() - $resend_time > 3600) {
        $resend_count = 0;
    }

    if ($resend_count >= 3) {
        wp_redirect(home_url('/login/?verify=limit'));
        exit;
    }

    // Send verification email
    $sent = humanitarian_send_verification_email($user_id);

    // Update rate limit counters
    update_user_meta($user_id, $resend_count_key, $resend_count + 1);
    update_user_meta($user_id, $resend_time_key, time());

    if ($sent) {
        wp_redirect(home_url('/login/?verify=resent'));
    } else {
        wp_redirect(home_url('/login/?verify=error'));
    }
    exit;
}
add_action('template_redirect', 'humanitarian_handle_resend_verification');

/**
 * Send verification email after user registration
 *
 * @param int $user_id User ID
 */
function humanitarian_send_verification_on_registration($user_id) {
    // Check if user has subscriber or author role (not admin/editor)
    $user = get_userdata($user_id);

    if (!$user) {
        return;
    }

    // Only require verification for subscribers and authors
    if (in_array('administrator', $user->roles) || in_array('editor', $user->roles)) {
        update_user_meta($user_id, '_email_verified', 1);
        return;
    }

    humanitarian_send_verification_email($user_id);
}
add_action('user_register', 'humanitarian_send_verification_on_registration', 10, 1);

/**
 * Display verification status messages on login page
 */
function humanitarian_verification_messages() {
    if (!isset($_GET['verify'])) {
        return;
    }

    $messages = [
        'success' => [
            'type' => 'success',
            'message' => __('Your email has been verified successfully! You can now log in.', 'humanitarianblog')
        ],
        'already' => [
            'type' => 'info',
            'message' => __('Your email has already been verified. Please log in.', 'humanitarianblog')
        ],
        'invalid' => [
            'type' => 'error',
            'message' => __('Invalid verification link. Please try again or request a new verification email.', 'humanitarianblog')
        ],
        'expired' => [
            'type' => 'error',
            'message' => __('This verification link has expired. Please request a new verification email.', 'humanitarianblog')
        ],
        'resent' => [
            'type' => 'success',
            'message' => __('A new verification email has been sent. Please check your inbox.', 'humanitarianblog')
        ],
        'limit' => [
            'type' => 'error',
            'message' => __('Too many verification email requests. Please try again in an hour.', 'humanitarianblog')
        ],
        'error' => [
            'type' => 'error',
            'message' => __('An error occurred. Please try again later.', 'humanitarianblog')
        ]
    ];

    $status = sanitize_key($_GET['verify']);

    if (isset($messages[$status])) {
        return $messages[$status];
    }

    return null;
}

/**
 * Check if user's email is verified
 *
 * @param int $user_id User ID
 * @return bool
 */
function humanitarian_is_email_verified($user_id) {
    $is_verified = get_user_meta($user_id, '_email_verified', true);
    return $is_verified == 1;
}

/**
 * Add verification status to user profile in admin
 *
 * @param WP_User $user User object
 */
function humanitarian_show_verification_status($user) {
    $is_verified = get_user_meta($user->ID, '_email_verified', true);
    ?>
    <h3><?php _e('Email Verification Status', 'humanitarianblog'); ?></h3>
    <table class="form-table">
        <tr>
            <th><?php _e('Status', 'humanitarianblog'); ?></th>
            <td>
                <?php if ($is_verified == 1): ?>
                    <span style="color: #059669; font-weight: 600;">
                        <span class="dashicons dashicons-yes-alt" style="color: #059669;"></span>
                        <?php _e('Verified', 'humanitarianblog'); ?>
                    </span>
                <?php else: ?>
                    <span style="color: #dc2626; font-weight: 600;">
                        <span class="dashicons dashicons-warning" style="color: #dc2626;"></span>
                        <?php _e('Not Verified', 'humanitarianblog'); ?>
                    </span>
                    <p class="description">
                        <a href="<?php echo esc_url(add_query_arg([
                            'action' => 'admin_resend_verification',
                            'user_id' => $user->ID,
                            'nonce' => wp_create_nonce('admin_resend_' . $user->ID)
                        ], admin_url('users.php'))); ?>" class="button button-secondary">
                            <?php _e('Resend Verification Email', 'humanitarianblog'); ?>
                        </a>
                        <a href="<?php echo esc_url(add_query_arg([
                            'action' => 'admin_verify_user',
                            'user_id' => $user->ID,
                            'nonce' => wp_create_nonce('admin_verify_' . $user->ID)
                        ], admin_url('users.php'))); ?>" class="button button-primary" style="margin-left: 8px;">
                            <?php _e('Manually Verify', 'humanitarianblog'); ?>
                        </a>
                    </p>
                <?php endif; ?>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'humanitarian_show_verification_status');
add_action('edit_user_profile', 'humanitarian_show_verification_status');

/**
 * Handle admin actions for verification
 */
function humanitarian_admin_verification_actions() {
    if (!current_user_can('edit_users')) {
        return;
    }

    // Resend verification from admin
    if (isset($_GET['action']) && $_GET['action'] === 'admin_resend_verification') {
        $user_id = absint($_GET['user_id'] ?? 0);
        $nonce = sanitize_text_field($_GET['nonce'] ?? '');

        if ($user_id && wp_verify_nonce($nonce, 'admin_resend_' . $user_id)) {
            humanitarian_send_verification_email($user_id);
            wp_redirect(add_query_arg('verification_sent', '1', admin_url('users.php')));
            exit;
        }
    }

    // Manually verify from admin
    if (isset($_GET['action']) && $_GET['action'] === 'admin_verify_user') {
        $user_id = absint($_GET['user_id'] ?? 0);
        $nonce = sanitize_text_field($_GET['nonce'] ?? '');

        if ($user_id && wp_verify_nonce($nonce, 'admin_verify_' . $user_id)) {
            update_user_meta($user_id, '_email_verified', 1);
            delete_user_meta($user_id, '_email_verification_token');
            delete_user_meta($user_id, '_email_verification_expiry');
            wp_redirect(add_query_arg('user_verified', '1', admin_url('users.php')));
            exit;
        }
    }
}
add_action('admin_init', 'humanitarian_admin_verification_actions');

/**
 * Show admin notices for verification actions
 */
function humanitarian_admin_verification_notices() {
    if (isset($_GET['verification_sent'])) {
        echo '<div class="notice notice-success is-dismissible"><p>' .
             __('Verification email has been sent.', 'humanitarianblog') .
             '</p></div>';
    }

    if (isset($_GET['user_verified'])) {
        echo '<div class="notice notice-success is-dismissible"><p>' .
             __('User has been manually verified.', 'humanitarianblog') .
             '</p></div>';
    }
}
add_action('admin_notices', 'humanitarian_admin_verification_notices');

/**
 * Add verification status column to users list
 */
function humanitarian_add_verification_column($columns) {
    $columns['email_verified'] = __('Verified', 'humanitarianblog');
    return $columns;
}
add_filter('manage_users_columns', 'humanitarian_add_verification_column');

/**
 * Display verification status in users list
 */
function humanitarian_show_verification_column($value, $column_name, $user_id) {
    if ($column_name === 'email_verified') {
        $is_verified = get_user_meta($user_id, '_email_verified', true);

        if ($is_verified == 1) {
            return '<span class="dashicons dashicons-yes-alt" style="color: #059669;" title="' .
                   esc_attr__('Verified', 'humanitarianblog') . '"></span>';
        } else {
            return '<span class="dashicons dashicons-warning" style="color: #dc2626;" title="' .
                   esc_attr__('Not Verified', 'humanitarianblog') . '"></span>';
        }
    }

    return $value;
}
add_filter('manage_users_custom_column', 'humanitarian_show_verification_column', 10, 3);
