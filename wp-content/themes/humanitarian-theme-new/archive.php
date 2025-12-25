<?php
/**
 * Archive Template
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

<div class="archive-page">
    <div class="container">

        <header class="archive-page__header">
            <?php
            the_archive_title('<h1 class="archive-page__title">', '</h1>');
            the_archive_description('<div class="archive-page__description">', '</div>');
            ?>
        </header>

        <?php if (have_posts()) : ?>

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
