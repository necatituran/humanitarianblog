<?php
/**
 * Hero Secondary Articles Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get the hero post to exclude
$hero_post = humanitarian_get_hero_post();
$exclude_ids = $hero_post ? array($hero_post->ID) : array();

// Get 2 secondary posts
$secondary_args = array(
    'posts_per_page' => 2,
    'post__not_in'   => $exclude_ids,
);
if (function_exists('humanitarian_add_language_filter')) {
    $secondary_args = humanitarian_add_language_filter($secondary_args);
}
$secondary_query = new WP_Query($secondary_args);

if (!$secondary_query->have_posts()) {
    return;
}

$count = 0;
?>

<div class="hero-secondary">
    <?php while ($secondary_query->have_posts()) : $secondary_query->the_post(); $count++; ?>

        <?php if ($count === 1) : ?>
        <!-- First Article (with image on top) -->
        <article class="hero-secondary__article hero-secondary__article--top" onclick="window.location='<?php the_permalink(); ?>'">
            <div class="hero-secondary__image-wrapper">
                <?php humanitarian_post_thumbnail('humanitarian-hero-secondary', get_the_ID(), array('class' => 'hero-secondary__image')); ?>
            </div>
            <div class="hero-secondary__content">
                <?php humanitarian_category_badge(); ?>
                <h2 class="hero-secondary__title"><?php the_title(); ?></h2>
                <p class="hero-secondary__excerpt"><?php humanitarian_excerpt(15); ?></p>
            </div>
        </article>

        <div class="hero-secondary__divider"></div>

        <?php else : ?>
        <!-- Second Article (horizontal layout) -->
        <article class="hero-secondary__article hero-secondary__article--bottom" onclick="window.location='<?php the_permalink(); ?>'">
            <div class="hero-secondary__content">
                <?php humanitarian_category_badge(); ?>
                <h2 class="hero-secondary__title"><?php the_title(); ?></h2>
            </div>
            <div class="hero-secondary__image-wrapper">
                <?php humanitarian_post_thumbnail('humanitarian-card-square', get_the_ID(), array('class' => 'hero-secondary__image')); ?>
            </div>
        </article>
        <?php endif; ?>

    <?php endwhile; ?>
</div>

<?php
wp_reset_postdata();
?>
