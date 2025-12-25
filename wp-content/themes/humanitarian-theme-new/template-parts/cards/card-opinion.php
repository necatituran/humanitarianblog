<?php
/**
 * Opinion Card Template
 *
 * Used in Opinions section.
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$author_id = get_the_author_meta('ID');
$author_title = get_the_author_meta('description') ? wp_trim_words(get_the_author_meta('description'), 5, '') : '';
?>

<article class="card-opinion" onclick="window.location='<?php the_permalink(); ?>'">
    <div class="card-opinion__content">
        <div class="card-opinion__badge">
            <?php humanitarian_category_badge(); ?>
        </div>
        <h3 class="card-opinion__title"><?php the_title(); ?></h3>
        <div class="card-opinion__author">
            <p class="card-opinion__author-name"><?php the_author(); ?></p>
            <?php if ($author_title) : ?>
            <p class="card-opinion__author-title"><?php echo esc_html($author_title); ?></p>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-opinion__avatar">
        <?php echo get_avatar($author_id, 96); ?>
    </div>
</article>
