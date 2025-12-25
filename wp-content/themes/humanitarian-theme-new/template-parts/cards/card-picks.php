<?php
/**
 * Editor's Picks Card Template
 *
 * Used in Editors' Picks section.
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<article class="card-picks" onclick="window.location='<?php the_permalink(); ?>'">
    <div class="card-picks__image-wrapper">
        <div class="card-picks__image-overlay"></div>
        <?php humanitarian_post_thumbnail('humanitarian-picks', get_the_ID(), array('class' => 'card-picks__image')); ?>
        <div class="card-picks__badge">
            <?php humanitarian_category_badge(); ?>
        </div>
    </div>
    <h3 class="card-picks__title"><?php the_title(); ?></h3>
    <div class="card-picks__link">
        <?php esc_html_e('Read Article', 'humanitarian'); ?>
    </div>
</article>
