<?php
/**
 * Theme Customizer
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer settings
 */
function humanitarian_customize_register($wp_customize) {

    // ========================================
    // Homepage Settings Section
    // ========================================
    $wp_customize->add_section('humanitarian_homepage', array(
        'title'       => __('Homepage Settings', 'humanitarian'),
        'description' => __('Configure homepage sections and featured content.', 'humanitarian'),
        'priority'    => 30,
    ));

    // Hero Post ID
    $wp_customize->add_setting('humanitarian_hero_post_id', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
    ));

    $wp_customize->add_control('humanitarian_hero_post_id', array(
        'label'       => __('Hero Featured Post ID', 'humanitarian'),
        'description' => __('Enter the post ID to feature in the hero section. Leave empty to use the latest sticky post.', 'humanitarian'),
        'section'     => 'humanitarian_homepage',
        'type'        => 'number',
    ));

    // Show Newsletter Section
    $wp_customize->add_setting('humanitarian_show_newsletter', array(
        'default'           => true,
        'sanitize_callback' => 'humanitarian_sanitize_checkbox',
    ));

    $wp_customize->add_control('humanitarian_show_newsletter', array(
        'label'   => __('Show Newsletter Section', 'humanitarian'),
        'section' => 'humanitarian_homepage',
        'type'    => 'checkbox',
    ));

    // Newsletter URL
    $wp_customize->add_setting('humanitarian_newsletter_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('humanitarian_newsletter_url', array(
        'label'       => __('Newsletter Sign-up URL', 'humanitarian'),
        'description' => __('Link for the newsletter sign-up button.', 'humanitarian'),
        'section'     => 'humanitarian_homepage',
        'type'        => 'url',
    ));

    // ========================================
    // Social Media Section
    // ========================================
    $wp_customize->add_section('humanitarian_social', array(
        'title'    => __('Social Media', 'humanitarian'),
        'priority' => 35,
    ));

    // Twitter/X URL
    $wp_customize->add_setting('humanitarian_twitter_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('humanitarian_twitter_url', array(
        'label'   => __('Twitter/X URL', 'humanitarian'),
        'section' => 'humanitarian_social',
        'type'    => 'url',
    ));

    // Facebook URL
    $wp_customize->add_setting('humanitarian_facebook_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('humanitarian_facebook_url', array(
        'label'   => __('Facebook URL', 'humanitarian'),
        'section' => 'humanitarian_social',
        'type'    => 'url',
    ));

    // LinkedIn URL
    $wp_customize->add_setting('humanitarian_linkedin_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('humanitarian_linkedin_url', array(
        'label'   => __('LinkedIn URL', 'humanitarian'),
        'section' => 'humanitarian_social',
        'type'    => 'url',
    ));

    // Email Address
    $wp_customize->add_setting('humanitarian_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
    ));

    $wp_customize->add_control('humanitarian_email', array(
        'label'   => __('Contact Email', 'humanitarian'),
        'section' => 'humanitarian_social',
        'type'    => 'email',
    ));

    // ========================================
    // Footer Section
    // ========================================
    $wp_customize->add_section('humanitarian_footer', array(
        'title'    => __('Footer Settings', 'humanitarian'),
        'priority' => 40,
    ));

    // Footer Tagline
    $wp_customize->add_setting('humanitarian_footer_tagline', array(
        'default'           => __('Independent journalism for a more humane world. We cover the crises that others ignore and analyze the policies that shape them.', 'humanitarian'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('humanitarian_footer_tagline', array(
        'label'   => __('Footer Tagline', 'humanitarian'),
        'section' => 'humanitarian_footer',
        'type'    => 'textarea',
    ));

    // Support Text
    $wp_customize->add_setting('humanitarian_support_text', array(
        'default'           => __('We are a non-profit newsroom. Your support helps us keep our reporting free and accessible to everyone.', 'humanitarian'),
        'sanitize_callback' => 'sanitize_textarea_field',
    ));

    $wp_customize->add_control('humanitarian_support_text', array(
        'label'   => __('Support Section Text', 'humanitarian'),
        'section' => 'humanitarian_footer',
        'type'    => 'textarea',
    ));

    // Donate URL
    $wp_customize->add_setting('humanitarian_donate_url', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('humanitarian_donate_url', array(
        'label'   => __('Donate Button URL', 'humanitarian'),
        'section' => 'humanitarian_footer',
        'type'    => 'url',
    ));

    // Copyright Text
    $wp_customize->add_setting('humanitarian_copyright', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('humanitarian_copyright', array(
        'label'       => __('Copyright Text', 'humanitarian'),
        'description' => __('Leave empty for default: © [Year] [Site Name]. All rights reserved.', 'humanitarian'),
        'section'     => 'humanitarian_footer',
        'type'        => 'text',
    ));

    // ========================================
    // Top Strip Section
    // ========================================
    $wp_customize->add_section('humanitarian_top_strip', array(
        'title'    => __('Top Strip Settings', 'humanitarian'),
        'priority' => 25,
    ));

    // Tagline
    $wp_customize->add_setting('humanitarian_top_tagline', array(
        'default'           => __('Journalism from the heart of crises', 'humanitarian'),
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('humanitarian_top_tagline', array(
        'label'   => __('Top Strip Tagline', 'humanitarian'),
        'section' => 'humanitarian_top_strip',
        'type'    => 'text',
    ));

    // Show Top Strip
    $wp_customize->add_setting('humanitarian_show_top_strip', array(
        'default'           => true,
        'sanitize_callback' => 'humanitarian_sanitize_checkbox',
    ));

    $wp_customize->add_control('humanitarian_show_top_strip', array(
        'label'   => __('Show Top Strip', 'humanitarian'),
        'section' => 'humanitarian_top_strip',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'humanitarian_customize_register');

/**
 * Sanitize checkbox
 */
function humanitarian_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Get customizer option with default fallback
 *
 * @param string $option Option name
 * @param mixed $default Default value
 * @return mixed
 */
function humanitarian_get_option($option, $default = '') {
    return get_theme_mod($option, $default);
}

/**
 * Get hero post for homepage
 *
 * @return WP_Post|null
 */
function humanitarian_get_hero_post() {
    // First check if a specific post ID is set
    $hero_post_id = humanitarian_get_option('humanitarian_hero_post_id');

    if ($hero_post_id) {
        $post = get_post($hero_post_id);
        if ($post && 'publish' === $post->post_status) {
            return $post;
        }
    }

    // Fall back to sticky posts
    $sticky = get_option('sticky_posts');
    if (!empty($sticky)) {
        $sticky_posts = get_posts(array(
            'post__in'            => $sticky,
            'posts_per_page'      => 1,
            'ignore_sticky_posts' => 1,
        ));
        if (!empty($sticky_posts)) {
            return $sticky_posts[0];
        }
    }

    // Fall back to latest post
    $latest = get_posts(array(
        'posts_per_page' => 1,
    ));

    return !empty($latest) ? $latest[0] : null;
}

/**
 * Get analysis posts for homepage
 * Uses the new article_type taxonomy with fallbacks
 *
 * @param int $count Number of posts to get
 * @return WP_Query
 */
function humanitarian_get_analysis_posts($count = 2) {
    // First try posts with 'in-depth-analysis' article type taxonomy
    $args = array(
        'posts_per_page' => $count,
        'tax_query'      => array(
            array(
                'taxonomy' => 'article_type',
                'field'    => 'slug',
                'terms'    => 'in-depth-analysis',
            ),
        ),
    );

    $query = new WP_Query($args);

    // If not enough, try posts marked with the meta field (backward compatibility)
    if ($query->post_count < $count) {
        $args = array(
            'posts_per_page' => $count,
            'meta_key'       => '_humanitarian_analysis',
            'meta_value'     => '1',
        );
        $query = new WP_Query($args);
    }

    // If still not enough, try 'investigation' article type
    if ($query->post_count < $count) {
        $args = array(
            'posts_per_page' => $count,
            'tax_query'      => array(
                array(
                    'taxonomy' => 'article_type',
                    'field'    => 'slug',
                    'terms'    => array('in-depth-analysis', 'investigation'),
                ),
            ),
        );
        $query = new WP_Query($args);
    }

    // Final fallback: just get latest posts
    if ($query->post_count < $count) {
        $args = array(
            'posts_per_page' => $count,
        );
        $query = new WP_Query($args);
    }

    return $query;
}

/**
 * Get editor's picks posts for homepage
 *
 * @param int $count Number of posts to get
 * @return WP_Query
 */
function humanitarian_get_editors_picks($count = 4) {
    // First try posts marked as editor's pick
    $args = array(
        'posts_per_page' => $count,
        'meta_key'       => '_humanitarian_editors_pick',
        'meta_value'     => '1',
    );

    $query = new WP_Query($args);

    // If not enough posts, try sticky posts
    if ($query->post_count < $count) {
        $sticky = get_option('sticky_posts');
        if (!empty($sticky)) {
            $args = array(
                'post__in'            => $sticky,
                'posts_per_page'      => $count,
                'ignore_sticky_posts' => 1,
            );
            $query = new WP_Query($args);
        }
    }

    // If still not enough, just get latest posts
    if ($query->post_count < $count) {
        $args = array(
            'posts_per_page' => $count,
        );
        $query = new WP_Query($args);
    }

    return $query;
}

/**
 * Get featured posts for homepage (hero section area)
 *
 * @param int $count Number of posts to get
 * @return WP_Query
 */
function humanitarian_get_featured_posts($count = 4) {
    // First try posts marked as featured
    $args = array(
        'posts_per_page' => $count,
        'meta_key'       => '_humanitarian_featured',
        'meta_value'     => '1',
    );

    $query = new WP_Query($args);

    // If not enough posts, fallback to latest posts
    if ($query->post_count < $count) {
        $args = array(
            'posts_per_page' => $count,
        );
        $query = new WP_Query($args);
    }

    return $query;
}

/**
 * Get opinion posts for homepage
 * Uses the new article_type taxonomy with fallbacks
 *
 * @param int $count Number of posts to get
 * @return WP_Query
 */
function humanitarian_get_opinion_posts($count = 4) {
    // First try posts with 'opinion' article type taxonomy
    $args = array(
        'posts_per_page' => $count,
        'tax_query'      => array(
            array(
                'taxonomy' => 'article_type',
                'field'    => 'slug',
                'terms'    => 'opinion',
            ),
        ),
    );

    $query = new WP_Query($args);

    // If not enough, try 'opinions' category (backward compatibility)
    if ($query->post_count < $count) {
        $args = array(
            'posts_per_page' => $count,
            'category_name'  => 'opinions',
        );
        $query = new WP_Query($args);
    }

    return $query;
}

/**
 * Get posts by region for homepage or archive
 *
 * @param string $region Region slug
 * @param int $count Number of posts to get
 * @return WP_Query
 */
function humanitarian_get_region_posts($region, $count = 4) {
    $args = array(
        'posts_per_page' => $count,
        'tax_query'      => array(
            array(
                'taxonomy' => 'region',
                'field'    => 'slug',
                'terms'    => $region,
            ),
        ),
    );

    return new WP_Query($args);
}
