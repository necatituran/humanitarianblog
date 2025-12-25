<?php
/**
 * Theme Setup
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Setup theme defaults and register support for various WordPress features.
 */
function humanitarian_setup() {
    // Make theme available for translation
    load_theme_textdomain('humanitarian', HUMANITARIAN_DIR . '/languages');

    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails
    add_theme_support('post-thumbnails');

    // Custom image sizes
    add_image_size('humanitarian-hero', 1200, 750, true);      // Hero main image (16:10)
    add_image_size('humanitarian-hero-secondary', 800, 533, true); // Hero secondary (3:2)
    add_image_size('humanitarian-card', 800, 600, true);       // Card image (4:3)
    add_image_size('humanitarian-card-square', 400, 400, true); // Square image (1:1)
    add_image_size('humanitarian-analysis', 1000, 625, true);  // Analysis section
    add_image_size('humanitarian-picks', 600, 400, true);      // Editor's picks (3:2)

    // Register navigation menus
    register_nav_menus(array(
        'primary'     => __('Primary Navigation', 'humanitarian'),
        'top-strip'   => __('Top Strip Navigation', 'humanitarian'),
        'footer-sections' => __('Footer Sections', 'humanitarian'),
        'footer-about'    => __('Footer About', 'humanitarian'),
        'footer-legal'    => __('Footer Legal', 'humanitarian'),
    ));

    // Switch default core markup to output valid HTML5
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
        'navigation-widgets',
    ));

    // Add support for full and wide align images
    add_theme_support('align-wide');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));

    // Add editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');

    // Disable custom colors in block editor
    add_theme_support('disable-custom-colors');

    // Add custom color palette for block editor - Navy Blue Theme
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Dark', 'humanitarian'),
            'slug'  => 'dark',
            'color' => '#1a1919',
        ),
        array(
            'name'  => __('Navy', 'humanitarian'),
            'slug'  => 'navy',
            'color' => '#1a2634',
        ),
        array(
            'name'  => __('Navy Dark', 'humanitarian'),
            'slug'  => 'navy-dark',
            'color' => '#00203f',
        ),
        array(
            'name'  => __('Navy Light', 'humanitarian'),
            'slug'  => 'navy-light',
            'color' => '#2a3a4d',
        ),
        array(
            'name'  => __('Paper', 'humanitarian'),
            'slug'  => 'paper',
            'color' => '#FAF8F4',
        ),
        array(
            'name'  => __('Cream', 'humanitarian'),
            'slug'  => 'cream',
            'color' => '#f4f1ea',
        ),
        array(
            'name'  => __('Gray', 'humanitarian'),
            'slug'  => 'gray',
            'color' => '#e5e5e5',
        ),
    ));

    // Disable custom font sizes
    add_theme_support('disable-custom-font-sizes');

    // Add custom font sizes for block editor
    add_theme_support('editor-font-sizes', array(
        array(
            'name' => __('Small', 'humanitarian'),
            'size' => 14,
            'slug' => 'small',
        ),
        array(
            'name' => __('Normal', 'humanitarian'),
            'size' => 18,
            'slug' => 'normal',
        ),
        array(
            'name' => __('Large', 'humanitarian'),
            'size' => 24,
            'slug' => 'large',
        ),
        array(
            'name' => __('Huge', 'humanitarian'),
            'size' => 32,
            'slug' => 'huge',
        ),
    ));
}
add_action('after_setup_theme', 'humanitarian_setup');

/**
 * Set the content width in pixels
 */
function humanitarian_content_width() {
    $GLOBALS['content_width'] = apply_filters('humanitarian_content_width', 768);
}
add_action('after_setup_theme', 'humanitarian_content_width', 0);

/**
 * Make custom image sizes selectable in media library
 */
function humanitarian_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'humanitarian-hero' => __('Hero Image', 'humanitarian'),
        'humanitarian-card' => __('Card Image', 'humanitarian'),
        'humanitarian-card-square' => __('Square Image', 'humanitarian'),
    ));
}
add_filter('image_size_names_choose', 'humanitarian_custom_image_sizes');

/**
 * Custom walker for primary navigation
 */
class Humanitarian_Primary_Nav_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));

        $output .= '<a href="' . esc_url($item->url) . '" class="main-nav__link ' . esc_attr($class_names) . '">';
        $output .= apply_filters('the_title', $item->title, $item->ID);
        $output .= '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        // No closing tag needed
    }
}

/**
 * Custom walker for menu overlay sections
 */
class Humanitarian_Overlay_Nav_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<a href="' . esc_url($item->url) . '" class="menu-overlay__section-link">';
        $output .= apply_filters('the_title', $item->title, $item->ID);
        $output .= '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        // No closing tag needed
    }
}

/**
 * Custom walker for footer navigation
 */
class Humanitarian_Footer_Nav_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<li>';
        $output .= '<a href="' . esc_url($item->url) . '" class="site-footer__nav-link">';
        $output .= apply_filters('the_title', $item->title, $item->ID);
        $output .= '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</li>';
    }
}

/**
 * Add custom profile fields for authors (LinkedIn-style)
 */
function humanitarian_custom_user_profile_fields($user) {
    ?>
    <h3><?php esc_html_e('Professional Information', 'humanitarian'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="profession"><?php esc_html_e('Profession / Title', 'humanitarian'); ?></label></th>
            <td>
                <input type="text" name="profession" id="profession" value="<?php echo esc_attr(get_user_meta($user->ID, 'profession', true)); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('e.g., Humanitarian Affairs Specialist, Field Coordinator', 'humanitarian'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="experience_years"><?php esc_html_e('Years of Experience', 'humanitarian'); ?></label></th>
            <td>
                <input type="number" name="experience_years" id="experience_years" value="<?php echo esc_attr(get_user_meta($user->ID, 'experience_years', true)); ?>" class="small-text" min="0" max="50" />
                <p class="description"><?php esc_html_e('Total years in the humanitarian field', 'humanitarian'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="experience_field"><?php esc_html_e('Field of Expertise', 'humanitarian'); ?></label></th>
            <td>
                <input type="text" name="experience_field" id="experience_field" value="<?php echo esc_attr(get_user_meta($user->ID, 'experience_field', true)); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('e.g., Emergency Response, Food Security, Protection', 'humanitarian'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="linkedin_url"><?php esc_html_e('LinkedIn Profile', 'humanitarian'); ?></label></th>
            <td>
                <input type="url" name="linkedin_url" id="linkedin_url" value="<?php echo esc_url(get_user_meta($user->ID, 'linkedin_url', true)); ?>" class="regular-text" />
                <p class="description"><?php esc_html_e('Your LinkedIn profile URL', 'humanitarian'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'humanitarian_custom_user_profile_fields');
add_action('edit_user_profile', 'humanitarian_custom_user_profile_fields');

/**
 * Save custom profile fields
 */
function humanitarian_save_custom_user_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['profession'])) {
        update_user_meta($user_id, 'profession', sanitize_text_field($_POST['profession']));
    }
    if (isset($_POST['experience_years'])) {
        update_user_meta($user_id, 'experience_years', absint($_POST['experience_years']));
    }
    if (isset($_POST['experience_field'])) {
        update_user_meta($user_id, 'experience_field', sanitize_text_field($_POST['experience_field']));
    }
    if (isset($_POST['linkedin_url'])) {
        update_user_meta($user_id, 'linkedin_url', esc_url_raw($_POST['linkedin_url']));
    }
}
add_action('personal_options_update', 'humanitarian_save_custom_user_profile_fields');
add_action('edit_user_profile_update', 'humanitarian_save_custom_user_profile_fields');

/**
 * Get author professional info
 */
function humanitarian_get_author_info($user_id) {
    return array(
        'profession' => get_user_meta($user_id, 'profession', true),
        'experience_years' => get_user_meta($user_id, 'experience_years', true),
        'experience_field' => get_user_meta($user_id, 'experience_field', true),
        'linkedin_url' => get_user_meta($user_id, 'linkedin_url', true),
    );
}

/**
 * Add custom columns to Users admin table
 * GÖREV 11: profession, experience_years, experience_field
 */
function humanitarian_users_columns($columns) {
    $new_columns = array();

    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;

        // Add custom columns after 'email'
        if ($key === 'email') {
            $new_columns['profession'] = __('Profession', 'humanitarian');
            $new_columns['experience'] = __('Experience', 'humanitarian');
        }
    }

    return $new_columns;
}
add_filter('manage_users_columns', 'humanitarian_users_columns');

/**
 * Display custom column content in Users admin table
 */
function humanitarian_users_column_content($value, $column_name, $user_id) {
    switch ($column_name) {
        case 'profession':
            $profession = get_user_meta($user_id, 'profession', true);
            return $profession ? esc_html($profession) : '<span style="color:#999;">—</span>';

        case 'experience':
            $years = get_user_meta($user_id, 'experience_years', true);
            $field = get_user_meta($user_id, 'experience_field', true);

            if ($years && $field) {
                return sprintf(
                    '<span style="white-space:nowrap;">%d+ %s</span><br><small style="color:#666;">%s</small>',
                    intval($years),
                    esc_html__('years', 'humanitarian'),
                    esc_html($field)
                );
            } elseif ($years) {
                return sprintf('%d+ %s', intval($years), esc_html__('years', 'humanitarian'));
            } elseif ($field) {
                return esc_html($field);
            }
            return '<span style="color:#999;">—</span>';

        default:
            return $value;
    }
}
add_filter('manage_users_custom_column', 'humanitarian_users_column_content', 10, 3);

/**
 * Make custom columns sortable
 */
function humanitarian_users_sortable_columns($columns) {
    $columns['profession'] = 'profession';
    return $columns;
}
add_filter('manage_users_sortable_columns', 'humanitarian_users_sortable_columns');

/**
 * Handle sorting by custom columns
 */
function humanitarian_users_orderby($query) {
    if (!is_admin()) {
        return;
    }

    $orderby = $query->get('orderby');

    if ($orderby === 'profession') {
        $query->set('meta_key', 'profession');
        $query->set('orderby', 'meta_value');
    }
}
add_action('pre_get_users', 'humanitarian_users_orderby');
