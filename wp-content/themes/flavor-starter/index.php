<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php if (have_posts()) : ?>

        <div class="posts-container">

            <?php while (have_posts()) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <header class="entry-header">
                        <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-thumbnail">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('card-medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>

                    <div class="entry-content">
                        <?php the_excerpt(); ?>
                    </div>

                    <footer class="entry-footer">
                        <span class="posted-on"><?php echo get_the_date(); ?></span>
                    </footer>

                </article>

            <?php endwhile; ?>

        </div>

        <?php
        // Pagination
        the_posts_navigation();

    else :
        ?>

        <p><?php _e('No posts found.', 'humanitarianblog'); ?></p>

    <?php endif; ?>

</main>

<?php
get_footer();
