<?php
/**
 * The template for displaying all pages - Editorial Style
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main page-template">

    <!-- Page Header -->
    <header class="page-header">
        <div class="container">
            <div class="page-header-content">
                <?php the_title('<h1 class="page-title">', '</h1>'); ?>
                <?php if (has_excerpt()) : ?>
                    <div class="page-subtitle">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="page-content-wrapper">
        <div class="container">
            <?php
            while (have_posts()) :
                the_post();
                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class('page-article'); ?>>

                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="page-featured-image">
                            <?php the_post_thumbnail('hero-large'); ?>
                            <?php
                            $caption = get_the_post_thumbnail_caption();
                            if ($caption) :
                                ?>
                                <figcaption class="image-caption"><?php echo esc_html($caption); ?></figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>

                    <div class="page-content">
                        <?php
                        the_content();

                        wp_link_pages([
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'humanitarianblog'),
                            'after'  => '</div>',
                        ]);
                        ?>
                    </div>

                </article>

                <?php
                // If comments are open or we have at least one comment
                if (comments_open() || get_comments_number()) :
                    ?>
                    <section class="page-comments">
                        <?php comments_template(); ?>
                    </section>
                <?php endif;

            endwhile;
            ?>
        </div>
    </div>

</main>

<?php
get_footer();
