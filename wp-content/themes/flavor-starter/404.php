<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main error-404">

    <div class="container content-width">

        <div class="error-content text-center">
            <h1 class="error-title">404</h1>
            <h2 class="error-subtitle"><?php _e('Page Not Found', 'flavor-starter'); ?></h2>
            <p class="error-message">
                <?php _e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'flavor-starter'); ?>
            </p>

            <div class="error-search mt-8">
                <p><?php _e('Try searching for what you need:', 'flavor-starter'); ?></p>
                <?php get_search_form(); ?>
            </div>

            <div class="error-links mt-8">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <?php _e('Go to Homepage', 'flavor-starter'); ?>
                </a>
            </div>
        </div>

        <?php
        // Recent Posts
        $recent_posts = new WP_Query(array(
            'posts_per_page' => 3,
            'ignore_sticky_posts' => true,
        ));

        if ($recent_posts->have_posts()) :
            ?>
            <div class="recent-posts mt-12">
                <h3><?php _e('Recent Articles', 'flavor-starter'); ?></h3>
                <div class="grid grid-cols-3">
                    <?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
                        <article <?php post_class('article-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="card-thumbnail">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('card-medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            <div class="card-content">
                                <?php the_title('<h4 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h4>'); ?>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php
            wp_reset_postdata();
        endif;
        ?>

    </div>

</main>

<?php
get_footer();
