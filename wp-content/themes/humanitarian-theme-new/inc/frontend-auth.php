<?php
/**
 * Frontend Authentication & Comments System
 * GÖREV 13: Comment + Login Sistemi
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Frontend Login Form Shortcode
 * Usage: [humanitarian_login]
 */
function humanitarian_login_form_shortcode($atts) {
    // If already logged in, show user info
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        ob_start();
        ?>
        <div class="frontend-auth frontend-auth--logged-in">
            <div class="frontend-auth__user">
                <?php echo get_avatar($current_user->ID, 48); ?>
                <div class="frontend-auth__user-info">
                    <span class="frontend-auth__welcome"><?php esc_html_e('Welcome,', 'humanitarian'); ?></span>
                    <strong class="frontend-auth__name"><?php echo esc_html($current_user->display_name); ?></strong>
                </div>
            </div>
            <div class="frontend-auth__actions">
                <a href="<?php echo esc_url(admin_url('profile.php')); ?>" class="frontend-auth__btn frontend-auth__btn--secondary">
                    <?php esc_html_e('My Profile', 'humanitarian'); ?>
                </a>
                <a href="<?php echo esc_url(wp_logout_url(get_permalink())); ?>" class="frontend-auth__btn frontend-auth__btn--outline">
                    <?php esc_html_e('Logout', 'humanitarian'); ?>
                </a>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    // Show login form
    ob_start();
    ?>
    <div class="frontend-auth">
        <div class="frontend-auth__tabs">
            <button type="button" class="frontend-auth__tab frontend-auth__tab--active" data-tab="login">
                <?php esc_html_e('Login', 'humanitarian'); ?>
            </button>
            <button type="button" class="frontend-auth__tab" data-tab="register">
                <?php esc_html_e('Register', 'humanitarian'); ?>
            </button>
        </div>

        <!-- Login Form -->
        <form class="frontend-auth__form frontend-auth__form--login" id="frontendLoginForm" method="post">
            <?php wp_nonce_field('humanitarian_frontend_login', 'login_nonce'); ?>

            <div class="frontend-auth__field">
                <label for="login_email"><?php esc_html_e('Email', 'humanitarian'); ?></label>
                <input type="email" id="login_email" name="login_email" required>
            </div>

            <div class="frontend-auth__field">
                <label for="login_password"><?php esc_html_e('Password', 'humanitarian'); ?></label>
                <input type="password" id="login_password" name="login_password" required>
            </div>

            <div class="frontend-auth__remember">
                <label>
                    <input type="checkbox" name="remember" value="1">
                    <?php esc_html_e('Remember me', 'humanitarian'); ?>
                </label>
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="frontend-auth__forgot">
                    <?php esc_html_e('Forgot password?', 'humanitarian'); ?>
                </a>
            </div>

            <div class="frontend-auth__message" id="loginMessage"></div>

            <button type="submit" class="frontend-auth__btn frontend-auth__btn--primary">
                <?php esc_html_e('Login', 'humanitarian'); ?>
            </button>
        </form>

        <!-- Register Form -->
        <form class="frontend-auth__form frontend-auth__form--register" id="frontendRegisterForm" method="post" style="display: none;">
            <?php wp_nonce_field('humanitarian_frontend_register', 'register_nonce'); ?>

            <div class="frontend-auth__field">
                <label for="register_name"><?php esc_html_e('Full Name', 'humanitarian'); ?></label>
                <input type="text" id="register_name" name="register_name" required>
            </div>

            <div class="frontend-auth__field">
                <label for="register_email"><?php esc_html_e('Email', 'humanitarian'); ?></label>
                <input type="email" id="register_email" name="register_email" required>
            </div>

            <div class="frontend-auth__field">
                <label for="register_password"><?php esc_html_e('Password', 'humanitarian'); ?></label>
                <input type="password" id="register_password" name="register_password" required minlength="8">
            </div>

            <div class="frontend-auth__message" id="registerMessage"></div>

            <button type="submit" class="frontend-auth__btn frontend-auth__btn--primary">
                <?php esc_html_e('Create Account', 'humanitarian'); ?>
            </button>
        </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tab switching
        const tabs = document.querySelectorAll('.frontend-auth__tab');
        const loginForm = document.querySelector('.frontend-auth__form--login');
        const registerForm = document.querySelector('.frontend-auth__form--register');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                tabs.forEach(t => t.classList.remove('frontend-auth__tab--active'));
                this.classList.add('frontend-auth__tab--active');

                if (this.dataset.tab === 'login') {
                    loginForm.style.display = 'block';
                    registerForm.style.display = 'none';
                } else {
                    loginForm.style.display = 'none';
                    registerForm.style.display = 'block';
                }
            });
        });

        // Login form submission
        document.getElementById('frontendLoginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'humanitarian_frontend_login');

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const msg = document.getElementById('loginMessage');
                if (data.success) {
                    msg.innerHTML = '<span class="success">' + data.data.message + '</span>';
                    setTimeout(() => location.reload(), 1000);
                } else {
                    msg.innerHTML = '<span class="error">' + data.data.message + '</span>';
                }
            });
        });

        // Register form submission
        document.getElementById('frontendRegisterForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'humanitarian_frontend_register');

            fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const msg = document.getElementById('registerMessage');
                if (data.success) {
                    msg.innerHTML = '<span class="success">' + data.data.message + '</span>';
                    setTimeout(() => location.reload(), 1500);
                } else {
                    msg.innerHTML = '<span class="error">' + data.data.message + '</span>';
                }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('humanitarian_login', 'humanitarian_login_form_shortcode');

/**
 * AJAX Login Handler
 */
function humanitarian_ajax_login() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['login_nonce'], 'humanitarian_frontend_login')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'humanitarian')));
    }

    $email = sanitize_email($_POST['login_email']);
    $password = $_POST['login_password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Get user by email
    $user = get_user_by('email', $email);

    if (!$user) {
        wp_send_json_error(array('message' => __('Invalid email or password.', 'humanitarian')));
    }

    // Authenticate
    $creds = array(
        'user_login'    => $user->user_login,
        'user_password' => $password,
        'remember'      => $remember,
    );

    $user = wp_signon($creds, is_ssl());

    if (is_wp_error($user)) {
        wp_send_json_error(array('message' => __('Invalid email or password.', 'humanitarian')));
    }

    wp_send_json_success(array('message' => __('Login successful! Redirecting...', 'humanitarian')));
}
add_action('wp_ajax_nopriv_humanitarian_frontend_login', 'humanitarian_ajax_login');

/**
 * AJAX Register Handler
 */
function humanitarian_ajax_register() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['register_nonce'], 'humanitarian_frontend_register')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'humanitarian')));
    }

    $name = sanitize_text_field($_POST['register_name']);
    $email = sanitize_email($_POST['register_email']);
    $password = $_POST['register_password'];

    // Validation
    if (empty($name) || empty($email) || empty($password)) {
        wp_send_json_error(array('message' => __('All fields are required.', 'humanitarian')));
    }

    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('Please enter a valid email address.', 'humanitarian')));
    }

    if (email_exists($email)) {
        wp_send_json_error(array('message' => __('This email is already registered.', 'humanitarian')));
    }

    if (strlen($password) < 8) {
        wp_send_json_error(array('message' => __('Password must be at least 8 characters.', 'humanitarian')));
    }

    // Create user
    $user_id = wp_create_user($email, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(array('message' => $user_id->get_error_message()));
    }

    // Update display name
    wp_update_user(array(
        'ID'           => $user_id,
        'display_name' => $name,
        'first_name'   => explode(' ', $name)[0],
    ));

    // Set role to subscriber
    $user = new WP_User($user_id);
    $user->set_role('subscriber');

    // Auto login
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);

    wp_send_json_success(array('message' => __('Account created! Redirecting...', 'humanitarian')));
}
add_action('wp_ajax_nopriv_humanitarian_frontend_register', 'humanitarian_ajax_register');

/**
 * Enable comments on posts by default
 */
function humanitarian_enable_comments($data) {
    if ($data['post_type'] === 'post') {
        $data['comment_status'] = 'open';
    }
    return $data;
}
add_filter('wp_insert_post_data', 'humanitarian_enable_comments', 10, 1);

/**
 * Customize comment form for logged-in users
 */
function humanitarian_comment_form_defaults($defaults) {
    $defaults['title_reply'] = __('Leave a Comment', 'humanitarian');
    $defaults['title_reply_to'] = __('Reply to %s', 'humanitarian');
    $defaults['cancel_reply_link'] = __('Cancel', 'humanitarian');
    $defaults['label_submit'] = __('Post Comment', 'humanitarian');

    if (!is_user_logged_in()) {
        $defaults['must_log_in'] = sprintf(
            '<p class="must-log-in">%s <a href="%s">%s</a> %s</p>',
            __('You must be', 'humanitarian'),
            wp_login_url(get_permalink()),
            __('logged in', 'humanitarian'),
            __('to post a comment.', 'humanitarian')
        );
    }

    return $defaults;
}
add_filter('comment_form_defaults', 'humanitarian_comment_form_defaults');

/**
 * Register Submissions CPT for contact forms and messages
 */
function humanitarian_register_submissions_cpt() {
    $labels = array(
        'name'               => __('Submissions', 'humanitarian'),
        'singular_name'      => __('Submission', 'humanitarian'),
        'menu_name'          => __('Submissions', 'humanitarian'),
        'all_items'          => __('All Submissions', 'humanitarian'),
        'view_item'          => __('View Submission', 'humanitarian'),
        'search_items'       => __('Search Submissions', 'humanitarian'),
        'not_found'          => __('No submissions found', 'humanitarian'),
        'not_found_in_trash' => __('No submissions found in Trash', 'humanitarian'),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'capability_type'    => 'post',
        'hierarchical'       => false,
        'supports'           => array('title', 'editor', 'custom-fields'),
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-email-alt',
    );

    register_post_type('submission', $args);
}
add_action('init', 'humanitarian_register_submissions_cpt');

/**
 * Add submission type taxonomy
 */
function humanitarian_submission_type_taxonomy() {
    register_taxonomy('submission_type', 'submission', array(
        'labels' => array(
            'name'          => __('Submission Types', 'humanitarian'),
            'singular_name' => __('Submission Type', 'humanitarian'),
        ),
        'hierarchical' => true,
        'show_admin_column' => true,
    ));

    // Add default terms
    if (!term_exists('contact', 'submission_type')) {
        wp_insert_term(__('Contact Form', 'humanitarian'), 'submission_type', array('slug' => 'contact'));
    }
    if (!term_exists('message', 'submission_type')) {
        wp_insert_term(__('User Message', 'humanitarian'), 'submission_type', array('slug' => 'message'));
    }
}
add_action('init', 'humanitarian_submission_type_taxonomy');

/**
 * Message Form Shortcode (for logged-in users)
 * Usage: [humanitarian_message_form]
 */
function humanitarian_message_form_shortcode() {
    if (!is_user_logged_in()) {
        return '<p class="message-form-login-required">' .
            sprintf(
                __('Please <a href="%s">login</a> to send a message.', 'humanitarian'),
                wp_login_url(get_permalink())
            ) . '</p>';
    }

    ob_start();
    ?>
    <form class="message-form" id="userMessageForm" method="post">
        <?php wp_nonce_field('humanitarian_message', 'message_nonce'); ?>

        <div class="message-form__field">
            <label for="message_subject"><?php esc_html_e('Subject', 'humanitarian'); ?></label>
            <input type="text" id="message_subject" name="message_subject" required>
        </div>

        <div class="message-form__field">
            <label for="message_content"><?php esc_html_e('Message', 'humanitarian'); ?></label>
            <textarea id="message_content" name="message_content" rows="5" required></textarea>
        </div>

        <div class="message-form__response" id="messageResponse"></div>

        <button type="submit" class="message-form__submit">
            <?php esc_html_e('Send Message', 'humanitarian'); ?>
        </button>
    </form>

    <script>
    document.getElementById('userMessageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        formData.append('action', 'humanitarian_send_message');

        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const msg = document.getElementById('messageResponse');
            if (data.success) {
                msg.innerHTML = '<span class="success">' + data.data.message + '</span>';
                document.getElementById('userMessageForm').reset();
            } else {
                msg.innerHTML = '<span class="error">' + data.data.message + '</span>';
            }
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('humanitarian_message_form', 'humanitarian_message_form_shortcode');

/**
 * AJAX Message Handler
 */
function humanitarian_ajax_send_message() {
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('You must be logged in.', 'humanitarian')));
    }

    if (!wp_verify_nonce($_POST['message_nonce'], 'humanitarian_message')) {
        wp_send_json_error(array('message' => __('Security check failed.', 'humanitarian')));
    }

    $current_user = wp_get_current_user();
    $subject = sanitize_text_field($_POST['message_subject']);
    $content = sanitize_textarea_field($_POST['message_content']);

    if (empty($subject) || empty($content)) {
        wp_send_json_error(array('message' => __('Please fill in all fields.', 'humanitarian')));
    }

    // Create submission
    $post_id = wp_insert_post(array(
        'post_type'    => 'submission',
        'post_title'   => $subject,
        'post_content' => $content,
        'post_status'  => 'publish',
        'meta_input'   => array(
            '_submission_email' => $current_user->user_email,
            '_submission_name'  => $current_user->display_name,
            '_submission_user_id' => $current_user->ID,
        ),
    ));

    if ($post_id) {
        wp_set_object_terms($post_id, 'message', 'submission_type');
        wp_send_json_success(array('message' => __('Message sent successfully!', 'humanitarian')));
    } else {
        wp_send_json_error(array('message' => __('Failed to send message.', 'humanitarian')));
    }
}
add_action('wp_ajax_humanitarian_send_message', 'humanitarian_ajax_send_message');

/**
 * Add custom columns to Submissions list
 */
function humanitarian_submissions_columns($columns) {
    $new_columns = array(
        'cb'       => $columns['cb'],
        'title'    => $columns['title'],
        'sender'   => __('Sender', 'humanitarian'),
        'email'    => __('Email', 'humanitarian'),
        'type'     => __('Type', 'humanitarian'),
        'date'     => $columns['date'],
    );
    return $new_columns;
}
add_filter('manage_submission_posts_columns', 'humanitarian_submissions_columns');

/**
 * Display custom column content
 */
function humanitarian_submissions_column_content($column, $post_id) {
    switch ($column) {
        case 'sender':
            echo esc_html(get_post_meta($post_id, '_submission_name', true));
            break;
        case 'email':
            $email = get_post_meta($post_id, '_submission_email', true);
            echo '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
            break;
        case 'type':
            $terms = get_the_terms($post_id, 'submission_type');
            if ($terms && !is_wp_error($terms)) {
                echo esc_html($terms[0]->name);
            }
            break;
    }
}
add_action('manage_submission_posts_custom_column', 'humanitarian_submissions_column_content', 10, 2);

/**
 * Contact Form Handler - GÖREV 14
 * Saves contact form submissions to Submissions CPT
 */
function humanitarian_handle_contact_form() {
    // Verify nonce
    if (!isset($_POST['contact_nonce']) || !wp_verify_nonce($_POST['contact_nonce'], 'humanitarian_contact')) {
        wp_die(__('Security check failed.', 'humanitarian'));
    }

    // Sanitize inputs
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $message = sanitize_textarea_field($_POST['contact_message']);

    // Validation
    if (empty($name) || empty($email) || empty($message)) {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    // Create title
    $title = !empty($subject) ? $subject : sprintf(__('Contact from %s', 'humanitarian'), $name);

    // Create submission post
    $post_id = wp_insert_post(array(
        'post_type'    => 'submission',
        'post_title'   => $title,
        'post_content' => $message,
        'post_status'  => 'publish',
        'meta_input'   => array(
            '_submission_name'  => $name,
            '_submission_email' => $email,
            '_submission_date'  => current_time('mysql'),
            '_submission_ip'    => $_SERVER['REMOTE_ADDR'],
        ),
    ));

    if ($post_id) {
        // Set submission type to 'contact'
        wp_set_object_terms($post_id, 'contact', 'submission_type');

        // Redirect with success message
        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
    }
    exit;
}
add_action('admin_post_humanitarian_contact_form', 'humanitarian_handle_contact_form');
add_action('admin_post_nopriv_humanitarian_contact_form', 'humanitarian_handle_contact_form');

/**
 * Display contact form messages
 */
function humanitarian_contact_form_messages() {
    if (!isset($_GET['contact'])) {
        return;
    }

    $status = $_GET['contact'];

    if ($status === 'success') {
        echo '<div class="contact-form-message contact-form-message--success">';
        echo '<p>' . esc_html__('Thank you! Your message has been sent successfully.', 'humanitarian') . '</p>';
        echo '</div>';
    } elseif ($status === 'error') {
        echo '<div class="contact-form-message contact-form-message--error">';
        echo '<p>' . esc_html__('There was an error sending your message. Please try again.', 'humanitarian') . '</p>';
        echo '</div>';
    }
}

/**
 * Add unread count badge to Submissions menu
 */
function humanitarian_submissions_menu_badge() {
    global $menu;

    // Count unread submissions (less than 24 hours old)
    $count = get_posts(array(
        'post_type'      => 'submission',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'date_query'     => array(
            array(
                'after' => '24 hours ago',
            ),
        ),
        'fields'         => 'ids',
    ));

    $count = count($count);

    if ($count > 0) {
        foreach ($menu as $key => $value) {
            if ($menu[$key][2] === 'edit.php?post_type=submission') {
                $menu[$key][0] .= ' <span class="awaiting-mod count-' . $count . '"><span class="pending-count">' . $count . '</span></span>';
                break;
            }
        }
    }
}
add_action('admin_menu', 'humanitarian_submissions_menu_badge', 999);
