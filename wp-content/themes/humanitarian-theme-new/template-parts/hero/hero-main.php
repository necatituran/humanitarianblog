<?php
/**
 * Hero Main Feature Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get the hero post
$hero_post = humanitarian_get_hero_post();

if (!$hero_post) {
    return;
}

// Setup post data
setup_postdata($hero_post);
?>

<article class="hero-main" onclick="window.location='<?php echo esc_url(get_permalink($hero_post->ID)); ?>'">
    <div class="hero-main__image-wrapper">
        <div class="hero-main__image-overlay"></div>
        <?php
        if (has_post_thumbnail($hero_post->ID)) {
            echo get_the_post_thumbnail($hero_post->ID, 'humanitarian-hero', array(
                'class'   => 'hero-main__image',
                'loading' => 'eager', // Hero image should load immediately
            ));
        } else {
            printf(
                '<img src="%s" alt="%s" class="hero-main__image" loading="eager">',
                esc_url(HUMANITARIAN_URI . '/assets/images/placeholder.jpg'),
                esc_attr(get_the_title($hero_post->ID))
            );
        }
        ?>
    </div>
    <div class="hero-main__content">
        <div class="hero-main__meta">
            <?php humanitarian_category_badge($hero_post->ID); ?>
            <span class="hero-main__meta-separator">&bull;</span>
            <span class="hero-main__reading-time"><?php echo esc_html(humanitarian_reading_time($hero_post->ID)); ?></span>
        </div>
        <h1 class="hero-main__title">
            <?php echo esc_html(get_the_title($hero_post->ID)); ?>
        </h1>
        <p class="hero-main__excerpt">
            <?php humanitarian_excerpt(35, $hero_post->ID); ?>
        </p>
        <div class="hero-main__author">
            <div class="hero-main__author-avatar">
                <?php echo get_avatar(get_post_field('post_author', $hero_post->ID), 40); ?>
            </div>
            <div class="hero-main__author-info">
                <?php esc_html_e('By', 'humanitarian'); ?>
                <?php echo esc_html(get_the_author_meta('display_name', get_post_field('post_author', $hero_post->ID))); ?>
                <span class="hero-main__author-separator">|</span>
                <?php echo esc_html(get_the_date('', $hero_post->ID)); ?>
            </div>
        </div>
    </div>
</article>

<?php
// Reset post data
wp_reset_postdata();
?>
