<?php
/**
 * Single Post Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="container">
    <?php
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content/content', 'single');
    endwhile;
    ?>
</div>

<!-- Related Posts -->
<?php
$categories = get_the_category();
if (!empty($categories)) :
    $related_query = new WP_Query(array(
        'posts_per_page' => 3,
        'category__in'   => array($categories[0]->term_id),
        'post__not_in'   => array(get_the_ID()),
    ));

    if ($related_query->have_posts()) :
?>
<section class="related-posts">
    <div class="container">
        <div class="related-posts__header">
            <h2 class="related-posts__title"><?php esc_html_e('Related Articles', 'humanitarian'); ?></h2>
            <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="related-posts__link">
                <?php
                /* translators: %s: category name */
                printf(esc_html__('More in %s', 'humanitarian'), esc_html($categories[0]->name));
                ?>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
        <div class="related-posts__grid">
            <?php
            while ($related_query->have_posts()) : $related_query->the_post();
                get_template_part('template-parts/cards/card', 'grid');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
<?php
    endif;
endif;
?>

<?php
get_footer();
?>
