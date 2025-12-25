<?php
/**
 * Analysis Card Template
 *
 * Used in In-Depth Analysis section.
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<article class="card-analysis" onclick="window.location='<?php the_permalink(); ?>'">
    <div class="card-analysis__image-wrapper">
        <div class="card-analysis__badge">
            <?php humanitarian_category_badge(get_the_ID(), true); ?>
        </div>
        <?php humanitarian_post_thumbnail('humanitarian-analysis', get_the_ID(), array('class' => 'card-analysis__image')); ?>
    </div>
    <h3 class="card-analysis__title"><?php the_title(); ?></h3>
    <p class="card-analysis__excerpt"><?php humanitarian_excerpt(25); ?></p>
</article>
