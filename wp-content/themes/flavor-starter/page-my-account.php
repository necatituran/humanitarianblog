<?php
/**
 * Template Name: My Account Page
 *
 * Frontend account page for logged-in users
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Redirect if not logged in
if (!is_user_logged_in()) {
    wp_redirect(home_url('/login/'));
    exit;
}

$current_user = wp_get_current_user();
$active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'bookmarks';

// Get user's bookmarks
$bookmarks = get_user_meta($current_user->ID, 'bookmarked_posts', true);
if (!is_array($bookmarks)) {
    $bookmarks = array();
}

// Handle profile update
$profile_message = '';
if (isset($_GET['updated']) && $_GET['updated'] === 'true') {
    $profile_message = __('Profile updated successfully!', 'flavor-starter');
}

get_header();
?>

<main id="primary" class="site-main">
    <div class="account-page">
        <div class="container">
            <div class="account-layout">
                <!-- Sidebar -->
                <aside class="account-sidebar">
                    <div class="account-user-card">
                        <div class="user-avatar">
                            <?php echo get_avatar($current_user->ID, 80); ?>
                        </div>
                        <div class="user-info">
                            <h3 class="user-name"><?php echo esc_html($current_user->display_name); ?></h3>
                            <p class="user-email"><?php echo esc_html($current_user->user_email); ?></p>
                        </div>
                    </div>

                    <nav class="account-nav">
                        <a href="<?php echo esc_url(add_query_arg('tab', 'bookmarks', home_url('/my-account/'))); ?>" class="account-nav-item <?php echo $active_tab === 'bookmarks' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                            <span><?php _e('My Bookmarks', 'flavor-starter'); ?></span>
                            <?php if (count($bookmarks) > 0) : ?>
                                <span class="nav-badge"><?php echo count($bookmarks); ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="<?php echo esc_url(add_query_arg('tab', 'profile', home_url('/my-account/'))); ?>" class="account-nav-item <?php echo $active_tab === 'profile' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span><?php _e('Edit Profile', 'flavor-starter'); ?></span>
                        </a>
                        <a href="<?php echo esc_url(add_query_arg('tab', 'settings', home_url('/my-account/'))); ?>" class="account-nav-item <?php echo $active_tab === 'settings' ? 'active' : ''; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                            <span><?php _e('Settings', 'flavor-starter'); ?></span>
                        </a>
                        <div class="account-nav-divider"></div>
                        <a href="<?php echo esc_url(wp_logout_url(home_url('/login/?logged_out=true'))); ?>" class="account-nav-item account-nav-logout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            <span><?php _e('Sign Out', 'flavor-starter'); ?></span>
                        </a>
                    </nav>
                </aside>

                <!-- Main Content -->
                <div class="account-content">
                    <?php if ($profile_message) : ?>
                        <div class="auth-message auth-message-success"><?php echo esc_html($profile_message); ?></div>
                    <?php endif; ?>

                    <?php if ($active_tab === 'bookmarks') : ?>
                        <!-- Bookmarks Tab -->
                        <div class="account-section">
                            <div class="section-header">
                                <h2><?php _e('My Bookmarks', 'flavor-starter'); ?></h2>
                                <p><?php _e('Articles you saved for later reading', 'flavor-starter'); ?></p>
                            </div>

                            <?php if (empty($bookmarks)) : ?>
                                <div class="empty-state">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                    <h3><?php _e('No bookmarks yet', 'flavor-starter'); ?></h3>
                                    <p><?php _e('Start saving articles by clicking the bookmark icon on any article.', 'flavor-starter'); ?></p>
                                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary"><?php _e('Browse Articles', 'flavor-starter'); ?></a>
                                </div>
                            <?php else : ?>
                                <div class="bookmarks-grid">
                                    <?php
                                    $bookmark_query = new WP_Query(array(
                                        'post__in' => $bookmarks,
                                        'post_type' => 'post',
                                        'posts_per_page' => -1,
                                        'orderby' => 'post__in',
                                    ));

                                    if ($bookmark_query->have_posts()) :
                                        while ($bookmark_query->have_posts()) : $bookmark_query->the_post();
                                    ?>
                                        <article class="bookmark-card">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <a href="<?php the_permalink(); ?>" class="bookmark-thumbnail">
                                                    <?php the_post_thumbnail('card-medium'); ?>
                                                </a>
                                            <?php endif; ?>
                                            <div class="bookmark-content">
                                                <h3 class="bookmark-title">
                                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                                </h3>
                                                <div class="bookmark-meta">
                                                    <span class="bookmark-date"><?php echo get_the_date(); ?></span>
                                                    <span class="bookmark-reading-time"><?php echo humanitarianblog_reading_time(); ?></span>
                                                </div>
                                                <button class="bookmark-remove" data-post-id="<?php the_ID(); ?>" title="<?php esc_attr_e('Remove bookmark', 'flavor-starter'); ?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                                </button>
                                            </div>
                                        </article>
                                    <?php
                                        endwhile;
                                        wp_reset_postdata();
                                    endif;
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>

                    <?php elseif ($active_tab === 'profile') : ?>
                        <!-- Profile Tab -->
                        <div class="account-section">
                            <div class="section-header">
                                <h2><?php _e('Edit Profile', 'flavor-starter'); ?></h2>
                                <p><?php _e('Update your personal information', 'flavor-starter'); ?></p>
                            </div>

                            <form id="profile-form" class="profile-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                <div class="form-row">
                                    <div class="form-group form-group-half">
                                        <label for="first_name"><?php _e('First Name', 'flavor-starter'); ?></label>
                                        <input type="text" name="first_name" id="first_name" class="form-control" value="<?php echo esc_attr($current_user->first_name); ?>">
                                    </div>
                                    <div class="form-group form-group-half">
                                        <label for="last_name"><?php _e('Last Name', 'flavor-starter'); ?></label>
                                        <input type="text" name="last_name" id="last_name" class="form-control" value="<?php echo esc_attr($current_user->last_name); ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="display_name"><?php _e('Display Name', 'flavor-starter'); ?></label>
                                    <input type="text" name="display_name" id="display_name" class="form-control" value="<?php echo esc_attr($current_user->display_name); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="user_email"><?php _e('Email Address', 'flavor-starter'); ?></label>
                                    <input type="email" name="user_email" id="user_email" class="form-control" value="<?php echo esc_attr($current_user->user_email); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="description"><?php _e('Bio', 'flavor-starter'); ?></label>
                                    <textarea name="description" id="description" class="form-control" rows="4" placeholder="<?php esc_attr_e('Tell us a little about yourself...', 'flavor-starter'); ?>"><?php echo esc_textarea($current_user->description); ?></textarea>
                                </div>

                                <?php wp_nonce_field('update_profile', 'profile_nonce'); ?>
                                <input type="hidden" name="action" value="update_frontend_profile">

                                <button type="submit" class="btn btn-primary"><?php _e('Save Changes', 'flavor-starter'); ?></button>
                            </form>
                        </div>

                    <?php elseif ($active_tab === 'settings') : ?>
                        <!-- Settings Tab -->
                        <div class="account-section">
                            <div class="section-header">
                                <h2><?php _e('Account Settings', 'flavor-starter'); ?></h2>
                                <p><?php _e('Manage your account preferences', 'flavor-starter'); ?></p>
                            </div>

                            <div class="settings-section">
                                <h3><?php _e('Change Password', 'flavor-starter'); ?></h3>
                                <form id="password-form" class="profile-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <div class="form-group">
                                        <label for="current_password"><?php _e('Current Password', 'flavor-starter'); ?></label>
                                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="new_password"><?php _e('New Password', 'flavor-starter'); ?></label>
                                        <input type="password" name="new_password" id="new_password" class="form-control" required minlength="8">
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password"><?php _e('Confirm New Password', 'flavor-starter'); ?></label>
                                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
                                    </div>
                                    <?php wp_nonce_field('change_password', 'password_nonce'); ?>
                                    <input type="hidden" name="action" value="change_frontend_password">
                                    <button type="submit" class="btn btn-primary"><?php _e('Update Password', 'flavor-starter'); ?></button>
                                </form>
                            </div>

                            <div class="settings-section settings-section-danger">
                                <h3><?php _e('Email Preferences', 'flavor-starter'); ?></h3>
                                <form id="email-prefs-form" class="profile-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="newsletter" <?php checked(get_user_meta($current_user->ID, 'newsletter_subscribed', true), '1'); ?>>
                                        <span><?php _e('Receive weekly newsletter', 'flavor-starter'); ?></span>
                                    </label>
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="comment_notifications" <?php checked(get_user_meta($current_user->ID, 'comment_notifications', true), '1'); ?>>
                                        <span><?php _e('Receive notifications for comment replies', 'flavor-starter'); ?></span>
                                    </label>
                                    <?php wp_nonce_field('email_prefs', 'email_prefs_nonce'); ?>
                                    <input type="hidden" name="action" value="update_email_prefs">
                                    <button type="submit" class="btn btn-secondary"><?php _e('Save Preferences', 'flavor-starter'); ?></button>
                                </form>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Remove bookmark
    var removeButtons = document.querySelectorAll('.bookmark-remove');
    removeButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var postId = this.getAttribute('data-post-id');
            var card = this.closest('.bookmark-card');

            if (confirm('<?php echo esc_js(__('Remove this bookmark?', 'flavor-starter')); ?>')) {
                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'action=toggle_bookmark&post_id=' + postId + '&nonce=<?php echo wp_create_nonce('bookmark_nonce'); ?>'
                })
                .then(function(response) { return response.json(); })
                .then(function(data) {
                    if (data.success) {
                        card.style.opacity = '0';
                        card.style.transform = 'scale(0.9)';
                        setTimeout(function() { card.remove(); }, 300);
                    }
                });
            }
        });
    });
});
</script>

<?php get_footer(); ?>
