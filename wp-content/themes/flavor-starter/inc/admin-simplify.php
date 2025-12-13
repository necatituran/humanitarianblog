<?php
/**
 * Admin Simplification
 *
 * Simplify the WordPress admin interface for non-technical elderly writers
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove unnecessary admin menu items for Authors
 */
function flavor_simplify_admin_menu() {

    // Only simplify for Authors (not Editors or Admins)
    if (!current_user_can('publish_posts') && current_user_can('edit_posts')) {

        // Remove Comments menu
        remove_menu_page('edit-comments.php');

        // Remove Appearance menu
        remove_menu_page('themes.php');

        // Remove Plugins menu
        remove_menu_page('plugins.php');

        // Remove Tools menu
        remove_menu_page('tools.php');

        // Remove Settings menu
        remove_menu_page('options-general.php');
    }
}
add_action('admin_menu', 'flavor_simplify_admin_menu', 999);

/**
 * Remove unnecessary meta boxes from post editor
 */
function flavor_remove_meta_boxes() {

    // Only for Authors
    if (!current_user_can('publish_posts') && current_user_can('edit_posts')) {

        // Remove discussion meta box (comments)
        remove_meta_box('commentstatusdiv', 'post', 'normal');

        // Remove comment status meta box
        remove_meta_box('commentsdiv', 'post', 'normal');

        // Remove slug meta box
        remove_meta_box('slugdiv', 'post', 'normal');

        // Remove author meta box (can't change author anyway)
        remove_meta_box('authordiv', 'post', 'normal');

        // Remove custom fields
        remove_meta_box('postcustom', 'post', 'normal');

        // Remove page attributes
        remove_meta_box('pageparentdiv', 'post', 'normal');
    }
}
add_action('admin_menu', 'flavor_remove_meta_boxes');

/**
 * Remove unnecessary dashboard widgets
 */
function flavor_remove_dashboard_widgets() {

    // Only for Authors
    if (!current_user_can('publish_posts') && current_user_can('edit_posts')) {

        // Remove Quick Draft
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');

        // Remove WordPress Events and News
        remove_meta_box('dashboard_primary', 'dashboard', 'side');

        // Remove Activity
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');

        // Remove Site Health
        remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    }
}
add_action('wp_dashboard_setup', 'flavor_remove_dashboard_widgets', 999);

/**
 * Change "Publish" button to "Submit for Review" for Authors
 */
function flavor_change_publish_button($translation, $text) {

    if ($text == 'Publish' && !current_user_can('publish_posts') && current_user_can('edit_posts')) {
        return __('Submit for Review', 'flavor-starter');
    }

    return $translation;
}
add_filter('gettext', 'flavor_change_publish_button', 10, 2);

/**
 * Prevent Authors from publishing directly
 */
function flavor_prevent_author_publish() {

    if (current_user_can('edit_posts') && !current_user_can('publish_posts')) {

        // Remove publish capability
        $role = get_role('author');
        if ($role) {
            $role->remove_cap('publish_posts');
        }
    }
}
add_action('admin_init', 'flavor_prevent_author_publish');

/**
 * Simplify admin bar for Authors
 */
function flavor_simplify_admin_bar($wp_admin_bar) {

    // Only for Authors
    if (!current_user_can('publish_posts') && current_user_can('edit_posts')) {

        // Remove WordPress logo and menu
        $wp_admin_bar->remove_node('wp-logo');

        // Remove Comments
        $wp_admin_bar->remove_node('comments');

        // Remove New Content menu except "Post"
        $wp_admin_bar->remove_node('new-page');
        $wp_admin_bar->remove_node('new-media');
        $wp_admin_bar->remove_node('new-user');
    }
}
add_action('admin_bar_menu', 'flavor_simplify_admin_bar', 999);

/**
 * Add helpful admin notice for Authors
 */
function flavor_author_help_notice() {

    $screen = get_current_screen();

    // Only show on post edit screen for Authors
    if ($screen->id === 'post' && !current_user_can('publish_posts') && current_user_can('edit_posts')) {
        ?>
        <div class="notice notice-info">
            <h3><?php _e('How to Write an Article', 'flavor-starter'); ?></h3>
            <ol>
                <li><?php _e('Write your article title in the box above', 'flavor-starter'); ?></li>
                <li><?php _e('Add your content in the editor below', 'flavor-starter'); ?></li>
                <li><?php _e('Select a Category (Aid & Policy, Conflict, Environment, etc.)', 'flavor-starter'); ?></li>
                <li><?php _e('Select an Article Type (News, Opinion, Investigation, etc.)', 'flavor-starter'); ?></li>
                <li><?php _e('Select a Region where the story takes place', 'flavor-starter'); ?></li>
                <li><?php _e('Add Tags to help readers find your article', 'flavor-starter'); ?></li>
                <li><?php _e('Upload a Featured Image (main photo for your article)', 'flavor-starter'); ?></li>
                <li><?php _e('Click "Submit for Review" - An editor will review and publish your article', 'flavor-starter'); ?></li>
            </ol>
            <p><strong><?php _e('Need help?', 'flavor-starter'); ?></strong> <?php _e('Contact your editor at editor@humanitarianblog.org', 'flavor-starter'); ?></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'flavor_author_help_notice');

/**
 * Simplify TinyMCE editor toolbar for Authors
 */
function flavor_simplify_tinymce($buttons) {

    // Only for Authors
    if (!current_user_can('publish_posts') && current_user_can('edit_posts')) {

        return array(
            'formatselect',
            'bold',
            'italic',
            'underline',
            'bullist',
            'numlist',
            'blockquote',
            'link',
            'unlink',
            'undo',
            'redo',
        );
    }

    return $buttons;
}
add_filter('mce_buttons', 'flavor_simplify_tinymce');

/**
 * Remove second toolbar row for Authors
 */
function flavor_remove_tinymce_second_row($buttons) {

    // Only for Authors
    if (!current_user_can('publish_posts') && current_user_can('edit_posts')) {
        return array();
    }

    return $buttons;
}
add_filter('mce_buttons_2', 'flavor_remove_tinymce_second_row');

/**
 * Add custom admin CSS for better readability
 */
function flavor_admin_styles() {
    wp_enqueue_style(
        'flavor-admin-style',
        FLAVOR_THEME_URI . '/assets/css/admin-style.css',
        array(),
        FLAVOR_THEME_VERSION
    );
}
add_action('admin_enqueue_scripts', 'flavor_admin_styles');

/**
 * Customize admin footer text
 */
function flavor_admin_footer_text() {
    echo sprintf(
        __('Thank you for contributing to %s', 'flavor-starter'),
        '<strong>' . get_bloginfo('name') . '</strong>'
    );
}
add_filter('admin_footer_text', 'flavor_admin_footer_text');

/**
 * Add helpful links to admin bar for Authors
 */
function flavor_add_help_links($wp_admin_bar) {

    // Only for Authors
    if (!current_user_can('publish_posts') && current_user_can('edit_posts')) {

        $wp_admin_bar->add_node(array(
            'id'    => 'writing-guide',
            'title' => __('Writing Guide', 'flavor-starter'),
            'href'  => admin_url('admin.php?page=writing-guide'),
            'meta'  => array(
                'title' => __('View the writing guide', 'flavor-starter'),
            ),
        ));
    }
}
add_action('admin_bar_menu', 'flavor_add_help_links', 100);

/**
 * Set default post status to "pending" for Authors
 */
function flavor_set_default_post_status($post) {

    if ($post->post_type === 'post' && $post->post_status === 'auto-draft' && !current_user_can('publish_posts')) {
        $post->post_status = 'pending';
    }

    return $post;
}
add_filter('wp_insert_post_data', 'flavor_set_default_post_status');
