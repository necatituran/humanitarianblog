<?php
/**
 * Grid Card Template
 *
 * Used in Current Coverage section.
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<article class="card-grid" onclick="window.location='<?php the_permalink(); ?>'">
    <div class="card-grid__image-wrapper">
        <div class="card-grid__image-overlay"></div>
        <div class="card-grid__image-gradient"></div>
        <?php humanitarian_post_thumbnail('humanitarian-card', get_the_ID(), array('class' => 'card-grid__image')); ?>
        <div class="card-grid__badge">
            <?php humanitarian_category_badge(); ?>
        </div>
    </div>
    <div class="card-grid__content">
        <h3 class="card-grid__title"><?php the_title(); ?></h3>
        <div class="card-grid__meta">
            <span><?php humanitarian_relative_date(); ?></span>
            <span class="card-grid__meta-separator">&bull;</span>
            <span><?php echo esc_html(humanitarian_reading_time()); ?></span>
        </div>
    </div>
</article>
