<?php
/**
 * Template Name: Register Page
 *
 * Frontend registration page for readers
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Redirect if already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url('/my-account/'));
    exit;
}

// Check if registration is enabled
if (!get_option('users_can_register')) {
    wp_redirect(home_url('/login/'));
    exit;
}

get_header();

// Handle registration messages
$register_error = '';
$register_success = '';
if (isset($_GET['register']) && $_GET['register'] === 'success') {
    $register_success = __('Registration successful! Please check your email for confirmation.', 'flavor-starter');
} elseif (isset($_GET['register']) && $_GET['register'] === 'failed') {
    $register_error = isset($_GET['message']) ? sanitize_text_field($_GET['message']) : __('Registration failed. Please try again.', 'flavor-starter');
}
?>

<main id="primary" class="site-main">
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h1 class="auth-title"><?php _e('Create Account', 'flavor-starter'); ?></h1>
                    <p class="auth-subtitle"><?php _e('Join our community and never miss important stories', 'flavor-starter'); ?></p>
                </div>

                <?php if ($register_error) : ?>
                    <div class="auth-message auth-message-error">
                        <?php echo esc_html($register_error); ?>
                    </div>
                <?php endif; ?>

                <?php if ($register_success) : ?>
                    <div class="auth-message auth-message-success">
                        <?php echo esc_html($register_success); ?>
                    </div>
                <?php endif; ?>

                <form id="frontend-register-form" class="auth-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                    <div class="form-row">
                        <div class="form-group form-group-half">
                            <label for="first_name"><?php _e('First Name', 'flavor-starter'); ?></label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="<?php esc_attr_e('John', 'flavor-starter'); ?>" required>
                        </div>
                        <div class="form-group form-group-half">
                            <label for="last_name"><?php _e('Last Name', 'flavor-starter'); ?></label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="<?php esc_attr_e('Doe', 'flavor-starter'); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="user_email"><?php _e('Email Address', 'flavor-starter'); ?></label>
                        <input type="email" name="user_email" id="user_email" class="form-control" placeholder="<?php esc_attr_e('john@example.com', 'flavor-starter'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="user_login"><?php _e('Username', 'flavor-starter'); ?></label>
                        <input type="text" name="user_login" id="user_login" class="form-control" placeholder="<?php esc_attr_e('johndoe', 'flavor-starter'); ?>" required>
                        <small class="form-hint"><?php _e('Letters, numbers, and underscores only', 'flavor-starter'); ?></small>
                    </div>

                    <div class="form-group">
                        <label for="user_pass"><?php _e('Password', 'flavor-starter'); ?></label>
                        <div class="password-input-wrapper">
                            <input type="password" name="user_pass" id="user_pass" class="form-control" placeholder="<?php esc_attr_e('Create a strong password', 'flavor-starter'); ?>" required minlength="8">
                            <button type="button" class="password-toggle" aria-label="<?php esc_attr_e('Show password', 'flavor-starter'); ?>">
                                <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="eye-closed" style="display:none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                        <div class="password-strength" id="password-strength"></div>
                    </div>

                    <div class="form-group">
                        <label for="user_pass_confirm"><?php _e('Confirm Password', 'flavor-starter'); ?></label>
                        <input type="password" name="user_pass_confirm" id="user_pass_confirm" class="form-control" placeholder="<?php esc_attr_e('Confirm your password', 'flavor-starter'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="terms_agree" required>
                            <span><?php printf(__('I agree to the <a href="%s" target="_blank">Terms of Service</a> and <a href="%s" target="_blank">Privacy Policy</a>', 'flavor-starter'), esc_url(home_url('/terms-of-service/')), esc_url(home_url('/privacy-policy/'))); ?></span>
                        </label>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="newsletter_subscribe" checked>
                            <span><?php _e('Subscribe to our newsletter for weekly updates', 'flavor-starter'); ?></span>
                        </label>
                    </div>

                    <?php wp_nonce_field('frontend_register', 'frontend_register_nonce'); ?>
                    <input type="hidden" name="action" value="frontend_register">

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <?php _e('Create Account', 'flavor-starter'); ?>
                    </button>
                </form>

                <div class="auth-footer">
                    <p><?php _e('Already have an account?', 'flavor-starter'); ?> <a href="<?php echo esc_url(home_url('/login/')); ?>"><?php _e('Sign in', 'flavor-starter'); ?></a></p>
                </div>
            </div>

            <div class="auth-benefits">
                <h3><?php _e('Why Join Us?', 'flavor-starter'); ?></h3>
                <ul>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        <span><?php _e('100% Free - No credit card required', 'flavor-starter'); ?></span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                        <span><?php _e('Save unlimited articles', 'flavor-starter'); ?></span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/></svg>
                        <span><?php _e('Weekly curated newsletter', 'flavor-starter'); ?></span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                        <span><?php _e('Your data is safe with us', 'flavor-starter'); ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password toggle
    var toggleBtn = document.querySelector('.password-toggle');
    var passwordInput = document.getElementById('user_pass');
    if (toggleBtn && passwordInput) {
        var eyeOpen = toggleBtn.querySelector('.eye-open');
        var eyeClosed = toggleBtn.querySelector('.eye-closed');

        toggleBtn.addEventListener('click', function() {
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeOpen.style.display = 'none';
                eyeClosed.style.display = 'block';
            } else {
                passwordInput.type = 'password';
                eyeOpen.style.display = 'block';
                eyeClosed.style.display = 'none';
            }
        });
    }

    // Password strength indicator
    var strengthEl = document.getElementById('password-strength');
    if (passwordInput && strengthEl) {
        passwordInput.addEventListener('input', function() {
            var password = this.value;
            var strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            var labels = ['', 'Weak', 'Fair', 'Good', 'Strong', 'Excellent'];
            var colors = ['', '#ef4444', '#f59e0b', '#eab308', '#22c55e', '#10b981'];

            if (password.length > 0) {
                strengthEl.innerHTML = '<div class="strength-bar" style="width:' + (strength * 20) + '%;background:' + colors[strength] + '"></div><span style="color:' + colors[strength] + '">' + labels[strength] + '</span>';
            } else {
                strengthEl.innerHTML = '';
            }
        });
    }

    // Password confirmation validation
    var confirmInput = document.getElementById('user_pass_confirm');
    var form = document.getElementById('frontend-register-form');
    if (form && confirmInput) {
        form.addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmInput.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                confirmInput.focus();
            }
        });
    }
});
</script>

<?php get_footer(); ?>
