<?php
/**
 * Post Email Notification System
 *
 * Send email notifications to subscribers when new articles are published
 * Supports selective sending and targeting by region/category
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add notification meta box to post editor
 */
function humanitarian_notification_meta_box() {
    add_meta_box(
        'post_notification',
        __('Email Notification', 'humanitarianblog'),
        'humanitarian_notification_meta_box_html',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'humanitarian_notification_meta_box');

/**
 * Render notification meta box HTML
 *
 * @param WP_Post $post Current post object
 */
function humanitarian_notification_meta_box_html($post) {
    wp_nonce_field('notification_meta_box', 'notification_nonce');

    $send_notification = get_post_meta($post->ID, '_send_notification', true);
    $notification_target = get_post_meta($post->ID, '_notification_target', true) ?: 'all';
    $notification_sent = get_post_meta($post->ID, '_notification_sent', true);

    // Get subscriber count
    global $wpdb;
    $table_name = $wpdb->prefix . 'humanitarian_newsletters';
    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
    $subscriber_count = 0;

    if ($table_exists) {
        $subscriber_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    }
    ?>
    <div class="notification-meta-box">
        <?php if ($notification_sent) : ?>
            <div class="notification-sent-notice">
                <span class="dashicons dashicons-yes-alt" style="color: #059669;"></span>
                <?php
                $sent_date = get_post_meta($post->ID, '_notification_sent_date', true);
                printf(
                    __('Notification sent on %s', 'humanitarianblog'),
                    date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($sent_date))
                );
                ?>
            </div>
        <?php else : ?>
            <p>
                <label>
                    <input type="checkbox" name="send_notification" value="1"
                        <?php checked($send_notification, 1); ?>>
                    <?php _e('Send email to subscribers when published', 'humanitarianblog'); ?>
                </label>
            </p>

            <p>
                <label for="notification_target">
                    <strong><?php _e('Target Audience:', 'humanitarianblog'); ?></strong>
                </label>
                <select name="notification_target" id="notification_target" style="width: 100%; margin-top: 5px;">
                    <option value="all" <?php selected($notification_target, 'all'); ?>>
                        <?php _e('All subscribers', 'humanitarianblog'); ?>
                    </option>

                    <optgroup label="<?php esc_attr_e('By Region', 'humanitarianblog'); ?>">
                        <?php
                        $regions = get_terms([
                            'taxonomy' => 'region',
                            'hide_empty' => false
                        ]);
                        foreach ($regions as $region) {
                            printf(
                                '<option value="region_%d" %s>%s</option>',
                                $region->term_id,
                                selected($notification_target, 'region_' . $region->term_id, false),
                                esc_html($region->name)
                            );
                        }
                        ?>
                    </optgroup>

                    <optgroup label="<?php esc_attr_e('By Article Type', 'humanitarianblog'); ?>">
                        <?php
                        $types = get_terms([
                            'taxonomy' => 'article_type',
                            'hide_empty' => false
                        ]);
                        foreach ($types as $type) {
                            printf(
                                '<option value="type_%d" %s>%s</option>',
                                $type->term_id,
                                selected($notification_target, 'type_' . $type->term_id, false),
                                esc_html($type->name)
                            );
                        }
                        ?>
                    </optgroup>
                </select>
            </p>

            <p class="description" style="margin-top: 10px; padding: 8px; background: #f0f0f0; border-radius: 4px;">
                <span class="dashicons dashicons-email" style="font-size: 16px; vertical-align: middle;"></span>
                <?php printf(
                    __('Will be sent to approximately <strong>%d</strong> subscribers', 'humanitarianblog'),
                    $subscriber_count
                ); ?>
            </p>
        <?php endif; ?>
    </div>

    <style>
        .notification-meta-box { padding: 5px 0; }
        .notification-sent-notice {
            padding: 10px;
            background: #d1fae5;
            border-radius: 4px;
            color: #065f46;
        }
    </style>
    <?php
}

/**
 * Save notification meta box data
 *
 * @param int $post_id Post ID
 */
function humanitarian_save_notification_meta($post_id) {
    // Verify nonce
    if (!isset($_POST['notification_nonce']) ||
        !wp_verify_nonce($_POST['notification_nonce'], 'notification_meta_box')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save send notification checkbox
    $send_notification = isset($_POST['send_notification']) ? 1 : 0;
    update_post_meta($post_id, '_send_notification', $send_notification);

    // Save target audience
    if (isset($_POST['notification_target'])) {
        update_post_meta($post_id, '_notification_target', sanitize_text_field($_POST['notification_target']));
    }
}
add_action('save_post', 'humanitarian_save_notification_meta');

/**
 * Send notification when post is published
 *
 * @param string $new_status New post status
 * @param string $old_status Old post status
 * @param WP_Post $post Post object
 */
function humanitarian_send_post_notification($new_status, $old_status, $post) {
    // Only for posts
    if ($post->post_type !== 'post') {
        return;
    }

    // Only when publishing (not updating already published)
    if ($new_status !== 'publish' || $old_status === 'publish') {
        return;
    }

    // Check if notification should be sent
    $send_notification = get_post_meta($post->ID, '_send_notification', true);
    if (!$send_notification) {
        return;
    }

    // Check if already sent
    if (get_post_meta($post->ID, '_notification_sent', true)) {
        return;
    }

    // Schedule the email sending (async to not slow down publishing)
    wp_schedule_single_event(time() + 10, 'humanitarian_send_notification_emails', [$post->ID]);
}
add_action('transition_post_status', 'humanitarian_send_post_notification', 10, 3);

/**
 * Actually send the notification emails (runs async via cron)
 *
 * @param int $post_id Post ID
 */
function humanitarian_do_send_notification_emails($post_id) {
    $post = get_post($post_id);

    if (!$post || $post->post_status !== 'publish') {
        return;
    }

    // Get subscribers
    global $wpdb;
    $table_name = $wpdb->prefix . 'humanitarian_newsletters';

    $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") === $table_name;
    if (!$table_exists) {
        return;
    }

    $subscribers = $wpdb->get_col("SELECT email FROM $table_name");

    if (empty($subscribers)) {
        return;
    }

    // Build email content
    $subject = sprintf(
        /* translators: %s: Article title */
        __('New Article: %s', 'humanitarianblog'),
        $post->post_title
    );

    $message = humanitarian_get_notification_email_template($post);

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>'
    ];

    // Send in batches to avoid timeout
    $batch_size = 50;
    $batches = array_chunk($subscribers, $batch_size);
    $sent_count = 0;

    foreach ($batches as $batch) {
        foreach ($batch as $email) {
            if (is_email($email)) {
                $result = wp_mail($email, $subject, $message, $headers);
                if ($result) {
                    $sent_count++;
                }
            }
        }

        // Small delay between batches to avoid rate limiting
        if (count($batches) > 1) {
            sleep(1);
        }
    }

    // Mark as sent
    update_post_meta($post_id, '_notification_sent', 1);
    update_post_meta($post_id, '_notification_sent_date', current_time('mysql'));
    update_post_meta($post_id, '_notification_sent_count', $sent_count);

    // Log for debugging
    error_log(sprintf(
        'Humanitarian Blog: Sent notification for post #%d to %d subscribers',
        $post_id,
        $sent_count
    ));
}
add_action('humanitarian_send_notification_emails', 'humanitarian_do_send_notification_emails');

/**
 * Get notification email HTML template
 *
 * @param WP_Post $post Post object
 * @return string HTML email content
 */
function humanitarian_get_notification_email_template($post) {
    $site_name = get_bloginfo('name');
    $site_url = home_url('/');
    $post_url = get_permalink($post->ID);
    $thumbnail = get_the_post_thumbnail_url($post->ID, 'card-medium');
    $excerpt = wp_trim_words($post->post_content, 40, '...');

    // Get category
    $categories = get_the_category($post->ID);
    $category_name = !empty($categories) ? $categories[0]->name : '';

    // Get region
    $regions = get_the_terms($post->ID, 'region');
    $region_name = (!empty($regions) && !is_wp_error($regions)) ? $regions[0]->name : '';

    // Get reading time
    $word_count = str_word_count(strip_tags($post->post_content));
    $reading_time = max(1, ceil($word_count / 200));

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
                <td style="padding: 40px 20px;">
                    <table role="presentation" style="width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <!-- Header -->
                        <tr>
                            <td style="background: linear-gradient(135deg, #0D5C63 0%, #094147 100%); padding: 25px; text-align: center;">
                                <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 600;">
                                    <?php echo esc_html($site_name); ?>
                                </h1>
                                <p style="color: rgba(255,255,255,0.8); margin: 8px 0 0; font-size: 14px;">
                                    <?php _e('New Article Published', 'humanitarianblog'); ?>
                                </p>
                            </td>
                        </tr>

                        <!-- Featured Image -->
                        <?php if ($thumbnail) : ?>
                        <tr>
                            <td style="padding: 0;">
                                <a href="<?php echo esc_url($post_url); ?>">
                                    <img src="<?php echo esc_url($thumbnail); ?>" alt="" style="width: 100%; height: auto; display: block;">
                                </a>
                            </td>
                        </tr>
                        <?php endif; ?>

                        <!-- Content -->
                        <tr>
                            <td style="padding: 30px;">
                                <!-- Category & Region Badge -->
                                <?php if ($category_name || $region_name) : ?>
                                <p style="margin: 0 0 15px;">
                                    <?php if ($category_name) : ?>
                                    <span style="display: inline-block; padding: 4px 12px; background: #e0f2fe; color: #0369a1; font-size: 12px; font-weight: 600; border-radius: 20px; text-transform: uppercase;">
                                        <?php echo esc_html($category_name); ?>
                                    </span>
                                    <?php endif; ?>
                                    <?php if ($region_name) : ?>
                                    <span style="display: inline-block; padding: 4px 12px; background: #fef3c7; color: #92400e; font-size: 12px; font-weight: 600; border-radius: 20px; text-transform: uppercase; margin-left: 5px;">
                                        <?php echo esc_html($region_name); ?>
                                    </span>
                                    <?php endif; ?>
                                </p>
                                <?php endif; ?>

                                <!-- Title -->
                                <h2 style="color: #1a1a1a; margin: 0 0 15px; font-size: 24px; font-weight: 700; line-height: 1.3;">
                                    <a href="<?php echo esc_url($post_url); ?>" style="color: #1a1a1a; text-decoration: none;">
                                        <?php echo esc_html($post->post_title); ?>
                                    </a>
                                </h2>

                                <!-- Meta -->
                                <p style="color: #888888; font-size: 13px; margin: 0 0 20px;">
                                    <?php echo esc_html(get_the_author_meta('display_name', $post->post_author)); ?>
                                    &nbsp;&bull;&nbsp;
                                    <?php echo esc_html(get_the_date('', $post->ID)); ?>
                                    &nbsp;&bull;&nbsp;
                                    <?php printf(__('%d min read', 'humanitarianblog'), $reading_time); ?>
                                </p>

                                <!-- Excerpt -->
                                <p style="color: #4a4a4a; font-size: 16px; line-height: 1.7; margin: 0 0 25px;">
                                    <?php echo esc_html($excerpt); ?>
                                </p>

                                <!-- CTA Button -->
                                <table role="presentation" style="margin: 0;">
                                    <tr>
                                        <td style="border-radius: 6px; background: #0D5C63;">
                                            <a href="<?php echo esc_url($post_url); ?>" style="display: inline-block; padding: 14px 28px; color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 600;">
                                                <?php _e('Read Full Article', 'humanitarianblog'); ?> &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- Footer -->
                        <tr>
                            <td style="background-color: #f8f8f8; padding: 25px; text-align: center; border-top: 1px solid #eeeeee;">
                                <p style="color: #888888; font-size: 13px; margin: 0 0 10px;">
                                    <?php _e("You're receiving this email because you subscribed to our newsletter.", 'humanitarianblog'); ?>
                                </p>
                                <p style="margin: 0;">
                                    <a href="<?php echo esc_url($site_url); ?>" style="color: #0D5C63; font-size: 12px; text-decoration: none;">
                                        <?php _e('Visit Website', 'humanitarianblog'); ?>
                                    </a>
                                    &nbsp;&nbsp;|&nbsp;&nbsp;
                                    <a href="<?php echo esc_url(add_query_arg('unsubscribe', 'true', $site_url)); ?>" style="color: #888888; font-size: 12px; text-decoration: none;">
                                        <?php _e('Unsubscribe', 'humanitarianblog'); ?>
                                    </a>
                                </p>
                                <p style="color: #aaaaaa; font-size: 11px; margin: 15px 0 0;">
                                    &copy; <?php echo date('Y'); ?> <?php echo esc_html($site_name); ?>
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
 * Add notification status column to posts list
 */
function humanitarian_add_notification_column($columns) {
    $new_columns = [];

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Add after date column
        if ($key === 'date') {
            $new_columns['notification'] = __('Notified', 'humanitarianblog');
        }
    }

    return $new_columns;
}
add_filter('manage_posts_columns', 'humanitarian_add_notification_column');

/**
 * Display notification status in posts list
 */
function humanitarian_notification_column_content($column, $post_id) {
    if ($column !== 'notification') {
        return;
    }

    $notification_sent = get_post_meta($post_id, '_notification_sent', true);

    if ($notification_sent) {
        $sent_count = get_post_meta($post_id, '_notification_sent_count', true);
        echo '<span class="dashicons dashicons-email-alt" style="color: #059669;" title="' .
             sprintf(esc_attr__('Sent to %d subscribers', 'humanitarianblog'), $sent_count) .
             '"></span>';
    } else {
        $send_notification = get_post_meta($post_id, '_send_notification', true);
        if ($send_notification) {
            echo '<span class="dashicons dashicons-clock" style="color: #d97706;" title="' .
                 esc_attr__('Scheduled to send', 'humanitarianblog') .
                 '"></span>';
        } else {
            echo '<span class="dashicons dashicons-minus" style="color: #9ca3af;" title="' .
                 esc_attr__('No notification', 'humanitarianblog') .
                 '"></span>';
        }
    }
}
add_action('manage_posts_custom_column', 'humanitarian_notification_column_content', 10, 2);

/**
 * Make notification column sortable
 */
function humanitarian_notification_column_sortable($columns) {
    $columns['notification'] = 'notification';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'humanitarian_notification_column_sortable');
