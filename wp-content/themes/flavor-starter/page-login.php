<?php
/**
 * Template Name: Login Page
 *
 * Frontend login page for readers
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Redirect if already logged in
if (is_user_logged_in()) {
    wp_redirect(home_url('/my-account/'));
    exit;
}

get_header();

// Handle login errors
$login_error = '';
if (isset($_GET['login']) && $_GET['login'] === 'failed') {
    $login_error = __('Invalid username or password. Please try again.', 'flavor-starter');
} elseif (isset($_GET['login']) && $_GET['login'] === 'empty') {
    $login_error = __('Please enter both username and password.', 'flavor-starter');
} elseif (isset($_GET['logged_out']) && $_GET['logged_out'] === 'true') {
    $login_error = __('You have been logged out successfully.', 'flavor-starter');
}
?>

<main id="primary" class="site-main">
    <div class="auth-page">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h1 class="auth-title"><?php _e('Welcome Back', 'flavor-starter'); ?></h1>
                    <p class="auth-subtitle"><?php _e('Sign in to access your bookmarks and personalized content', 'flavor-starter'); ?></p>
                </div>

                <?php if ($login_error) : ?>
                    <div class="auth-message <?php echo isset($_GET['logged_out']) ? 'auth-message-success' : 'auth-message-error'; ?>">
                        <?php echo esc_html($login_error); ?>
                    </div>
                <?php endif; ?>

                <form id="frontend-login-form" class="auth-form" method="post" action="<?php echo esc_url(wp_login_url()); ?>">
                    <div class="form-group">
                        <label for="user_login"><?php _e('Email or Username', 'flavor-starter'); ?></label>
                        <input type="text" name="log" id="user_login" class="form-control" placeholder="<?php esc_attr_e('Enter your email or username', 'flavor-starter'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="user_pass"><?php _e('Password', 'flavor-starter'); ?></label>
                        <div class="password-input-wrapper">
                            <input type="password" name="pwd" id="user_pass" class="form-control" placeholder="<?php esc_attr_e('Enter your password', 'flavor-starter'); ?>" required>
                            <button type="button" class="password-toggle" aria-label="<?php esc_attr_e('Show password', 'flavor-starter'); ?>">
                                <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                <svg class="eye-closed" style="display:none" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-group form-group-inline">
                        <label class="checkbox-label">
                            <input type="checkbox" name="rememberme" value="forever">
                            <span><?php _e('Remember me', 'flavor-starter'); ?></span>
                        </label>
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="forgot-password-link"><?php _e('Forgot password?', 'flavor-starter'); ?></a>
                    </div>

                    <input type="hidden" name="redirect_to" value="<?php echo esc_url(home_url('/my-account/')); ?>">
                    <?php wp_nonce_field('frontend_login', 'frontend_login_nonce'); ?>

                    <button type="submit" class="btn btn-primary btn-block btn-lg">
                        <?php _e('Sign In', 'flavor-starter'); ?>
                    </button>
                </form>

                <div class="auth-footer">
                    <p><?php _e("Don't have an account?", 'flavor-starter'); ?> <a href="<?php echo esc_url(home_url('/register/')); ?>"><?php _e('Create one', 'flavor-starter'); ?></a></p>
                </div>

                <div class="auth-divider">
                    <span><?php _e('or continue with', 'flavor-starter'); ?></span>
                </div>

                <div class="social-login-buttons">
                    <button type="button" class="btn-social btn-google" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                        <span><?php _e('Google', 'flavor-starter'); ?></span>
                    </button>
                </div>
            </div>

            <div class="auth-benefits">
                <h3><?php _e('Member Benefits', 'flavor-starter'); ?></h3>
                <ul>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                        <span><?php _e('Save articles to read later', 'flavor-starter'); ?></span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        <span><?php _e('Get personalized recommendations', 'flavor-starter'); ?></span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        <span><?php _e('Join the conversation with comments', 'flavor-starter'); ?></span>
                    </li>
                    <li>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/></svg>
                        <span><?php _e('Track your reading history', 'flavor-starter'); ?></span>
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
});
</script>

<?php get_footer(); ?>
