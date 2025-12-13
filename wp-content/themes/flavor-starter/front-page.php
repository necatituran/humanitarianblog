<?php
/**
 * Template Name: Homepage
 * The front page template file
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main homepage">

    <?php
    // Hero Section - Sticky Posts
    $sticky = get_option('sticky_posts');
    if (!empty($sticky)) {
        $hero_query = new WP_Query(array(
            'post__in'       => array_slice($sticky, 0, 3),
            'posts_per_page' => 3,
        ));

        if ($hero_query->have_posts()) :
            ?>
            <section class="hero-section">
                <div class="container">
                    <?php while ($hero_query->have_posts()) : $hero_query->the_post(); ?>
                        <article <?php post_class('hero-article'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="hero-thumbnail">
                                    <?php the_post_thumbnail('hero-large'); ?>
                                </div>
                            <?php endif; ?>
                            <div class="hero-content">
                                <header class="entry-header">
                                    <?php the_category(); ?>
                                    <?php the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>
                                    <?php if (has_excerpt()) : ?>
                                        <div class="entry-excerpt"><?php the_excerpt(); ?></div>
                                    <?php endif; ?>
                                    <div class="entry-meta">
                                        <span class="author"><?php the_author(); ?></span>
                                        <span class="date"><?php echo get_the_date(); ?></span>
                                    </div>
                                </header>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </section>
            <?php
            wp_reset_postdata();
        endif;
    }
    ?>

    <?php
    // Current Coverage Section
    $current_coverage = new WP_Query(array(
        'posts_per_page' => 6,
        'post__not_in'   => $sticky,
    ));

    if ($current_coverage->have_posts()) :
        ?>
        <section class="current-coverage-section pt-12 pb-12">
            <div class="container">
                <header class="section-header">
                    <h2><?php _e('Current Coverage', 'flavor-starter'); ?></h2>
                    <p><?php _e('Global Emergencies & Updates', 'flavor-starter'); ?></p>
                </header>

                <div class="grid grid-cols-3">
                    <?php while ($current_coverage->have_posts()) : $current_coverage->the_post(); ?>
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
                                <?php the_title('<h3 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
                                <div class="card-meta">
                                    <span class="date"><?php echo get_the_date(); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    endif;
    ?>

    <?php
    // Opinions Section
    $opinions = new WP_Query(array(
        'posts_per_page' => 3,
        'tax_query'      => array(
            array(
                'taxonomy' => 'article_type',
                'field'    => 'slug',
                'terms'    => 'opinion',
            ),
        ),
    ));

    if ($opinions->have_posts()) :
        ?>
        <section class="opinions-section bg-light pt-12 pb-12">
            <div class="container">
                <header class="section-header">
                    <h2><?php _e('Opinions', 'flavor-starter'); ?></h2>
                </header>

                <div class="opinions-grid">
                    <?php while ($opinions->have_posts()) : $opinions->the_post(); ?>
                        <article <?php post_class('opinion-card'); ?>>
                            <div class="opinion-content">
                                <?php the_title('<h3 class="opinion-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
                                <div class="opinion-author">
                                    <span class="author-name"><?php the_author(); ?></span>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </div>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    endif;
    ?>

</main>

<?php
get_footer();
