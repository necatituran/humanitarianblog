<?php
/**
 * Demo Content Generator
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable SSL verification for local development
 * This is needed because Local by Flywheel has SSL issues with external URLs
 */
add_filter('https_ssl_verify', '__return_false');
add_filter('https_local_ssl_verify', '__return_false');
add_filter('http_request_args', function($args) {
    $args['sslverify'] = false;
    return $args;
}, 10, 1);

/**
 * Use custom avatar for users with humanitarian_avatar meta
 */
add_filter('get_avatar_url', 'humanitarian_custom_avatar_url', 10, 3);
function humanitarian_custom_avatar_url($url, $id_or_email, $args) {
    $user_id = null;

    if (is_numeric($id_or_email)) {
        $user_id = (int) $id_or_email;
    } elseif (is_object($id_or_email)) {
        if (!empty($id_or_email->user_id)) {
            $user_id = (int) $id_or_email->user_id;
        }
    } elseif (is_string($id_or_email)) {
        $user = get_user_by('email', $id_or_email);
        if ($user) {
            $user_id = $user->ID;
        }
    }

    if ($user_id) {
        $avatar_id = get_user_meta($user_id, 'humanitarian_avatar', true);
        if ($avatar_id) {
            $avatar_url = wp_get_attachment_image_url($avatar_id, 'thumbnail');
            if ($avatar_url) {
                return $avatar_url;
            }
        }
    }

    return $url;
}

/**
 * Admin notice for demo content installation
 * DISABLED - Demo content already installed
 */
function humanitarian_demo_content_notice() {
    // Notice disabled - site already has content
    return;
}
// add_action('admin_notices', 'humanitarian_demo_content_notice');

/**
 * Add demo content page under Tools menu
 */
function humanitarian_demo_content_menu() {
    add_management_page(
        __('Install Demo Content', 'humanitarian'),
        __('Demo Content', 'humanitarian'),
        'manage_options',
        'humanitarian-demo-content',
        'humanitarian_demo_content_page'
    );
}
add_action('admin_menu', 'humanitarian_demo_content_menu');

/**
 * Handle form submissions
 */
function humanitarian_demo_content_handler() {
    if (!isset($_GET['page']) || $_GET['page'] !== 'humanitarian-demo-content') {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    // Install demo content
    if (isset($_POST['install_demo']) && wp_verify_nonce($_POST['_wpnonce'], 'humanitarian_demo_install')) {
        humanitarian_install_demo_content();
        wp_safe_redirect(admin_url('tools.php?page=humanitarian-demo-content&installed=1'));
        exit;
    }

    // Reset demo flag
    if (isset($_POST['reset_demo']) && wp_verify_nonce($_POST['_wpnonce'], 'humanitarian_demo_reset')) {
        delete_option('humanitarian_demo_installed');
        wp_safe_redirect(admin_url('tools.php?page=humanitarian-demo-content&reset=1'));
        exit;
    }
}
add_action('admin_init', 'humanitarian_demo_content_handler');

/**
 * Demo content admin page
 */
function humanitarian_demo_content_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions.', 'humanitarian'));
    }

    $is_installed = get_option('humanitarian_demo_installed');
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('HumanitarianBlog Demo Content', 'humanitarian'); ?></h1>

        <?php if (isset($_GET['installed'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p><strong><?php esc_html_e('Demo content installed successfully!', 'humanitarian'); ?></strong></p>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['reset'])) : ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e('Demo flag reset. You can now reinstall demo content.', 'humanitarian'); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($is_installed) : ?>
            <div class="card">
                <h2><?php esc_html_e('Demo Content Installed', 'humanitarian'); ?></h2>
                <p><?php esc_html_e('Demo content has been installed.', 'humanitarian'); ?></p>
                <p>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="button button-primary"><?php esc_html_e('View Site', 'humanitarian'); ?></a>
                    <a href="<?php echo esc_url(admin_url('edit.php')); ?>" class="button"><?php esc_html_e('Manage Posts', 'humanitarian'); ?></a>
                </p>
            </div>

            <div class="card" style="margin-top: 20px;">
                <h2><?php esc_html_e('Reset Demo Flag', 'humanitarian'); ?></h2>
                <p><?php esc_html_e('Reset to reinstall demo content.', 'humanitarian'); ?></p>
                <form method="post">
                    <?php wp_nonce_field('humanitarian_demo_reset'); ?>
                    <input type="submit" name="reset_demo" class="button" value="<?php esc_attr_e('Reset', 'humanitarian'); ?>">
                </form>
            </div>
        <?php else : ?>
            <div class="card">
                <h2><?php esc_html_e('Install Demo Content', 'humanitarian'); ?></h2>
                <p><?php esc_html_e('This will create:', 'humanitarian'); ?></p>
                <ul style="list-style: disc; margin-left: 20px;">
                    <li>12 Categories</li>
                    <li>5 Authors</li>
                    <li>12 Posts with featured images</li>
                    <li>4 Pages</li>
                    <li>Navigation menus</li>
                </ul>
                <form method="post">
                    <?php wp_nonce_field('humanitarian_demo_install'); ?>
                    <input type="submit" name="install_demo" class="button button-primary button-hero" value="<?php esc_attr_e('Install Demo Content', 'humanitarian'); ?>">
                </form>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Main installation function
 */
function humanitarian_install_demo_content() {
    // Increase limits
    set_time_limit(600);
    ini_set('memory_limit', '512M');

    // Load required files
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // 1. Create categories
    $categories = humanitarian_create_categories();

    // 2. Create tags
    $tags = humanitarian_create_tags();

    // 3. Create authors
    $authors = humanitarian_create_authors();

    // 4. Create posts with images
    $posts = humanitarian_create_posts($categories, $authors, $tags);

    // 5. Create pages
    humanitarian_create_pages();

    // 6. Create menus
    humanitarian_create_menus($categories);

    // 7. Set customizer
    humanitarian_set_customizer($posts);

    // Mark as installed
    update_option('humanitarian_demo_installed', true);
    update_option('humanitarian_demo_installed_date', current_time('mysql'));

    return true;
}

/**
 * Create tags
 */
function humanitarian_create_tags() {
    $tag_names = array(
        'United Nations', 'UNHCR', 'World Food Programme', 'Red Cross',
        'Refugees', 'Displacement', 'IDPs', 'Asylum',
        'Climate Change', 'Drought', 'Flooding', 'Natural Disasters',
        'Food Security', 'Famine', 'Malnutrition', 'Water Crisis',
        'Conflict Zones', 'Peace Talks', 'Ceasefire', 'War Crimes',
        'Aid Workers', 'Humanitarian Access', 'NGOs', 'Funding',
        'Human Rights', 'Women\'s Rights', 'Children', 'Education',
        'Health Crisis', 'Pandemic', 'Vaccination', 'Mental Health',
        'Africa', 'Middle East', 'Asia', 'Latin America',
        'Data', 'Analysis', 'Investigation', 'Interview'
    );

    $result = array();

    foreach ($tag_names as $name) {
        $slug = sanitize_title($name);
        $term = get_term_by('slug', $slug, 'post_tag');
        if ($term) {
            $result[$slug] = $term->term_id;
        } else {
            $inserted = wp_insert_term($name, 'post_tag', array('slug' => $slug));
            if (!is_wp_error($inserted)) {
                $result[$slug] = $inserted['term_id'];
            }
        }
    }

    return $result;
}

/**
 * Create categories
 * DISABLED - Using custom categories from setup-new-categories.php instead
 * New structure: Technical Guides, Aid and Policy, Environment and Conflict, Stories from the Field
 */
function humanitarian_create_categories() {
    // Return empty array - categories are managed manually
    return array();
}

/**
 * Create authors
 */
function humanitarian_create_authors() {
    // Avatar IDs from i.pravatar.cc (1-70 range)
    // Selected to match author names/genders
    $authors_data = array(
        array(
            'user_login' => 'sarah_jenkins',
            'display_name' => 'Sarah Jenkins',
            'description' => 'Senior correspondent covering East Africa. Previously with Reuters and Al Jazeera.',
            'avatar_id' => 25  // Professional woman
        ),
        array(
            'user_login' => 'james_thorne',
            'display_name' => 'James Thorne',
            'description' => 'Field coordinator turned journalist. Covering South Sudan and the Sahel region.',
            'avatar_id' => 12  // Professional man
        ),
        array(
            'user_login' => 'elena_rostova',
            'display_name' => 'Elena Rostova',
            'description' => 'Economist and writer. Former World Bank consultant.',
            'avatar_id' => 32  // Professional woman
        ),
        array(
            'user_login' => 'amara_kone',
            'display_name' => 'Dr. Amara Kone',
            'description' => 'Senior Fellow at ODI. Expert on African development policy.',
            'avatar_id' => 68  // Professional man
        ),
        array(
            'user_login' => 'lyla_mehta',
            'display_name' => 'Professor Lyla Mehta',
            'description' => 'Professor at Institute of Development Studies. Research focus on water and climate justice.',
            'avatar_id' => 47  // Professional woman
        ),
    );

    $result = array();

    foreach ($authors_data as $data) {
        $user = get_user_by('login', $data['user_login']);
        if ($user) {
            $result[$data['user_login']] = $user->ID;
            // Update avatar if not set
            if (!get_user_meta($user->ID, 'humanitarian_avatar', true)) {
                humanitarian_set_user_avatar($user->ID, $data['avatar_id']);
            }
        } else {
            $user_id = wp_insert_user(array(
                'user_login' => $data['user_login'],
                'user_email' => $data['user_login'] . '@demo.local',
                'user_pass' => wp_generate_password(16),
                'display_name' => $data['display_name'],
                'description' => $data['description'],
                'role' => 'author'
            ));
            if (!is_wp_error($user_id)) {
                $result[$data['user_login']] = $user_id;
                // Set avatar for new user
                humanitarian_set_user_avatar($user_id, $data['avatar_id']);
            }
        }
    }

    return $result;
}

/**
 * Download and set user avatar
 */
function humanitarian_set_user_avatar($user_id, $avatar_id) {
    // Use i.pravatar.cc for realistic human photos
    // Each ID gives a consistent unique person photo
    $avatar_url = "https://i.pravatar.cc/300?img=" . $avatar_id;

    $tmp_file = download_url($avatar_url, 30);

    if (is_wp_error($tmp_file)) {
        return false;
    }

    $file_array = array(
        'name' => 'avatar-' . $user_id . '-' . time() . '.jpg',
        'tmp_name' => $tmp_file,
    );

    $attach_id = media_handle_sideload($file_array, 0, 'Avatar for user ' . $user_id);

    if (file_exists($tmp_file)) {
        @unlink($tmp_file);
    }

    if (is_wp_error($attach_id)) {
        return false;
    }

    // Save attachment ID as user meta
    update_user_meta($user_id, 'humanitarian_avatar', $attach_id);

    return $attach_id;
}

/**
 * Create posts with images
 */
function humanitarian_create_posts($categories, $authors, $tags = array()) {
    $posts_data = humanitarian_get_posts_data();
    $created = array();
    $sticky_id = null;

    // Map categories to relevant tags
    $category_tags = array(
        'investigations' => array('investigation', 'analysis', 'data'),
        'aid-policy' => array('united-nations', 'ngos', 'funding', 'aid-workers'),
        'environment' => array('climate-change', 'natural-disasters', 'water-crisis'),
        'migration' => array('refugees', 'displacement', 'unhcr', 'asylum'),
        'conflict' => array('conflict-zones', 'peace-talks', 'war-crimes'),
        'health' => array('health-crisis', 'pandemic', 'vaccination', 'mental-health'),
        'climate' => array('climate-change', 'drought', 'flooding'),
        'gender' => array('womens-rights', 'human-rights', 'children'),
        'tech' => array('data', 'analysis'),
        'economy' => array('funding', 'food-security', 'world-food-programme'),
        'interview' => array('interview', 'analysis'),
        'opinions' => array('analysis', 'human-rights'),
    );

    // Regional tags to randomly add
    $regional_tags = array('africa', 'middle-east', 'asia', 'latin-america');

    foreach ($posts_data as $index => $post) {
        // Check if exists
        $existing = get_page_by_title($post['title'], OBJECT, 'post');
        if ($existing) {
            $created[] = $existing->ID;
            if (!empty($post['sticky'])) {
                $sticky_id = $existing->ID;
            }
            continue;
        }

        // Get IDs
        $author_id = isset($authors[$post['author']]) ? $authors[$post['author']] : 1;
        $cat_id = isset($categories[$post['category']]) ? $categories[$post['category']] : 1;
        $days = isset($post['days_ago']) ? $post['days_ago'] : $index;

        // Insert post
        $post_id = wp_insert_post(array(
            'post_title' => $post['title'],
            'post_content' => $post['content'],
            'post_excerpt' => $post['excerpt'],
            'post_status' => 'publish',
            'post_author' => $author_id,
            'post_date' => date('Y-m-d H:i:s', strtotime("-{$days} days")),
            'post_category' => array($cat_id),
        ));

        if (!is_wp_error($post_id) && $post_id) {
            $created[] = $post_id;

            // Set tags for this post
            if (!empty($tags)) {
                $post_tag_ids = array();
                $cat_slug = $post['category'];

                // Add category-related tags
                if (isset($category_tags[$cat_slug])) {
                    foreach ($category_tags[$cat_slug] as $tag_slug) {
                        if (isset($tags[$tag_slug])) {
                            $post_tag_ids[] = $tags[$tag_slug];
                        }
                    }
                }

                // Add a random regional tag
                $random_region = $regional_tags[array_rand($regional_tags)];
                if (isset($tags[$random_region])) {
                    $post_tag_ids[] = $tags[$random_region];
                }

                // Set tags
                if (!empty($post_tag_ids)) {
                    wp_set_post_terms($post_id, $post_tag_ids, 'post_tag');
                }
            }

            // Set featured image - use picsum.photos which works reliably
            $image_id = humanitarian_download_and_set_image($post_id, $index);
            if ($image_id) {
                set_post_thumbnail($post_id, $image_id);
            }

            // Set sticky
            if (!empty($post['sticky'])) {
                stick_post($post_id);
                $sticky_id = $post_id;
            }

            // Set meta
            if (!empty($post['featured_type'])) {
                if ($post['featured_type'] === 'analysis') {
                    update_post_meta($post_id, '_humanitarian_analysis', '1');
                } elseif ($post['featured_type'] === 'editors_pick') {
                    update_post_meta($post_id, '_humanitarian_editors_pick', '1');
                }
            }
        }
    }

    return array(
        'posts' => $created,
        'sticky_post_id' => $sticky_id
    );
}

/**
 * Download image and attach to post
 * Uses picsum.photos which returns real photos without redirects
 */
function humanitarian_download_and_set_image($post_id, $index) {
    // Use picsum.photos - reliable, returns actual images, no auth needed
    // Different seed for each post ensures variety
    $seed = ($post_id * 7) + ($index * 13) + 1000;
    $url = "https://picsum.photos/seed/{$seed}/1200/800";

    // Download the file
    $tmp_file = download_url($url, 60);

    if (is_wp_error($tmp_file)) {
        error_log('Demo content: Failed to download image - ' . $tmp_file->get_error_message());
        // Try alternative: loremflickr
        $alt_url = "https://loremflickr.com/1200/800/nature?lock={$seed}";
        $tmp_file = download_url($alt_url, 60);

        if (is_wp_error($tmp_file)) {
            error_log('Demo content: Alternative also failed - ' . $tmp_file->get_error_message());
            return humanitarian_create_local_image($post_id);
        }
    }

    // Prepare file array
    $file_array = array(
        'name' => 'featured-' . $post_id . '-' . time() . '.jpg',
        'tmp_name' => $tmp_file,
    );

    // Upload and attach
    $attach_id = media_handle_sideload($file_array, $post_id, get_the_title($post_id));

    // Clean up temp file
    if (file_exists($tmp_file)) {
        @unlink($tmp_file);
    }

    if (is_wp_error($attach_id)) {
        error_log('Demo content: Failed to sideload - ' . $attach_id->get_error_message());
        return humanitarian_create_local_image($post_id);
    }

    return $attach_id;
}

/**
 * Create a local placeholder image as last resort
 */
function humanitarian_create_local_image($post_id) {
    if (!function_exists('imagecreatetruecolor')) {
        return false;
    }

    $upload_dir = wp_upload_dir();
    $filename = 'placeholder-' . $post_id . '-' . time() . '.jpg';
    $filepath = $upload_dir['path'] . '/' . $filename;

    // Create gradient image
    $width = 1200;
    $height = 800;
    $image = imagecreatetruecolor($width, $height);

    // Colors based on post ID
    $palettes = array(
        array(array(45, 55, 72), array(74, 85, 104)),
        array(array(55, 65, 81), array(30, 41, 59)),
        array(array(64, 63, 76), array(44, 43, 56)),
        array(array(75, 85, 99), array(51, 65, 85)),
        array(array(55, 48, 46), array(87, 83, 78)),
        array(array(39, 39, 42), array(63, 63, 70)),
    );

    $palette = $palettes[$post_id % count($palettes)];

    // Draw gradient
    for ($y = 0; $y < $height; $y++) {
        $ratio = $y / $height;
        $r = (int)($palette[0][0] + ($palette[1][0] - $palette[0][0]) * $ratio);
        $g = (int)($palette[0][1] + ($palette[1][1] - $palette[0][1]) * $ratio);
        $b = (int)($palette[0][2] + ($palette[1][2] - $palette[0][2]) * $ratio);
        $color = imagecolorallocate($image, $r, $g, $b);
        imageline($image, 0, $y, $width, $y, $color);
    }

    imagejpeg($image, $filepath, 90);
    imagedestroy($image);

    // Create attachment
    $attachment = array(
        'post_mime_type' => 'image/jpeg',
        'post_title' => 'Placeholder ' . $post_id,
        'post_status' => 'inherit',
        'guid' => $upload_dir['url'] . '/' . $filename
    );

    $attach_id = wp_insert_attachment($attachment, $filepath, $post_id);

    if ($attach_id) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $metadata = wp_generate_attachment_metadata($attach_id, $filepath);
        wp_update_attachment_metadata($attach_id, $metadata);
    }

    return $attach_id;
}

/**
 * Create pages
 */
function humanitarian_create_pages() {
    $pages = array(
        array(
            'title' => 'About Us',
            'slug' => 'about',
            'content' => '<!-- Hero Section -->
<div class="about-hero">
    <p class="about-hero__label">ABOUT US</p>
    <h1 class="about-hero__title">Independent journalism for a more humane world</h1>
    <p class="about-hero__subtitle">We are a nonprofit newsroom dedicated to in-depth coverage of humanitarian crises, conflict, and the policies that shape responses to them.</p>
</div>

<!-- Mission Section -->
<div class="about-mission">
    <div class="about-mission__content">
        <h2 class="about-mission__title">Our Mission</h2>
        <p class="about-mission__text">HumanitarianBlog was founded with a singular purpose: to shed light on the stories that matter most. We believe that journalism can be a force for good—that by documenting crises, amplifying marginalized voices, and holding power accountable, we can contribute to a more just and humane world.</p>
        <p class="about-mission__text">We go where others don\'t. We stay when others leave. We ask the questions that need asking, even when the answers are uncomfortable.</p>
    </div>
</div>

<!-- Values Section -->
<div class="about-values">
    <h2 class="about-values__title">Our Values</h2>
    <div class="about-values__grid">
        <div class="about-value">
            <div class="about-value__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
            </div>
            <h3 class="about-value__title">Independence</h3>
            <p class="about-value__text">We are not affiliated with any government, political party, or humanitarian organization. Our journalism is funded by readers and foundations committed to press freedom.</p>
        </div>
        <div class="about-value">
            <div class="about-value__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
            </div>
            <h3 class="about-value__title">Accuracy</h3>
            <p class="about-value__text">Every fact is verified. Every source is protected. We correct our mistakes publicly and promptly. Accuracy is not just a standard—it\'s a commitment to our readers.</p>
        </div>
        <div class="about-value">
            <div class="about-value__icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/></svg>
            </div>
            <h3 class="about-value__title">Humanity</h3>
            <p class="about-value__text">Behind every statistic is a person. We report with empathy and dignity, never losing sight of the human stories at the heart of every crisis.</p>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="about-team">
    <h2 class="about-team__title">Our Team</h2>
    <p class="about-team__intro">Our journalists and analysts bring decades of combined experience covering humanitarian emergencies around the world.</p>
    <div class="about-team__grid">
        <div class="about-team__member">
            <div class="about-team__avatar"></div>
            <h3 class="about-team__name">Sarah Jenkins</h3>
            <p class="about-team__role">Senior Correspondent, East Africa</p>
            <p class="about-team__bio">Previously with Reuters and Al Jazeera. Award-winning coverage of the Tigray crisis.</p>
        </div>
        <div class="about-team__member">
            <div class="about-team__avatar"></div>
            <h3 class="about-team__name">James Thorne</h3>
            <p class="about-team__role">Field Correspondent, Sahel Region</p>
            <p class="about-team__bio">Former field coordinator turned journalist. Specializes in conflict and displacement.</p>
        </div>
        <div class="about-team__member">
            <div class="about-team__avatar"></div>
            <h3 class="about-team__name">Elena Rostova</h3>
            <p class="about-team__role">Economics Editor</p>
            <p class="about-team__bio">Former World Bank consultant. Expert on humanitarian financing and aid effectiveness.</p>
        </div>
        <div class="about-team__member">
            <div class="about-team__avatar"></div>
            <h3 class="about-team__name">Dr. Amara Kone</h3>
            <p class="about-team__role">Senior Fellow & Analyst</p>
            <p class="about-team__bio">Senior Fellow at ODI. Leading voice on African development policy.</p>
        </div>
        <div class="about-team__member">
            <div class="about-team__avatar"></div>
            <h3 class="about-team__name">Prof. Lyla Mehta</h3>
            <p class="about-team__role">Climate & Water Justice</p>
            <p class="about-team__bio">Professor at Institute of Development Studies. Research focus on climate justice.</p>
        </div>
    </div>
</div>

<!-- Impact Section -->
<div class="about-impact">
    <div class="about-impact__grid">
        <div class="about-impact__stat">
            <span class="about-impact__number">45+</span>
            <span class="about-impact__label">Countries Covered</span>
        </div>
        <div class="about-impact__stat">
            <span class="about-impact__number">500+</span>
            <span class="about-impact__label">In-Depth Reports</span>
        </div>
        <div class="about-impact__stat">
            <span class="about-impact__number">2M+</span>
            <span class="about-impact__label">Monthly Readers</span>
        </div>
        <div class="about-impact__stat">
            <span class="about-impact__number">15</span>
            <span class="about-impact__label">Journalism Awards</span>
        </div>
    </div>
</div>

<!-- Contact Section -->
<div class="about-contact">
    <h2 class="about-contact__title">Get in Touch</h2>
    <div class="about-contact__grid">
        <div class="about-contact__item">
            <h3>General Inquiries</h3>
            <p>info@humanitarianblog.org</p>
        </div>
        <div class="about-contact__item">
            <h3>Editorial & Story Tips</h3>
            <p>editors@humanitarianblog.org</p>
        </div>
        <div class="about-contact__item">
            <h3>Partnerships</h3>
            <p>partnerships@humanitarianblog.org</p>
        </div>
    </div>
</div>'
        ),
        array(
            'title' => 'Contact Us',
            'slug' => 'contact',
            'content' => '<!-- Contact Hero -->
<div class="contact-hero">
    <p class="contact-hero__label">CONTACT US</p>
    <h1 class="contact-hero__title">We\'d love to hear from you</h1>
    <p class="contact-hero__subtitle">Whether you have a story tip, feedback, or just want to say hello—our team is here to help.</p>
</div>

<!-- Contact Grid -->
<div class="contact-grid">
    <div class="contact-card">
        <div class="contact-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <h3 class="contact-card__title">General Inquiries</h3>
        <p class="contact-card__text">For general questions about HumanitarianBlog, partnerships, or press inquiries.</p>
        <a href="mailto:info@humanitarianblog.org" class="contact-card__link">info@humanitarianblog.org</a>
    </div>

    <div class="contact-card">
        <div class="contact-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
        </div>
        <h3 class="contact-card__title">Story Tips</h3>
        <p class="contact-card__text">Have a news tip or lead on a story we should investigate? We protect our sources.</p>
        <a href="mailto:tips@humanitarianblog.org" class="contact-card__link">tips@humanitarianblog.org</a>
    </div>

    <div class="contact-card">
        <div class="contact-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
        </div>
        <h3 class="contact-card__title">Editorial</h3>
        <p class="contact-card__text">Pitch a story, submit an opinion piece, or inquire about freelance opportunities.</p>
        <a href="mailto:editors@humanitarianblog.org" class="contact-card__link">editors@humanitarianblog.org</a>
    </div>

    <div class="contact-card">
        <div class="contact-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <h3 class="contact-card__title">Feedback</h3>
        <p class="contact-card__text">Let us know how we\'re doing. Your feedback helps us improve our journalism.</p>
        <a href="mailto:feedback@humanitarianblog.org" class="contact-card__link">feedback@humanitarianblog.org</a>
    </div>
</div>

<!-- Social Section -->
<div class="contact-social">
    <h2 class="contact-social__title">Follow Us</h2>
    <p class="contact-social__text">Stay connected and join the conversation on social media.</p>
    <div class="contact-social__links">
        <a href="#" class="contact-social__link" aria-label="Twitter">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
        </a>
        <a href="#" class="contact-social__link" aria-label="Facebook">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
        </a>
        <a href="#" class="contact-social__link" aria-label="LinkedIn">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
        </a>
        <a href="#" class="contact-social__link" aria-label="Instagram">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
        </a>
        <a href="#" class="contact-social__link" aria-label="YouTube">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
        </a>
    </div>
</div>

<!-- Office Info -->
<div class="contact-office">
    <div class="contact-office__item">
        <h3>Headquarters</h3>
        <p>Geneva, Switzerland</p>
        <p class="contact-office__note">By appointment only</p>
    </div>
    <div class="contact-office__item">
        <h3>Regional Offices</h3>
        <p>Nairobi, Kenya</p>
        <p>Bangkok, Thailand</p>
    </div>
    <div class="contact-office__item">
        <h3>Secure Communication</h3>
        <p>For sensitive information, use our SecureDrop or Signal.</p>
        <p class="contact-office__note">Details available upon request</p>
    </div>
</div>'
        ),
        array(
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'content' => '<!-- Privacy Hero -->
<div class="legal-hero">
    <p class="legal-hero__label">LEGAL</p>
    <h1 class="legal-hero__title">Privacy Policy</h1>
    <p class="legal-hero__updated">Last updated: ' . date('F j, Y') . '</p>
</div>

<div class="legal-content">
    <div class="legal-intro">
        <p>At HumanitarianBlog, we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.</p>
    </div>

    <div class="legal-section">
        <h2>Information We Collect</h2>
        <h3>Information You Provide</h3>
        <ul>
            <li><strong>Newsletter subscriptions:</strong> Email address and name when you subscribe to our newsletters</li>
            <li><strong>Contact forms:</strong> Name, email, and message content when you reach out to us</li>
            <li><strong>Comments:</strong> Name, email, and comment content if you engage with our articles</li>
        </ul>

        <h3>Automatically Collected Information</h3>
        <ul>
            <li><strong>Log data:</strong> IP address, browser type, pages visited, time spent on pages</li>
            <li><strong>Cookies:</strong> Small files stored on your device to improve your experience</li>
            <li><strong>Analytics:</strong> Aggregated data about how visitors use our site</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>How We Use Your Information</h2>
        <ul>
            <li>To send you newsletters and editorial updates you\'ve subscribed to</li>
            <li>To respond to your inquiries and provide support</li>
            <li>To analyze and improve our website and content</li>
            <li>To protect against fraudulent or unauthorized activity</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>Information Sharing</h2>
        <p>We do not sell, trade, or rent your personal information to third parties. We may share information with:</p>
        <ul>
            <li>Service providers who assist in operating our website (hosting, analytics, email delivery)</li>
            <li>Legal authorities when required by law or to protect our rights</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access the personal data we hold about you</li>
            <li>Request correction of inaccurate data</li>
            <li>Request deletion of your data</li>
            <li>Unsubscribe from our communications at any time</li>
            <li>Opt out of cookies through your browser settings</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>Data Security</h2>
        <p>We implement appropriate technical and organizational measures to protect your personal data. However, no method of transmission over the Internet is 100% secure.</p>
    </div>

    <div class="legal-section">
        <h2>Contact Us</h2>
        <p>If you have questions about this Privacy Policy, please contact us at:</p>
        <p><strong>Email:</strong> privacy@humanitarianblog.org</p>
    </div>
</div>'
        ),
        array(
            'title' => 'Terms of Use',
            'slug' => 'terms-of-use',
            'content' => '<!-- Terms Hero -->
<div class="legal-hero">
    <p class="legal-hero__label">LEGAL</p>
    <h1 class="legal-hero__title">Terms of Use</h1>
    <p class="legal-hero__updated">Last updated: ' . date('F j, Y') . '</p>
</div>

<div class="legal-content">
    <div class="legal-intro">
        <p>Welcome to HumanitarianBlog. By accessing and using this website, you accept and agree to be bound by these Terms of Use. Please read them carefully.</p>
    </div>

    <div class="legal-section">
        <h2>Use of Content</h2>
        <p>All content published on HumanitarianBlog—including articles, photographs, graphics, and other materials—is protected by copyright and other intellectual property laws.</p>
        <h3>You May:</h3>
        <ul>
            <li>Share links to our articles on social media and other platforms</li>
            <li>Quote brief excerpts with proper attribution and a link to the original article</li>
            <li>Print articles for personal, non-commercial use</li>
        </ul>
        <h3>You May Not:</h3>
        <ul>
            <li>Reproduce, republish, or redistribute our content without permission</li>
            <li>Remove or alter any copyright notices or attributions</li>
            <li>Use our content for commercial purposes without a license</li>
            <li>Frame or embed our content on other websites without permission</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>Republication & Licensing</h2>
        <p>Some of our content is available for republication under Creative Commons licenses. For commercial licensing or republication requests, please contact:</p>
        <p><strong>Email:</strong> licensing@humanitarianblog.org</p>
    </div>

    <div class="legal-section">
        <h2>User Conduct</h2>
        <p>When interacting with our website, you agree not to:</p>
        <ul>
            <li>Post false, misleading, or defamatory content</li>
            <li>Harass, threaten, or abuse other users or our staff</li>
            <li>Attempt to gain unauthorized access to our systems</li>
            <li>Use automated systems to scrape or collect data</li>
            <li>Interfere with the proper functioning of the website</li>
        </ul>
    </div>

    <div class="legal-section">
        <h2>Disclaimer</h2>
        <p>The information on this website is provided for general informational purposes. While we strive for accuracy, we make no warranties about the completeness, reliability, or accuracy of this information.</p>
        <p>HumanitarianBlog is not liable for any losses or damages arising from your use of our website or reliance on our content.</p>
    </div>

    <div class="legal-section">
        <h2>Changes to Terms</h2>
        <p>We reserve the right to modify these Terms of Use at any time. Changes will be effective immediately upon posting. Your continued use of the website constitutes acceptance of the modified terms.</p>
    </div>

    <div class="legal-section">
        <h2>Governing Law</h2>
        <p>These Terms shall be governed by the laws of Switzerland. Any disputes shall be resolved in the courts of Geneva, Switzerland.</p>
    </div>

    <div class="legal-section">
        <h2>Contact</h2>
        <p>For questions about these Terms of Use, please contact:</p>
        <p><strong>Email:</strong> legal@humanitarianblog.org</p>
    </div>
</div>'
        ),
    );

    foreach ($pages as $page) {
        if (!get_page_by_path($page['slug'])) {
            wp_insert_post(array(
                'post_title' => $page['title'],
                'post_name' => $page['slug'],
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
        }
    }
}

/**
 * Create menus
 */
function humanitarian_create_menus($categories) {
    $locations = get_theme_mod('nav_menu_locations', array());

    // Primary Menu
    if (!wp_get_nav_menu_object('Primary Menu')) {
        $menu_id = wp_create_nav_menu('Primary Menu');
        $items = array('aid-policy', 'conflict', 'environment', 'investigations', 'migration');
        $pos = 1;
        foreach ($items as $slug) {
            if (isset($categories[$slug])) {
                wp_update_nav_menu_item($menu_id, 0, array(
                    'menu-item-title' => get_term($categories[$slug])->name,
                    'menu-item-object' => 'category',
                    'menu-item-object-id' => $categories[$slug],
                    'menu-item-type' => 'taxonomy',
                    'menu-item-status' => 'publish',
                    'menu-item-position' => $pos++,
                ));
            }
        }
        $locations['primary'] = $menu_id;
    }

    // Top Strip Menu
    if (!wp_get_nav_menu_object('Top Strip Menu')) {
        $menu_id = wp_create_nav_menu('Top Strip Menu');
        $about = get_page_by_path('about');
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'About Us',
            'menu-item-url' => $about ? get_permalink($about) : home_url('/about/'),
            'menu-item-type' => 'custom',
            'menu-item-status' => 'publish',
            'menu-item-position' => 1,
        ));
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'Newsletters',
            'menu-item-url' => '#',
            'menu-item-type' => 'custom',
            'menu-item-status' => 'publish',
            'menu-item-position' => 2,
        ));
        $locations['top-strip'] = $menu_id;
    }

    set_theme_mod('nav_menu_locations', $locations);
}

/**
 * Set customizer options
 */
function humanitarian_set_customizer($posts) {
    if (!empty($posts['sticky_post_id'])) {
        set_theme_mod('humanitarian_hero_post', $posts['sticky_post_id']);
    }
    set_theme_mod('humanitarian_show_newsletter', true);
    set_theme_mod('humanitarian_footer_text', 'Independent journalism for a more humane world.');
    set_theme_mod('humanitarian_twitter', 'https://twitter.com/humanitarianblog');
    set_theme_mod('humanitarian_facebook', 'https://facebook.com/humanitarianblog');
}

/**
 * Get posts data - 36 posts (3 per category for 12 categories)
 */
function humanitarian_get_posts_data() {
    return array(
        // ============ INVESTIGATIONS (3 posts) ============
        array(
            'title' => 'The Hidden Cost of Safe Zones: Inside the Displacement Camps of Northern Goma',
            'excerpt' => 'As conflict escalates in the eastern DRC, so-called safe zones are becoming traps for civilians.',
            'category' => 'investigations',
            'author' => 'sarah_jenkins',
            'days_ago' => 0,
            'sticky' => true,
            'featured_type' => 'analysis',
            'content' => '<p class="lead">The morning sun breaks over the volcanic hills surrounding Goma, casting long shadows across a makeshift settlement that now houses over 50,000 people—triple its intended capacity.</p>
<p>When the M23 rebel group launched its latest offensive in November, hundreds of thousands of Congolese fled their homes. But what they found was a system stretched beyond breaking point.</p>
<h2>A System Under Strain</h2>
<p>According to UNHCR figures, the camps around Goma are now operating at 340% capacity. Water distribution points designed to serve 5,000 people are being used by 17,000.</p>
<blockquote><p>"We are witnessing a protection crisis within a protection framework."</p></blockquote>
<p>"Humanitarian action cannot substitute for political solutions," argues Thomas Lubanga, a researcher at the Congolese think tank POLE Institute.</p>'
        ),
        array(
            'title' => 'Tracking the Arms: How Weapons Flow Into Yemen Despite Embargo',
            'excerpt' => 'An investigation into the networks supplying weapons to all sides of Yemen\'s civil war.',
            'category' => 'investigations',
            'author' => 'james_thorne',
            'days_ago' => 8,
            'content' => '<p class="lead">Despite a UN arms embargo, weapons continue to pour into Yemen, fueling a conflict that has created the world\'s worst humanitarian crisis.</p>
<p>Through months of investigation, we traced shipments from Eastern Europe through the Gulf and into the hands of fighters on multiple sides of the conflict.</p>
<h2>The Supply Chain</h2>
<p>Arms dealers operate through a web of shell companies and falsified end-user certificates. One shipment we tracked passed through five countries before reaching Yemen.</p>
<blockquote><p>"Everyone knows what is happening. The embargo exists on paper only."</p></blockquote>'
        ),
        array(
            'title' => 'The Missing Billions: Where Does Humanitarian Aid Really Go?',
            'excerpt' => 'Following the money trail from donor to beneficiary reveals troubling inefficiencies.',
            'category' => 'investigations',
            'author' => 'elena_rostova',
            'days_ago' => 20,
            'content' => '<p class="lead">For every dollar donated to humanitarian causes, how much actually reaches people in need? The answer is surprisingly difficult to determine.</p>
<p>Our six-month investigation tracked funding flows across multiple crises and found systemic opacity in how aid money is spent.</p>
<h2>Overhead and Inefficiency</h2>
<p>Coordination costs, security expenses, and administrative overhead consume significant portions of aid budgets—but exact figures are rarely disclosed.</p>
<blockquote><p>"Donors don\'t want to hear that 40 cents of their dollar goes to logistics."</p></blockquote>'
        ),

        // ============ AID-POLICY (3 posts) ============
        array(
            'title' => 'UN Security Council Gridlock Stalls Critical Vote on Sudan Sanctions',
            'excerpt' => 'Diplomatic sources say a resolution is unlikely as permanent members remain divided.',
            'category' => 'aid-policy',
            'author' => 'james_thorne',
            'days_ago' => 1,
            'content' => '<p class="lead">As Sudan enters its ninth month of civil war, a proposed UN Security Council resolution to expand sanctions remains stuck in diplomatic limbo.</p>
<h2>Deep Divisions</h2>
<p>Russia has maintained close ties with the SAF leadership, while the UAE has been accused of providing weapons to the RSF.</p>
<blockquote><p>"Every week of delay costs lives. The Security Council\'s paralysis is a moral failure."</p></blockquote>
<p>More than 7 million people have been displaced—the largest displacement crisis in the world.</p>'
        ),
        array(
            'title' => 'Cash Programming Reaches Record Levels, But Is It Reaching the Right People?',
            'excerpt' => 'Humanitarian organizations distributed $10.2 billion in cash and vouchers last year.',
            'category' => 'aid-policy',
            'author' => 'elena_rostova',
            'days_ago' => 4,
            'featured_type' => 'editors_pick',
            'content' => '<p class="lead">Humanitarian organizations distributed $10.2 billion in cash and vouchers last year, representing 21% of total humanitarian assistance.</p>
<h2>The Evidence</h2>
<p>Research shows cash is as effective as in-kind assistance for most outcomes, often at lower cost.</p>
<blockquote><p>"Cash respects dignity. It treats people as agents of their own recovery."</p></blockquote>'
        ),
        array(
            'title' => 'How Local Organizations Are Reshaping Humanitarian Response',
            'excerpt' => 'The localization agenda has gained momentum, but power and resources remain concentrated.',
            'category' => 'aid-policy',
            'author' => 'amara_kone',
            'days_ago' => 12,
            'featured_type' => 'analysis',
            'content' => '<p class="lead">For years, the humanitarian sector has talked about localization. Progress has been slow.</p>
<p>Only 1.2% of humanitarian funding goes directly to local and national NGOs.</p>
<h2>Changing Dynamics</h2>
<p>Local organizations are increasingly vocal about their role—not as implementing partners, but as leaders.</p>
<blockquote><p>"We don\'t need capacity building. We have capacity. What we need is access to resources."</p></blockquote>'
        ),

        // ============ ENVIRONMENT (3 posts) ============
        array(
            'title' => 'Rising Tides, Sinking Hopes: Pacific Nations Preparing for Total Relocation',
            'excerpt' => 'As sea levels rise, entire nations face moving their populations to higher ground.',
            'category' => 'environment',
            'author' => 'lyla_mehta',
            'days_ago' => 2,
            'featured_type' => 'analysis',
            'content' => '<p class="lead">On the island of Tebunginako in Kiribati, the cemetery is disappearing. The ocean washes over the graves of ancestors.</p>
<h2>Nations Without Land</h2>
<p>What happens to a nation when its land vanishes? International law provides no clear answers.</p>
<blockquote><p>"We refuse to accept that our country will simply cease to exist."</p></blockquote>
<p>Tuvalu has declared itself the world\'s first digital nation, copying its laws and heritage to secure servers.</p>'
        ),
        array(
            'title' => 'The Amazon\'s Last Stand: Indigenous Communities Fight for Survival',
            'excerpt' => 'Deforestation threatens both the rainforest and the people who have protected it for millennia.',
            'category' => 'environment',
            'author' => 'sarah_jenkins',
            'days_ago' => 15,
            'content' => '<p class="lead">Deep in the Brazilian Amazon, the Yanomami people are fighting a battle on multiple fronts: illegal miners, disease, and government indifference.</p>
<h2>A Crisis Ignored</h2>
<p>Despite international attention, deforestation rates continue to climb. Indigenous territories that once seemed protected are now under siege.</p>
<blockquote><p>"The forest is our pharmacy, our supermarket, our home. Without it, we are nothing."</p></blockquote>'
        ),
        array(
            'title' => 'Water Wars: The Conflicts Climate Change Is Already Causing',
            'excerpt' => 'From the Nile to the Mekong, water scarcity is driving tensions between nations.',
            'category' => 'environment',
            'author' => 'james_thorne',
            'days_ago' => 25,
            'content' => '<p class="lead">The Grand Ethiopian Renaissance Dam has brought Egypt and Ethiopia to the brink of conflict. It won\'t be the last water war.</p>
<h2>Scarce Resources</h2>
<p>Climate change is altering rainfall patterns worldwide. Rivers that once flowed reliably are becoming unpredictable.</p>
<blockquote><p>"Water will be the oil of the 21st century—and the source of its conflicts."</p></blockquote>'
        ),

        // ============ MIGRATION (3 posts) ============
        array(
            'title' => 'Mediterranean Crossings Hit Decade Low, But Fatality Rates Surge',
            'excerpt' => 'While crossing attempts decreased, the death rate per crossing has tripled.',
            'category' => 'migration',
            'author' => 'sarah_jenkins',
            'days_ago' => 3,
            'content' => '<p class="lead">Mediterranean crossings in 2024 are down 60%. But the death rate per crossing attempt has tripled.</p>
<h2>Shifting Patterns</h2>
<p>Crossings from West Africa to the Canary Islands have surged 300%, with journeys of over 1,000 kilometers.</p>
<blockquote><p>"The boats leave at night. Sometimes they are found weeks later, drifting empty."</p></blockquote>'
        ),
        array(
            'title' => 'The Darien Gap: The World\'s Most Dangerous Migration Route',
            'excerpt' => 'Hundreds of thousands risk death crossing the jungle between Colombia and Panama.',
            'category' => 'migration',
            'author' => 'james_thorne',
            'days_ago' => 14,
            'featured_type' => 'analysis',
            'content' => '<p class="lead">The Darien Gap—60 miles of dense jungle—has become a highway of desperation for migrants heading north to the United States.</p>
<h2>A Deadly Journey</h2>
<p>This year alone, over 400,000 people have attempted the crossing. Hundreds have died. The true toll is unknown.</p>
<blockquote><p>"We knew we might die. But staying meant certain death. At least this way, there was hope."</p></blockquote>'
        ),
        array(
            'title' => 'Europe\'s Externalized Borders: The Human Cost of Migration Deals',
            'excerpt' => 'Agreements with North African countries are preventing arrivals—at what price?',
            'category' => 'migration',
            'author' => 'elena_rostova',
            'days_ago' => 22,
            'content' => '<p class="lead">The EU\'s migration deals with Libya, Tunisia, and Morocco have reduced arrivals. But reports of abuse are mounting.</p>
<h2>Outsourced Cruelty</h2>
<p>Migrants describe being abandoned in the desert, detained indefinitely, and beaten by authorities funded by European money.</p>
<blockquote><p>"Europe has not stopped migration. It has simply moved the suffering out of sight."</p></blockquote>'
        ),

        // ============ CONFLICT (3 posts) ============
        array(
            'title' => 'Gaza Under Siege: Six Months of Humanitarian Catastrophe',
            'excerpt' => 'The blockade and military operations have created unprecedented civilian suffering.',
            'category' => 'conflict',
            'author' => 'sarah_jenkins',
            'days_ago' => 5,
            'featured_type' => 'editors_pick',
            'content' => '<p class="lead">Six months into the current conflict, Gaza faces conditions that aid workers describe as apocalyptic.</p>
<h2>A Population Trapped</h2>
<p>With borders closed and aid restricted, 2.2 million people have nowhere to go and little to survive on.</p>
<blockquote><p>"I have worked in emergencies for 20 years. I have never seen anything like this."</p></blockquote>'
        ),
        array(
            'title' => 'Ukraine\'s Forgotten Frontlines: The Villages Caught in Endless War',
            'excerpt' => 'Far from the headlines, communities along the contact line endure daily shelling.',
            'category' => 'conflict',
            'author' => 'elena_rostova',
            'days_ago' => 16,
            'content' => '<p class="lead">In villages along the eastern front, residents have adapted to a life punctuated by explosions. Many refuse to leave.</p>
<h2>Living with War</h2>
<p>These communities have learned to distinguish incoming from outgoing fire. They sleep in basements and farm between bombardments.</p>
<blockquote><p>"This is our home. Where would we go? Who would tend our graves?"</p></blockquote>'
        ),
        array(
            'title' => 'Myanmar\'s Civil War: The Revolution the World Forgot',
            'excerpt' => 'Three years after the coup, armed resistance continues—largely ignored by international media.',
            'category' => 'conflict',
            'author' => 'james_thorne',
            'days_ago' => 28,
            'content' => '<p class="lead">The military junta that seized power in 2021 now controls less territory than ever. But the resistance receives little international support.</p>
<h2>A Forgotten Struggle</h2>
<p>Young people who once protested peacefully have taken up arms. The humanitarian toll continues to mount.</p>
<blockquote><p>"The world has moved on. We cannot."</p></blockquote>'
        ),

        // ============ HEALTH (3 posts) ============
        array(
            'title' => 'The Hidden Epidemic: Mental Health in Conflict Zones',
            'excerpt' => 'Trauma affects millions, but mental health services remain scarce.',
            'category' => 'health',
            'author' => 'lyla_mehta',
            'days_ago' => 9,
            'content' => '<p class="lead">Wherever there is conflict, there is an invisible epidemic of psychological trauma.</p>
<p>One in five people in conflict-affected areas experiences depression, anxiety, or PTSD.</p>
<h2>A Neglected Crisis</h2>
<p>Yet mental health receives only 0.3% of humanitarian funding.</p>
<blockquote><p>"We treat the wounds we can see. But the wounds we cannot see are often deeper."</p></blockquote>'
        ),
        array(
            'title' => 'Cholera Returns: The 19th Century Disease Surging in the 21st',
            'excerpt' => 'Outbreaks are increasing worldwide as infrastructure fails and climate change disrupts water supplies.',
            'category' => 'health',
            'author' => 'amara_kone',
            'days_ago' => 18,
            'content' => '<p class="lead">Cholera killed millions in the 1800s. It should be a disease of the past. Instead, cases are surging.</p>
<h2>A Preventable Crisis</h2>
<p>Cholera is easily prevented with clean water and sanitation. But investment in these basics has lagged.</p>
<blockquote><p>"People are dying from a disease we know how to prevent. That is the definition of failure."</p></blockquote>'
        ),
        array(
            'title' => 'The Last Mile: Getting Vaccines to the Hardest-to-Reach Communities',
            'excerpt' => 'Cold chains, conflict, and distrust create barriers to immunization in remote areas.',
            'category' => 'health',
            'author' => 'sarah_jenkins',
            'days_ago' => 30,
            'featured_type' => 'analysis',
            'content' => '<p class="lead">Global vaccination rates have stalled. The challenge now is reaching the children who have been left behind.</p>
<h2>Beyond Access</h2>
<p>Getting vaccines to remote communities requires not just logistics, but trust. Misinformation has made that harder.</p>
<blockquote><p>"We can deliver vaccines anywhere. Convincing parents to accept them is the real challenge."</p></blockquote>'
        ),

        // ============ CLIMATE (3 posts) ============
        array(
            'title' => 'Climate Finance Falls Short as Developing Nations Bear the Cost',
            'excerpt' => 'Rich countries promised $100 billion annually for climate adaptation. They have not delivered.',
            'category' => 'climate',
            'author' => 'elena_rostova',
            'days_ago' => 10,
            'content' => '<p class="lead">In 2009, wealthy nations promised $100 billion annually by 2020 to help developing countries adapt. That target has never been met.</p>
<h2>The Numbers</h2>
<p>Climate finance reached $83.3 billion in 2020. But much of this was loans, not grants—adding to debt burdens.</p>
<blockquote><p>"We did not cause this crisis. But we are being asked to pay for it."</p></blockquote>'
        ),
        array(
            'title' => 'The Horn of Africa\'s Endless Drought: Climate Change in Real Time',
            'excerpt' => 'Five consecutive failed rainy seasons have pushed millions to the brink of famine.',
            'category' => 'climate',
            'author' => 'james_thorne',
            'days_ago' => 19,
            'featured_type' => 'editors_pick',
            'content' => '<p class="lead">Somalia, Ethiopia, and Kenya are experiencing their worst drought in 40 years. Climate scientists say it would have been impossible without global warming.</p>
<h2>Beyond Drought</h2>
<p>When rain finally comes, it often brings floods. The climate is becoming not just drier, but more extreme.</p>
<blockquote><p>"Our elders do not recognize this weather. It follows no pattern they have ever known."</p></blockquote>'
        ),
        array(
            'title' => 'Loss and Damage: The Climate Debt Rich Countries Owe',
            'excerpt' => 'After decades of resistance, wealthy nations have agreed to pay. But how much, and to whom?',
            'category' => 'climate',
            'author' => 'lyla_mehta',
            'days_ago' => 32,
            'content' => '<p class="lead">At COP28, a loss and damage fund was finally established. It\'s a historic breakthrough—and a drop in the ocean of what\'s needed.</p>
<h2>The Reckoning</h2>
<p>Estimates of climate-related losses in developing countries run into hundreds of billions annually. The fund has $700 million.</p>
<blockquote><p>"This is not charity. This is compensation for harm caused."</p></blockquote>'
        ),

        // ============ GENDER (3 posts) ============
        array(
            'title' => 'Afghanistan Three Years On: Women Erased from Public Life',
            'excerpt' => 'Since the Taliban takeover, women have been systematically excluded from education and employment.',
            'category' => 'gender',
            'author' => 'sarah_jenkins',
            'days_ago' => 6,
            'featured_type' => 'editors_pick',
            'content' => '<p class="lead">Three years after the Taliban\'s return, Afghan women face the most severe restrictions on their rights anywhere in the world.</p>
<h2>The Human Cost</h2>
<p>Girls cannot attend school beyond sixth grade. Women cannot work for NGOs or appear in public without a male guardian.</p>
<blockquote><p>"They want us to disappear. But we will not disappear."</p></blockquote>'
        ),
        array(
            'title' => 'The Double Burden: How Crises Affect Women Differently',
            'excerpt' => 'From displacement to food insecurity, women bear disproportionate impacts of humanitarian emergencies.',
            'category' => 'gender',
            'author' => 'lyla_mehta',
            'days_ago' => 21,
            'content' => '<p class="lead">When crisis hits, women and girls are often the last to eat, the first to drop out of school, and the most likely to face violence.</p>
<h2>Invisible Impacts</h2>
<p>Humanitarian data rarely captures these gendered differences. What we don\'t measure, we don\'t address.</p>
<blockquote><p>"We talk about vulnerable groups. But we rarely ask why women are made vulnerable."</p></blockquote>'
        ),
        array(
            'title' => 'Breaking Barriers: Women Leading Humanitarian Response',
            'excerpt' => 'Despite obstacles, women are increasingly taking leadership roles in crisis response.',
            'category' => 'gender',
            'author' => 'elena_rostova',
            'days_ago' => 35,
            'content' => '<p class="lead">The humanitarian sector has long been led by men. That is slowly changing—but not fast enough.</p>
<h2>Changing Leadership</h2>
<p>Women now head several major UN agencies. Local women\'s organizations are demanding seats at the table.</p>
<blockquote><p>"Women don\'t just experience crises differently. We respond to them differently—often more effectively."</p></blockquote>'
        ),

        // ============ TECH (3 posts) ============
        array(
            'title' => 'The Data Revolution That Never Was: Why Humanitarian Information Systems Fail',
            'excerpt' => 'Despite billions invested, humanitarian organizations still struggle with basic coordination.',
            'category' => 'tech',
            'author' => 'amara_kone',
            'days_ago' => 7,
            'content' => '<p class="lead">A decade ago, the humanitarian sector promised a data revolution. Basic questions remain unanswered.</p>
<h2>Fragmented Systems</h2>
<p>The problem is not technology—it is governance. Each agency maintains its own systems with different standards.</p>
<blockquote><p>"We have more data than ever, but less clarity."</p></blockquote>'
        ),
        array(
            'title' => 'AI in Humanitarian Response: Promise and Peril',
            'excerpt' => 'Machine learning could transform aid delivery—or entrench existing biases.',
            'category' => 'tech',
            'author' => 'elena_rostova',
            'days_ago' => 23,
            'content' => '<p class="lead">Artificial intelligence is being deployed to predict famines, target aid, and identify fraud. But at what cost?</p>
<h2>The Algorithm Decides</h2>
<p>When an algorithm determines who receives assistance, who is accountable for its errors?</p>
<blockquote><p>"We are automating decisions about life and death. We should be very careful."</p></blockquote>'
        ),
        array(
            'title' => 'Digital ID in Crisis: Biometrics and the Right to Aid',
            'excerpt' => 'Refugee registration systems collect unprecedented data. Is it being protected?',
            'category' => 'tech',
            'author' => 'amara_kone',
            'days_ago' => 33,
            'featured_type' => 'analysis',
            'content' => '<p class="lead">To receive aid, refugees must often provide fingerprints, iris scans, and detailed personal information. Where does that data go?</p>
<h2>Data Protection Gaps</h2>
<p>Humanitarian organizations are not bound by the same data protection laws as governments or companies.</p>
<blockquote><p>"Refugees have no choice but to hand over their data. That is not consent."</p></blockquote>'
        ),

        // ============ ECONOMY (3 posts) ============
        array(
            'title' => 'Debt and Disaster: How Economic Crises Fuel Humanitarian Ones',
            'excerpt' => 'Countries drowning in debt cannot afford to prepare for—or respond to—emergencies.',
            'category' => 'economy',
            'author' => 'elena_rostova',
            'days_ago' => 11,
            'content' => '<p class="lead">Sri Lanka, Lebanon, Pakistan—a wave of economic crises is creating humanitarian emergencies that dwarf natural disasters.</p>
<h2>The Debt Trap</h2>
<p>Countries spending more on debt service than health and education cannot build resilience to shocks.</p>
<blockquote><p>"We are choosing between paying creditors and feeding our children."</p></blockquote>'
        ),
        array(
            'title' => 'The Aid Economy: When Humanitarian Presence Distorts Local Markets',
            'excerpt' => 'International organizations bring resources—and unintended economic consequences.',
            'category' => 'economy',
            'author' => 'amara_kone',
            'days_ago' => 24,
            'content' => '<p class="lead">When aid workers arrive, rents rise, skilled workers leave local organizations, and prices inflate. Is the cure sometimes worse than the disease?</p>
<h2>Unintended Consequences</h2>
<p>The presence of well-funded international organizations can hollow out local capacity even as it provides short-term relief.</p>
<blockquote><p>"They pay our best nurses five times what hospitals can. Then they leave, and we have no nurses."</p></blockquote>'
        ),
        array(
            'title' => 'Sanctions and Suffering: When Economic Warfare Hurts Civilians',
            'excerpt' => 'Designed to pressure governments, sanctions often inflict the greatest harm on ordinary people.',
            'category' => 'economy',
            'author' => 'james_thorne',
            'days_ago' => 36,
            'content' => '<p class="lead">From North Korea to Syria to Venezuela, comprehensive sanctions have coincided with humanitarian crises. Coincidence or causation?</p>
<h2>Collective Punishment</h2>
<p>Sanctions advocates argue that suffering is the point—that populations will pressure their governments to change. The evidence suggests otherwise.</p>
<blockquote><p>"Leaders eat. Their people starve. And the world pretends this is diplomacy."</p></blockquote>'
        ),

        // ============ INTERVIEW (3 posts) ============
        array(
            'title' => 'Interview: WFP Chief on the Global Hunger Crisis',
            'excerpt' => 'Cindy McCain discusses the unprecedented challenges facing the World Food Programme.',
            'category' => 'interview',
            'author' => 'james_thorne',
            'days_ago' => 13,
            'content' => '<p class="lead">The World Food Programme faces its most challenging moment since its founding.</p>
<p><strong>HB:</strong> How would you describe the current state of global food security?</p>
<p><strong>CM:</strong> We are facing a perfect storm. Conflict, climate change, and economic shocks have combined to create hunger at levels we haven\'t seen in decades.</p>
<h2>On Funding</h2>
<p><strong>HB:</strong> WFP has faced significant funding shortfalls. How are you adapting?</p>
<p><strong>CM:</strong> We are making impossible choices every day.</p>'
        ),
        array(
            'title' => 'Interview: A Refugee\'s Journey from Syria to Medical School',
            'excerpt' => 'Ahmad Khalil fled Aleppo at 16. Now he\'s training to be a doctor in Germany.',
            'category' => 'interview',
            'author' => 'sarah_jenkins',
            'days_ago' => 26,
            'content' => '<p class="lead">Eight years ago, Ahmad Khalil was a teenager in a war zone. Today, he is studying medicine in Berlin.</p>
<p><strong>HB:</strong> What do you remember most about leaving Syria?</p>
<p><strong>AK:</strong> The sound of the boat engine. I thought it was too small. I thought we would all die. Fourteen hours later, we reached Greece.</p>
<h2>On Integration</h2>
<p><strong>HB:</strong> What was the hardest part of starting over?</p>
<p><strong>AK:</strong> Learning that my experience mattered. In Syria, I was just a refugee. Here, I am a person with a story.</p>'
        ),
        array(
            'title' => 'Interview: The Aid Worker Who Became a Whistleblower',
            'excerpt' => 'After reporting misconduct, she lost her job. She doesn\'t regret it.',
            'category' => 'interview',
            'author' => 'elena_rostova',
            'days_ago' => 38,
            'content' => '<p class="lead">Maria Santos spent 15 years in humanitarian work. Then she reported sexual exploitation by colleagues. Her career ended overnight.</p>
<p><strong>HB:</strong> Why did you come forward?</p>
<p><strong>MS:</strong> Because I couldn\'t live with myself if I didn\'t. The people we were supposed to help were being victimized by the people supposed to protect them.</p>
<h2>On Retaliation</h2>
<p><strong>HB:</strong> What happened after you reported?</p>
<p><strong>MS:</strong> I was told I was not a "team player." I was moved to a desk job. Then my contract was not renewed.</p>'
        ),

        // ============ OPINIONS (3 posts) ============
        array(
            'title' => 'Opinion: The Humanitarian System Is Broken. Here\'s How to Fix It',
            'excerpt' => 'The current model of humanitarian response is not fit for purpose.',
            'category' => 'opinions',
            'author' => 'amara_kone',
            'days_ago' => 17,
            'featured_type' => 'editors_pick',
            'content' => '<p class="lead">After three decades in humanitarian response, I have reached a difficult conclusion: the system is broken.</p>
<h2>What Needs to Change</h2>
<p>First, we need to shift power. Decisions are made in Geneva by people who have never experienced the crises they respond to.</p>
<p>Second, we need to invest in prevention. For every dollar spent on prevention, we save seven on response.</p>
<blockquote><p>"We cannot solve 21st-century problems with 20th-century institutions."</p></blockquote>'
        ),
        array(
            'title' => 'Opinion: It\'s Time to End the Humanitarian-Development Divide',
            'excerpt' => 'Treating emergencies and long-term development as separate domains makes no sense.',
            'category' => 'opinions',
            'author' => 'lyla_mehta',
            'days_ago' => 27,
            'content' => '<p class="lead">The artificial division between humanitarian and development aid costs lives and wastes resources.</p>
<h2>A False Dichotomy</h2>
<p>Crises don\'t respect our organizational charts. A drought is both an emergency and a development failure.</p>
<blockquote><p>"We keep putting out fires without asking why everything keeps catching fire."</p></blockquote>'
        ),
        array(
            'title' => 'Opinion: Why I Left the UN After 20 Years',
            'excerpt' => 'The institution I joined is not the institution I left. The bureaucracy has won.',
            'category' => 'opinions',
            'author' => 'james_thorne',
            'days_ago' => 40,
            'content' => '<p class="lead">I spent two decades at the United Nations. I believed in its mission. I still do. But I no longer believe in its capacity to fulfill it.</p>
<h2>Death by Process</h2>
<p>Every year, there are more reports, more meetings, more coordination mechanisms—and less actual impact.</p>
<blockquote><p>"We have become experts at describing problems and amateurs at solving them."</p></blockquote>'
        ),
    );
}
