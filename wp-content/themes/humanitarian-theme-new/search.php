<?php
/**
 * Search Results Template
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

<div class="search-page">
    <div class="container">

        <header class="search-page__header">
            <h1 class="search-page__title">
                <?php
                /* translators: %s: search query */
                printf(esc_html__('Search results for: %s', 'humanitarian'), '<span>' . get_search_query() . '</span>');
                ?>
            </h1>
            <div class="search-page__form">
                <?php get_search_form(); ?>
            </div>
        </header>

        <?php if (have_posts()) : ?>

        <p class="search-page__results-count">
            <?php
            /* translators: %d: number of results */
            printf(
                esc_html(_n('%d result found', '%d results found', $wp_query->found_posts, 'humanitarian')),
                absint($wp_query->found_posts)
            );
            ?>
        </p>

        <div class="archive-page__grid">
            <?php
            while (have_posts()) : the_post();
                get_template_part('template-parts/cards/card', 'grid');
            endwhile;
            ?>
        </div>

        <?php humanitarian_pagination(); ?>

        <?php else : ?>

        <?php get_template_part('template-parts/content/content', 'none'); ?>

        <?php endif; ?>

    </div>
</div>

<?php
get_footer();
?>
