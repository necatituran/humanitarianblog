<?php
/**
 * The template for displaying archive pages (Category, Tag, etc.)
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main archive-page">

    <!-- Archive Header -->
    <header class="archive-header">
        <div class="container">
            <?php
            // Get archive info
            $archive_title = '';
            $archive_description = '';

            if (is_category()) {
                $archive_title = single_cat_title('', false);
                $archive_description = category_description();
            } elseif (is_tag()) {
                $archive_title = single_tag_title('', false);
                $archive_description = tag_description();
            } elseif (is_author()) {
                $archive_title = get_the_author();
                $archive_description = get_the_author_meta('description');
            } elseif (is_post_type_archive()) {
                $archive_title = post_type_archive_title('', false);
            } elseif (is_tax()) {
                $archive_title = single_term_title('', false);
                $archive_description = term_description();
            } else {
                $archive_title = __('Archives', 'humanitarianblog');
            }
            ?>

            <div class="archive-header-content">
                <?php if (is_category()) : ?>
                    <span class="archive-label"><?php _e('Category', 'humanitarianblog'); ?></span>
                <?php elseif (is_tag()) : ?>
                    <span class="archive-label"><?php _e('Tag', 'humanitarianblog'); ?></span>
                <?php endif; ?>

                <h1 class="archive-title"><?php echo esc_html($archive_title); ?></h1>

                <?php if ($archive_description) : ?>
                    <div class="archive-description">
                        <?php echo wp_kses_post($archive_description); ?>
                    </div>
                <?php endif; ?>

                <div class="archive-meta">
                    <?php
                    global $wp_query;
                    $total_posts = $wp_query->found_posts;
                    ?>
                    <span class="post-count">
                        <?php printf(_n('%s Article', '%s Articles', $total_posts, 'humanitarianblog'), number_format_i18n($total_posts)); ?>
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Archive Content -->
    <section class="archive-content">
        <div class="container">
            <?php if (have_posts()) : ?>

                <div class="articles-grid">
                    <?php while (have_posts()) : the_post(); ?>

                        <article <?php post_class('article-card'); ?>>
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="card-thumbnail">
                                    <?php the_post_thumbnail('card-medium'); ?>
                                </a>
                            <?php endif; ?>

                            <div class="card-content">
                                <?php
                                // Category badge (clickable) - only show if not on category archive
                                if (!is_category()) :
                                    $categories = get_the_category();
                                    if (!empty($categories)) :
                                        $cat_colors = ['category-badge--red', 'category-badge--blue', 'category-badge--green', 'category-badge--orange'];
                                        $cat_color = $cat_colors[array_rand($cat_colors)];
                                        ?>
                                        <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="category-badge <?php echo esc_attr($cat_color); ?>">
                                            <?php echo esc_html($categories[0]->name); ?>
                                        </a>
                                    <?php endif;
                                endif;
                                ?>

                                <?php the_title('<h3 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>

                                <?php if (has_excerpt()) : ?>
                                    <div class="card-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="card-meta">
                                    <span class="author"><?php the_author(); ?></span>
                                    <span class="separator">·</span>
                                    <span class="date"><?php echo get_the_date(); ?></span>
                                    <span class="separator">·</span>
                                    <span class="read-time"><?php echo humanitarianblog_reading_time(); ?></span>
                                </div>
                            </div>
                        </article>

                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <nav class="archive-pagination">
                    <?php
                    the_posts_pagination([
                        'mid_size'  => 2,
                        'prev_text' => '← ' . __('Previous', 'humanitarianblog'),
                        'next_text' => __('Next', 'humanitarianblog') . ' →',
                    ]);
                    ?>
                </nav>

            <?php else : ?>

                <div class="no-posts">
                    <p><?php _e('No articles found in this category.', 'humanitarianblog'); ?></p>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                        <?php _e('Back to Homepage', 'humanitarianblog'); ?>
                    </a>
                </div>

            <?php endif; ?>
        </div>
    </section>

</main>

<?php
get_footer();
