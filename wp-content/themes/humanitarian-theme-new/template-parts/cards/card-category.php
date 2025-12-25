<?php
/**
 * Category Card Template
 *
 * Card for category archive pages with tags display
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<article class="card-category" onclick="window.location='<?php the_permalink(); ?>'">
    <div class="card-category__image-wrapper">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('humanitarian-card', array('class' => 'card-category__image')); ?>
        <?php else : ?>
            <img src="<?php echo esc_url(HUMANITARIAN_URI . '/assets/images/placeholder.jpg'); ?>"
                 alt="<?php the_title_attribute(); ?>"
                 class="card-category__image">
        <?php endif; ?>
    </div>

    <div class="card-category__content">
        <div class="card-category__meta">
            <?php
            // Show category badge
            $categories = get_the_category();
            if (!empty($categories)) :
                $cat = $categories[0];
                $cat_colors = humanitarian_get_category_colors();
                $cat_color = isset($cat_colors[$cat->slug]) ? $cat_colors[$cat->slug] : '#1a1919';
            ?>
                <span class="category-badge" style="background-color: <?php echo esc_attr($cat_color); ?>">
                    <?php echo esc_html($cat->name); ?>
                </span>
            <?php endif; ?>

            <?php
            // Show tags with different colors
            $tags = get_the_tags();
            if ($tags) :
                $tag_colors = humanitarian_get_tag_colors();
                $shown_tags = array_slice($tags, 0, 2); // Show max 2 tags
                foreach ($shown_tags as $tag) :
                    $tag_color = isset($tag_colors[$tag->slug]) ? $tag_colors[$tag->slug] : '#6b7280';
            ?>
                <span class="tag-badge" style="background-color: <?php echo esc_attr($tag_color); ?>">
                    <?php echo esc_html($tag->name); ?>
                </span>
            <?php
                endforeach;
            endif;
            ?>
        </div>

        <h3 class="card-category__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <p class="card-category__excerpt">
            <?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
        </p>

        <div class="card-category__footer">
            <span class="card-category__author">
                <?php echo get_avatar(get_the_author_meta('ID'), 24); ?>
                <?php the_author(); ?>
            </span>
            <span class="card-category__date"><?php echo get_the_date(); ?></span>
        </div>
    </div>
</article>
