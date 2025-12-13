<?php
/**
 * The template for displaying author pages
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main author-page">

    <div class="container">

        <?php if (have_posts()) : ?>

            <header class="author-header">
                <div class="author-avatar">
                    <?php echo get_avatar(get_the_author_meta('ID'), 120); ?>
                </div>

                <div class="author-info">
                    <h1 class="author-name"><?php echo get_the_author(); ?></h1>

                    <?php if (get_the_author_meta('description')) : ?>
                        <p class="author-bio"><?php the_author_meta('description'); ?></p>
                    <?php endif; ?>

                    <div class="author-meta">
                        <?php
                        $post_count = count_user_posts(get_the_author_meta('ID'));
                        printf(_n('%s Article', '%s Articles', $post_count, 'flavor-starter'), number_format_i18n($post_count));
                        ?>
                    </div>
                </div>
            </header>

            <div class="author-posts grid grid-cols-3">
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

                            <div class="card-meta">
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

            <p><?php _e('This author has not published any posts yet.', 'flavor-starter'); ?></p>

        <?php endif; ?>

    </div>

</main>

<?php
get_footer();
