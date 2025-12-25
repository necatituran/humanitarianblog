<?php
/**
 * Admin Cleanup
 *
 * Simplifies the WordPress admin for authors and editors.
 * Optimized for non-technical and elderly users.
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * ============================================================================
 * AUTHOR ROLE CUSTOMIZATION
 * Remove publish capability - authors can only submit for review
 * ============================================================================
 */
function humanitarian_customize_author_role() {
    $author = get_role('author');
    if ($author) {
        // Remove publish capability - authors can only submit drafts for review
        $author->remove_cap('publish_posts');
    }
}
add_action('admin_init', 'humanitarian_customize_author_role');

/**
 * Change "Publish" button text to "Submit for Review" for authors
 */
function humanitarian_change_publish_button($translation, $text) {
    if (!current_user_can('publish_posts')) {
        if ($text === 'Publish') {
            return __('Submit for Review', 'humanitarian');
        }
        if ($text === 'Update') {
            return __('Save Changes', 'humanitarian');
        }
    }
    return $translation;
}
add_filter('gettext', 'humanitarian_change_publish_button', 10, 2);

/**
 * ============================================================================
 * MENU CLEANUP
 * Remove unnecessary admin menu items for non-admins
 * ============================================================================
 */
function humanitarian_admin_menu_cleanup() {
    if (!current_user_can('manage_options')) {
        // Remove Comments menu
        remove_menu_page('edit-comments.php');

        // Remove Tools menu
        remove_menu_page('tools.php');

        // Remove Appearance menu
        remove_menu_page('themes.php');

        // Remove Plugins menu
        remove_menu_page('plugins.php');

        // Remove Settings menu
        remove_menu_page('options-general.php');

        // Remove Media Library from menu (still accessible when adding images to posts)
        // remove_menu_page('upload.php');
    }

    // For authors specifically - even simpler menu
    if (current_user_can('author') && !current_user_can('edit_others_posts')) {
        // Remove Pages menu (authors only write posts)
        remove_menu_page('edit.php?post_type=page');
    }
}
add_action('admin_menu', 'humanitarian_admin_menu_cleanup', 999);

/**
 * ============================================================================
 * DASHBOARD CLEANUP & WELCOME WIDGET
 * ============================================================================
 */
function humanitarian_dashboard_cleanup() {
    // Remove for non-admins
    if (!current_user_can('manage_options')) {
        remove_meta_box('dashboard_primary', 'dashboard', 'side');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
    }

    // Remove for everyone
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
    remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
    remove_meta_box('dashboard_secondary', 'dashboard', 'side');
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal');
    remove_action('welcome_panel', 'wp_welcome_panel');

    // Add custom welcome widget for non-admins
    if (!current_user_can('manage_options')) {
        wp_add_dashboard_widget(
            'humanitarian_welcome_widget',
            __('Welcome to HumanitarianBlog', 'humanitarian'),
            'humanitarian_render_welcome_widget'
        );

        wp_add_dashboard_widget(
            'humanitarian_my_articles_widget',
            __('My Articles', 'humanitarian'),
            'humanitarian_render_my_articles_widget'
        );
    }
}
add_action('wp_dashboard_setup', 'humanitarian_dashboard_cleanup');

/**
 * Render the welcome widget with step-by-step guide
 */
function humanitarian_render_welcome_widget() {
    $current_user = wp_get_current_user();
    ?>
    <div class="humanitarian-welcome">
        <h3 style="margin-top: 0; font-size: 18px; color: #1a1919;">
            <?php printf(__('Hello, %s!', 'humanitarian'), esc_html($current_user->display_name)); ?>
        </h3>

        <div style="background: #f8f9fa; padding: 15px; border-radius: 4px; margin-bottom: 15px;">
            <h4 style="margin-top: 0; margin-bottom: 10px; font-size: 14px; color: #0D5C63;">
                <?php _e('How to Write a New Article:', 'humanitarian'); ?>
            </h4>
            <ol style="font-size: 14px; line-height: 2; margin: 0; padding-left: 20px; color: #333;">
                <li><strong><?php _e('Click "Write New Article"', 'humanitarian'); ?></strong> <?php _e('in the menu on the left', 'humanitarian'); ?></li>
                <li><strong><?php _e('Add your title', 'humanitarian'); ?></strong> <?php _e('at the top', 'humanitarian'); ?></li>
                <li><strong><?php _e('Write your article', 'humanitarian'); ?></strong> <?php _e('in the large text area', 'humanitarian'); ?></li>
                <li><strong><?php _e('Add a featured image', 'humanitarian'); ?></strong> <?php _e('using the box on the right', 'humanitarian'); ?></li>
                <li><strong><?php _e('Select a category', 'humanitarian'); ?></strong> <?php _e('that best fits your article', 'humanitarian'); ?></li>
                <li><strong><?php _e('Click "Submit for Review"', 'humanitarian'); ?></strong> <?php _e('when you\'re done', 'humanitarian'); ?></li>
            </ol>
        </div>

        <p style="margin-bottom: 10px; color: #666; font-size: 13px;">
            <strong><?php _e('Note:', 'humanitarian'); ?></strong>
            <?php _e('After you submit, an editor will review and publish your article.', 'humanitarian'); ?>
        </p>

        <a href="<?php echo admin_url('post-new.php'); ?>" class="button button-primary button-hero" style="margin-top: 10px;">
            <?php _e('Write New Article', 'humanitarian'); ?>
        </a>
    </div>
    <?php
}

/**
 * Render the "My Articles" widget showing author's recent posts
 */
function humanitarian_render_my_articles_widget() {
    $current_user = wp_get_current_user();

    // Get user's posts with their status
    $posts = get_posts(array(
        'author'         => $current_user->ID,
        'posts_per_page' => 10,
        'post_status'    => array('publish', 'pending', 'draft'),
        'orderby'        => 'date',
        'order'          => 'DESC',
    ));

    // Count by status
    $published_count = count(get_posts(array('author' => $current_user->ID, 'post_status' => 'publish', 'posts_per_page' => -1)));
    $pending_count = count(get_posts(array('author' => $current_user->ID, 'post_status' => 'pending', 'posts_per_page' => -1)));
    $draft_count = count(get_posts(array('author' => $current_user->ID, 'post_status' => 'draft', 'posts_per_page' => -1)));
    ?>
    <div class="humanitarian-my-articles">
        <!-- Stats -->
        <div style="display: flex; gap: 15px; margin-bottom: 20px; text-align: center;">
            <div style="flex: 1; background: #d4edda; padding: 10px; border-radius: 4px;">
                <div style="font-size: 24px; font-weight: bold; color: #155724;"><?php echo $published_count; ?></div>
                <div style="font-size: 12px; color: #155724;"><?php _e('Published', 'humanitarian'); ?></div>
            </div>
            <div style="flex: 1; background: #fff3cd; padding: 10px; border-radius: 4px;">
                <div style="font-size: 24px; font-weight: bold; color: #856404;"><?php echo $pending_count; ?></div>
                <div style="font-size: 12px; color: #856404;"><?php _e('Pending Review', 'humanitarian'); ?></div>
            </div>
            <div style="flex: 1; background: #e2e3e5; padding: 10px; border-radius: 4px;">
                <div style="font-size: 24px; font-weight: bold; color: #383d41;"><?php echo $draft_count; ?></div>
                <div style="font-size: 12px; color: #383d41;"><?php _e('Drafts', 'humanitarian'); ?></div>
            </div>
        </div>

        <!-- Recent Articles List -->
        <?php if (!empty($posts)) : ?>
        <h4 style="margin: 0 0 10px; font-size: 13px; color: #666; text-transform: uppercase;">
            <?php _e('Recent Articles', 'humanitarian'); ?>
        </h4>
        <ul style="margin: 0; padding: 0; list-style: none;">
            <?php foreach ($posts as $post) :
                $status_labels = array(
                    'publish' => '<span style="color: #155724;">&#10003; ' . __('Published', 'humanitarian') . '</span>',
                    'pending' => '<span style="color: #856404;">&#9679; ' . __('Pending', 'humanitarian') . '</span>',
                    'draft'   => '<span style="color: #6c757d;">&#9675; ' . __('Draft', 'humanitarian') . '</span>',
                );
                $status_label = isset($status_labels[$post->post_status]) ? $status_labels[$post->post_status] : '';
            ?>
            <li style="padding: 8px 0; border-bottom: 1px solid #eee;">
                <a href="<?php echo get_edit_post_link($post->ID); ?>" style="color: #1a1919; text-decoration: none;">
                    <?php echo esc_html($post->post_title ? $post->post_title : __('(No title)', 'humanitarian')); ?>
                </a>
                <div style="font-size: 12px; color: #999; margin-top: 3px;">
                    <?php echo $status_label; ?> &middot; <?php echo get_the_date('', $post->ID); ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        <?php else : ?>
        <p style="color: #666; font-style: italic;">
            <?php _e('You haven\'t written any articles yet.', 'humanitarian'); ?>
        </p>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Remove unnecessary meta boxes from post editor for non-admins
 */
function humanitarian_post_metabox_cleanup() {
    if (!current_user_can('manage_options')) {
        // Remove custom fields meta box
        remove_meta_box('postcustom', 'post', 'normal');

        // Remove trackbacks meta box
        remove_meta_box('trackbacksdiv', 'post', 'normal');

        // Remove comments meta box
        remove_meta_box('commentsdiv', 'post', 'normal');
        remove_meta_box('commentstatusdiv', 'post', 'normal');

        // Remove slug meta box
        remove_meta_box('slugdiv', 'post', 'normal');

        // Remove author meta box (authors shouldn't change author)
        if (current_user_can('author')) {
            remove_meta_box('authordiv', 'post', 'normal');
        }
    }
}
add_action('add_meta_boxes', 'humanitarian_post_metabox_cleanup', 99);

/**
 * Customize admin bar for non-admins
 */
function humanitarian_admin_bar_cleanup($wp_admin_bar) {
    if (!current_user_can('manage_options')) {
        // Remove WordPress logo
        $wp_admin_bar->remove_node('wp-logo');

        // Remove comments
        $wp_admin_bar->remove_node('comments');

        // Remove updates
        $wp_admin_bar->remove_node('updates');

        // Remove customizer
        $wp_admin_bar->remove_node('customize');

        // Remove appearance
        $wp_admin_bar->remove_node('appearance');
    }
}
add_action('admin_bar_menu', 'humanitarian_admin_bar_cleanup', 999);

/**
 * Hide admin notices for non-admins
 */
function humanitarian_hide_admin_notices() {
    if (!current_user_can('manage_options')) {
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
}
add_action('admin_head', 'humanitarian_hide_admin_notices', 1);

/**
 * Customize admin footer text
 */
function humanitarian_admin_footer_text() {
    return sprintf(
        /* translators: %s: theme name */
        __('Powered by %s Theme', 'humanitarian'),
        '<strong>HumanitarianBlog</strong>'
    );
}
add_filter('admin_footer_text', 'humanitarian_admin_footer_text');

/**
 * Remove WordPress version from admin footer
 */
add_filter('update_footer', '__return_empty_string', 11);

/**
 * Enqueue admin styles
 */
function humanitarian_admin_styles() {
    wp_enqueue_style(
        'humanitarian-admin',
        HUMANITARIAN_URI . '/assets/css/admin-style.css',
        array(),
        HUMANITARIAN_VERSION
    );
}
add_action('admin_enqueue_scripts', 'humanitarian_admin_styles');

/**
 * Customize login page
 */
function humanitarian_login_styles() {
    ?>
    <style>
        body.login {
            background-color: #FAF8F4;
        }
        .login h1 a {
            background-image: none;
            text-indent: 0;
            width: auto;
            height: auto;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 32px;
            font-weight: 700;
            color: #1a1919;
        }
        .login h1 a:hover {
            color: #0D5C63;
        }
        .login form {
            border-radius: 2px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .login input[type="submit"] {
            background: #0D5C63;
            border-color: #0D5C63;
            border-radius: 2px;
        }
        .login input[type="submit"]:hover {
            background: #094349;
            border-color: #094349;
        }
        .login #nav a,
        .login #backtoblog a {
            color: #1a1919;
        }
        .login #nav a:hover,
        .login #backtoblog a:hover {
            color: #0D5C63;
        }
    </style>
    <?php
}
add_action('login_enqueue_scripts', 'humanitarian_login_styles');

/**
 * Customize login logo URL
 */
function humanitarian_login_logo_url() {
    return home_url('/');
}
add_filter('login_headerurl', 'humanitarian_login_logo_url');

/**
 * Customize login logo title
 */
function humanitarian_login_logo_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'humanitarian_login_logo_title');

/**
 * Add custom columns to posts list
 */
function humanitarian_posts_columns($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Add featured image column after checkbox
        if ($key === 'cb') {
            $new_columns['featured_image'] = __('Image', 'humanitarian');
        }

        // Add reading time column after title
        if ($key === 'title') {
            $new_columns['reading_time'] = __('Read Time', 'humanitarian');
        }
    }

    return $new_columns;
}
add_filter('manage_posts_columns', 'humanitarian_posts_columns');

/**
 * Populate custom columns
 */
function humanitarian_posts_custom_column($column, $post_id) {
    switch ($column) {
        case 'featured_image':
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            } else {
                echo '&mdash;';
            }
            break;

        case 'reading_time':
            echo esc_html(humanitarian_reading_time($post_id));
            break;
    }
}
add_action('manage_posts_custom_column', 'humanitarian_posts_custom_column', 10, 2);

/**
 * Add sortable columns
 */
function humanitarian_sortable_columns($columns) {
    $columns['reading_time'] = 'reading_time';
    return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'humanitarian_sortable_columns');

/**
 * Style the featured image column
 */
function humanitarian_admin_column_styles() {
    ?>
    <style>
        .column-featured_image {
            width: 60px;
        }
        .column-featured_image img {
            border-radius: 2px;
        }
        .column-reading_time {
            width: 100px;
        }
    </style>
    <?php
}
add_action('admin_head', 'humanitarian_admin_column_styles');

/**
 * ============================================================================
 * USER-FRIENDLY ADMIN LABELS
 * Make WordPress terms more understandable for non-technical users
 * ============================================================================
 */
function humanitarian_change_post_labels() {
    global $wp_post_types;

    // Change "Posts" to "Articles" for clarity
    $labels = &$wp_post_types['post']->labels;
    $labels->name               = __('Articles', 'humanitarian');
    $labels->singular_name      = __('Article', 'humanitarian');
    $labels->add_new            = __('Write New Article', 'humanitarian');
    $labels->add_new_item       = __('Write New Article', 'humanitarian');
    $labels->edit_item          = __('Edit Article', 'humanitarian');
    $labels->new_item           = __('New Article', 'humanitarian');
    $labels->view_item          = __('View Article', 'humanitarian');
    $labels->search_items       = __('Search Articles', 'humanitarian');
    $labels->not_found          = __('No articles found', 'humanitarian');
    $labels->not_found_in_trash = __('No articles found in Trash', 'humanitarian');
    $labels->all_items          = __('All Articles', 'humanitarian');
    $labels->menu_name          = __('Articles', 'humanitarian');
    $labels->name_admin_bar     = __('Article', 'humanitarian');
}
add_action('init', 'humanitarian_change_post_labels');

/**
 * Change admin menu order for simplicity
 */
function humanitarian_admin_menu_order($menu_order) {
    if (!current_user_can('manage_options')) {
        return array(
            'index.php',           // Dashboard
            'separator1',
            'edit.php',            // Articles (Posts)
            'upload.php',          // Media
            'separator2',
            'profile.php',         // Profile
        );
    }
    return $menu_order;
}
add_filter('custom_menu_order', '__return_true');
add_filter('menu_order', 'humanitarian_admin_menu_order');

/**
 * Rename "Posts" menu to "Articles" in admin menu
 */
function humanitarian_change_post_menu_label() {
    global $menu;
    global $submenu;

    if (isset($menu[5])) {
        $menu[5][0] = __('Articles', 'humanitarian');
    }

    if (isset($submenu['edit.php'])) {
        $submenu['edit.php'][5][0]  = __('All Articles', 'humanitarian');
        $submenu['edit.php'][10][0] = __('Write New Article', 'humanitarian');
    }
}
add_action('admin_menu', 'humanitarian_change_post_menu_label');

/**
 * Add helpful placeholder text to post title
 */
function humanitarian_title_placeholder($title) {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'post') {
        $title = __('Enter your article title here...', 'humanitarian');
    }
    return $title;
}
add_filter('enter_title_here', 'humanitarian_title_placeholder');

/**
 * Customize the "Featured Image" metabox title for clarity
 */
function humanitarian_change_featured_image_title($content) {
    return str_replace(
        __('Featured image'),
        __('Article Main Image', 'humanitarian'),
        $content
    );
}
add_filter('admin_post_thumbnail_html', 'humanitarian_change_featured_image_title');

/**
 * Add helpful text below the title field
 */
function humanitarian_after_title_help() {
    $screen = get_current_screen();
    if ($screen && $screen->post_type === 'post' && !current_user_can('manage_options')) {
        ?>
        <div class="humanitarian-editor-help" style="background: #e8f5e9; border-left: 4px solid #0D5C63; padding: 12px 15px; margin: 15px 0; font-size: 13px;">
            <strong style="color: #0D5C63;"><?php _e('Writing Tips:', 'humanitarian'); ?></strong>
            <ul style="margin: 8px 0 0 20px; color: #333;">
                <li><?php _e('Write a clear, descriptive title', 'humanitarian'); ?></li>
                <li><?php _e('Add your main content below', 'humanitarian'); ?></li>
                <li><?php _e('Don\'t forget to add a main image on the right side', 'humanitarian'); ?></li>
                <li><?php _e('Select at least one category before submitting', 'humanitarian'); ?></li>
            </ul>
        </div>
        <?php
    }
}
add_action('edit_form_after_title', 'humanitarian_after_title_help');

/**
 * ============================================================================
 * CLASSIC EDITOR SUPPORT
 * Force Classic Editor for simpler writing experience
 * ============================================================================
 */
function humanitarian_classic_editor_settings() {
    // Default to Classic Editor for posts
    add_filter('use_block_editor_for_post_type', function($use, $post_type) {
        if ($post_type === 'post') {
            // Allow admins to use Gutenberg if they want
            if (current_user_can('manage_options')) {
                return $use;
            }
            // Force Classic Editor for non-admins
            return false;
        }
        return $use;
    }, 10, 2);
}
add_action('init', 'humanitarian_classic_editor_settings');

/**
 * Add TinyMCE (Classic Editor) customizations for simpler toolbar
 */
function humanitarian_simplify_tinymce($buttons) {
    // Simplified toolbar for non-admins
    if (!current_user_can('manage_options')) {
        return array(
            'formatselect',  // Paragraph, Headings
            'bold',
            'italic',
            'underline',
            'separator',
            'bullist',       // Bullet list
            'numlist',       // Numbered list
            'separator',
            'link',
            'unlink',
            'separator',
            'wp_adv',        // Show/hide second row
        );
    }
    return $buttons;
}
add_filter('mce_buttons', 'humanitarian_simplify_tinymce');

/**
 * Simplified second row of TinyMCE toolbar
 */
function humanitarian_simplify_tinymce_2($buttons) {
    if (!current_user_can('manage_options')) {
        return array(
            'strikethrough',
            'blockquote',
            'separator',
            'alignleft',
            'aligncenter',
            'alignright',
            'separator',
            'undo',
            'redo',
            'separator',
            'removeformat',
        );
    }
    return $buttons;
}
add_filter('mce_buttons_2', 'humanitarian_simplify_tinymce_2');

/**
 * Add custom styles to TinyMCE for better preview
 */
function humanitarian_tinymce_styles($mce_css) {
    if (!empty($mce_css)) {
        $mce_css .= ',';
    }
    $mce_css .= HUMANITARIAN_URI . '/assets/css/editor-style.css';
    return $mce_css;
}
add_filter('mce_css', 'humanitarian_tinymce_styles');
