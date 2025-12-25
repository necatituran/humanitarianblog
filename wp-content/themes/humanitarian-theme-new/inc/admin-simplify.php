<?php
/**
 * Admin Simplification
 *
 * Simplify the WordPress admin interface for non-technical elderly writers
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Check if current user is Author role (not Editor or Admin)
 */
function humanitarian_is_author_role() {
    $user = wp_get_current_user();
    return in_array('author', (array) $user->roles) || in_array('contributor', (array) $user->roles);
}

/**
 * Remove unnecessary admin menu items for Authors
 * GÖREV 10: Authors only see Articles and Media
 */
function flavor_simplify_admin_menu() {

    // Only simplify for Authors (not Editors or Admins)
    if (humanitarian_is_author_role()) {

        // Remove Dashboard (optional - comment out if you want to keep it)
        // remove_menu_page('index.php');

        // Remove Pages menu - Authors don't need to edit pages
        remove_menu_page('edit.php?post_type=page');

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

        // Remove Users menu (Authors shouldn't manage users)
        remove_menu_page('users.php');

        // Remove Translations menu - Authors don't need to manage translations
        remove_menu_page('humanitarian-translations');

        // Remove any Submissions menu if it exists
        remove_menu_page('edit.php?post_type=submission');
        remove_submenu_page('edit.php', 'edit.php?post_type=submission');
    }
}
add_action('admin_menu', 'flavor_simplify_admin_menu', 999);

/**
 * Remove unnecessary meta boxes from post editor
 */
function flavor_remove_meta_boxes() {

    // Only for Authors
    if (humanitarian_is_author_role()) {

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
    if (humanitarian_is_author_role()) {

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

    if ($text == 'Publish' && humanitarian_is_author_role()) {
        return __('Submit for Review', 'humanitarianblog');
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
    if (humanitarian_is_author_role()) {

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
    if ($screen->id === 'post' && humanitarian_is_author_role()) {
        ?>
        <div class="notice notice-info">
            <h3><?php _e('How to Write an Article', 'humanitarianblog'); ?></h3>
            <ol>
                <li><?php _e('Write your article title in the box above', 'humanitarianblog'); ?></li>
                <li><?php _e('Add your content in the editor below', 'humanitarianblog'); ?></li>
                <li><?php _e('Select a Category (Aid & Policy, Conflict, Environment, etc.)', 'humanitarianblog'); ?></li>
                <li><?php _e('Select an Article Type (News, Opinion, Investigation, etc.)', 'humanitarianblog'); ?></li>
                <li><?php _e('Select a Region where the story takes place', 'humanitarianblog'); ?></li>
                <li><?php _e('Add Tags to help readers find your article', 'humanitarianblog'); ?></li>
                <li><?php _e('Upload a Featured Image (main photo for your article)', 'humanitarianblog'); ?></li>
                <li><?php _e('Click "Submit for Review" - An editor will review and publish your article', 'humanitarianblog'); ?></li>
            </ol>
            <p><strong><?php _e('Need help?', 'humanitarianblog'); ?></strong> <?php _e('Contact your editor at editor@humanitarianblog.org', 'humanitarianblog'); ?></p>
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
    if (humanitarian_is_author_role()) {

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
    if (humanitarian_is_author_role()) {
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
        HUMANITARIAN_URI . '/assets/css/admin-style.css',
        array(),
        HUMANITARIAN_VERSION
    );
}
add_action('admin_enqueue_scripts', 'flavor_admin_styles');

/**
 * Customize admin footer text
 */
function flavor_admin_footer_text() {
    echo sprintf(
        __('Thank you for contributing to %s', 'humanitarianblog'),
        '<strong>' . get_bloginfo('name') . '</strong>'
    );
}
add_filter('admin_footer_text', 'flavor_admin_footer_text');

/**
 * Add helpful links to admin bar for Authors
 */
function flavor_add_help_links($wp_admin_bar) {

    // Only for Authors
    if (humanitarian_is_author_role()) {

        $wp_admin_bar->add_node(array(
            'id'    => 'writing-guide',
            'title' => __('Writing Guide', 'humanitarianblog'),
            'href'  => admin_url('admin.php?page=writing-guide'),
            'meta'  => array(
                'title' => __('View the writing guide', 'humanitarianblog'),
            ),
        ));
    }
}
add_action('admin_bar_menu', 'flavor_add_help_links', 100);

/**
 * Set default post status to "pending" for Authors
 */
function flavor_set_default_post_status($post) {

    // Check if $post is an array (happens with wp_insert_post_data filter)
    $post_type = is_array($post) ? $post['post_type'] : $post->post_type;
    $post_status = is_array($post) ? $post['post_status'] : $post->post_status;

    if ($post_type === 'post' && $post_status === 'auto-draft' && !current_user_can('publish_posts')) {
        if (is_array($post)) {
            $post['post_status'] = 'pending';
        } else {
            $post->post_status = 'pending';
        }
    }

    return $post;
}
add_filter('wp_insert_post_data', 'flavor_set_default_post_status');

/**
 * Rename "Posts" to "Articles" in admin
 */
function humanitarian_rename_posts_to_articles($args, $post_type) {
    if ($post_type !== 'post') {
        return $args;
    }

    $args['labels'] = array(
        'name'                  => __('Articles', 'humanitarianblog'),
        'singular_name'         => __('Article', 'humanitarianblog'),
        'add_new'               => __('Add New', 'humanitarianblog'),
        'add_new_item'          => __('Add New Article', 'humanitarianblog'),
        'edit_item'             => __('Edit Article', 'humanitarianblog'),
        'new_item'              => __('New Article', 'humanitarianblog'),
        'view_item'             => __('View Article', 'humanitarianblog'),
        'view_items'            => __('View Articles', 'humanitarianblog'),
        'search_items'          => __('Search Articles', 'humanitarianblog'),
        'not_found'             => __('No articles found', 'humanitarianblog'),
        'not_found_in_trash'    => __('No articles found in Trash', 'humanitarianblog'),
        'all_items'             => __('All Articles', 'humanitarianblog'),
        'archives'              => __('Article Archives', 'humanitarianblog'),
        'attributes'            => __('Article Attributes', 'humanitarianblog'),
        'insert_into_item'      => __('Insert into article', 'humanitarianblog'),
        'uploaded_to_this_item' => __('Uploaded to this article', 'humanitarianblog'),
        'filter_items_list'     => __('Filter articles list', 'humanitarianblog'),
        'items_list_navigation' => __('Articles list navigation', 'humanitarianblog'),
        'items_list'            => __('Articles list', 'humanitarianblog'),
        'menu_name'             => __('Articles', 'humanitarianblog'),
        'name_admin_bar'        => __('Article', 'humanitarianblog'),
    );

    // Change menu icon to document
    $args['menu_icon'] = 'dashicons-media-document';

    return $args;
}
add_filter('register_post_type_args', 'humanitarian_rename_posts_to_articles', 10, 2);

/**
 * Add "My Articles" filter button in admin post list
 */
function humanitarian_my_articles_filter_button($post_type) {
    if ($post_type !== 'post') {
        return;
    }

    $current_user_id = get_current_user_id();
    $is_my_articles = isset($_GET['author']) && $_GET['author'] == $current_user_id;

    // Get user's article count
    $user_post_count = count_user_posts($current_user_id, 'post', true);

    $all_url = admin_url('edit.php?post_type=post');
    $my_url = add_query_arg('author', $current_user_id, $all_url);

    ?>
    <div class="alignleft actions humanitarian-article-filters" style="margin-right: 8px;">
        <a href="<?php echo esc_url($all_url); ?>"
           class="button <?php echo !$is_my_articles ? 'button-primary' : ''; ?>"
           style="margin-right: 4px;">
            <?php _e('All Articles', 'humanitarianblog'); ?>
        </a>
        <a href="<?php echo esc_url($my_url); ?>"
           class="button <?php echo $is_my_articles ? 'button-primary' : ''; ?>">
            <span class="dashicons dashicons-edit" style="vertical-align: middle; margin-right: 3px; font-size: 16px;"></span>
            <?php printf(__('My Articles (%d)', 'humanitarianblog'), $user_post_count); ?>
        </a>
    </div>
    <?php
}
add_action('restrict_manage_posts', 'humanitarian_my_articles_filter_button');

/**
 * Add article count column to admin post list
 */
function humanitarian_add_thumbnail_column($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        if ($key === 'title') {
            $new_columns['thumbnail'] = __('Image', 'humanitarianblog');
        }
        $new_columns[$key] = $value;
    }

    return $new_columns;
}
// DISABLED: Duplicate - already added in admin-cleanup.php
// add_filter('manage_posts_columns', 'humanitarian_add_thumbnail_column');

/**
 * Display thumbnail in admin column
 */
function humanitarian_thumbnail_column_content($column, $post_id) {
    if ($column !== 'thumbnail') {
        return;
    }

    $thumbnail = get_the_post_thumbnail($post_id, array(60, 45), array(
        'style' => 'border-radius: 4px; object-fit: cover;'
    ));

    if ($thumbnail) {
        echo $thumbnail;
    } else {
        echo '<span class="dashicons dashicons-format-image" style="font-size: 30px; color: #ccc; width: 60px; text-align: center;"></span>';
    }
}
add_action('manage_posts_custom_column', 'humanitarian_thumbnail_column_content', 10, 2);

/**
 * Set thumbnail column width
 */
function humanitarian_thumbnail_column_width() {
    ?>
    <style>
        .column-thumbnail { width: 70px; }
        .column-thumbnail img {
            width: 60px;
            height: 45px;
            object-fit: cover;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
    </style>
    <?php
}
add_action('admin_head-edit.php', 'humanitarian_thumbnail_column_width');
