<?php
/**
 * The template for displaying archive pages
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main archive-page">

    <div class="container">

        <header class="archive-header">
            <?php
            the_archive_title('<h1 class="archive-title">', '</h1>');
            the_archive_description('<div class="archive-description">', '</div>');
            ?>
        </header>

        <?php if (have_posts()) : ?>

            <div class="grid grid-cols-3">
                <?php while (have_posts()) : the_post(); ?>

                    <article <?php post_class('article-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="card-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('card-medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="card-content">
                            <?php the_category(); ?>

                            <?php the_title('<h2 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>

                            <div class="card-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="card-meta">
                                <span class="author"><?php the_author(); ?></span>
                                <span class="date"><?php echo get_the_date(); ?></span>
                            </div>
                        </div>
                    </article>

                <?php endwhile; ?>
            </div>

            <?php
            the_posts_pagination(array(
                'mid_size'  => 2,
                'prev_text' => __('← Previous', 'flavor-starter'),
                'next_text' => __('Next →', 'flavor-starter'),
            ));
            ?>

        <?php else : ?>

            <p><?php _e('No posts found.', 'flavor-starter'); ?></p>

        <?php endif; ?>

    </div>

</main>

<?php
get_footer();
