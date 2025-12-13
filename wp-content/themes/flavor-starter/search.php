<?php
/**
 * The template for displaying search results
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main search-results">

    <div class="container">

        <header class="search-header">
            <h1 class="search-title">
                <?php
                printf(
                    esc_html__('Search Results for: %s', 'humanitarianblog'),
                    '<span>' . get_search_query() . '</span>'
                );
                ?>
            </h1>
            <?php
            global $wp_query;
            if ($wp_query->found_posts) {
                printf(
                    '<p class="search-count">' . _n('%s result found', '%s results found', $wp_query->found_posts, 'humanitarianblog') . '</p>',
                    number_format_i18n($wp_query->found_posts)
                );
            }
            ?>
        </header>

        <?php if (have_posts()) : ?>

            <div class="search-results-list">
                <?php while (have_posts()) : the_post(); ?>

                    <article <?php post_class('search-result-item'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="result-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('card-small'); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="result-content">
                            <?php the_category(); ?>

                            <?php the_title('<h2 class="result-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>

                            <div class="result-excerpt">
                                <?php the_excerpt(); ?>
                            </div>

                            <div class="result-meta">
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
                'prev_text' => __('← Previous', 'humanitarianblog'),
                'next_text' => __('Next →', 'humanitarianblog'),
            ));
            ?>

        <?php else : ?>

            <div class="no-results">
                <h2><?php _e('Nothing Found', 'humanitarianblog'); ?></h2>
                <p><?php _e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'humanitarianblog'); ?></p>

                <?php get_search_form(); ?>
            </div>

        <?php endif; ?>

    </div>

</main>

<?php
get_footer();
