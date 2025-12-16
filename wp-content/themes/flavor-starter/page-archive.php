<?php
/**
 * Template Name: Archive
 * Archive page with date/category/tag filtering
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main archive-page">

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="page-hero-content">
                <span class="section-badge"><?php _e('ARCHIVE', 'humanitarianblog'); ?></span>
                <h1><?php _e('Article Archive', 'humanitarianblog'); ?></h1>
                <p class="page-hero-lead"><?php _e('Browse our complete collection of articles by date, category, or topic.', 'humanitarianblog'); ?></p>
            </div>
        </div>
    </section>

    <!-- Archive Filters -->
    <section class="archive-filters">
        <div class="container">
            <div class="filters-wrapper">
                <!-- Year Filter -->
                <div class="filter-group">
                    <label for="archive-year"><?php _e('Year', 'humanitarianblog'); ?></label>
                    <select id="archive-year" class="archive-filter">
                        <option value=""><?php _e('All Years', 'humanitarianblog'); ?></option>
                        <?php
                        $years = $wpdb->get_col("SELECT DISTINCT YEAR(post_date) FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_type = 'post' ORDER BY post_date DESC");
                        foreach ($years as $year) :
                        ?>
                            <option value="<?php echo esc_attr($year); ?>"><?php echo esc_html($year); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Month Filter -->
                <div class="filter-group">
                    <label for="archive-month"><?php _e('Month', 'humanitarianblog'); ?></label>
                    <select id="archive-month" class="archive-filter">
                        <option value=""><?php _e('All Months', 'humanitarianblog'); ?></option>
                        <?php for ($i = 1; $i <= 12; $i++) : ?>
                            <option value="<?php echo esc_attr($i); ?>"><?php echo esc_html(date_i18n('F', mktime(0, 0, 0, $i, 1))); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <!-- Category Filter -->
                <div class="filter-group">
                    <label for="archive-category"><?php _e('Category', 'humanitarianblog'); ?></label>
                    <select id="archive-category" class="archive-filter">
                        <option value=""><?php _e('All Categories', 'humanitarianblog'); ?></option>
                        <?php
                        $categories = get_categories(array('hide_empty' => true));
                        foreach ($categories as $category) :
                        ?>
                            <option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?> (<?php echo $category->count; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Article Type Filter -->
                <div class="filter-group">
                    <label for="archive-type"><?php _e('Article Type', 'humanitarianblog'); ?></label>
                    <select id="archive-type" class="archive-filter">
                        <option value=""><?php _e('All Types', 'humanitarianblog'); ?></option>
                        <?php
                        $article_types = get_terms(array('taxonomy' => 'article_type', 'hide_empty' => true));
                        if (!is_wp_error($article_types)) :
                            foreach ($article_types as $type) :
                        ?>
                            <option value="<?php echo esc_attr($type->term_id); ?>"><?php echo esc_html($type->name); ?> (<?php echo $type->count; ?>)</option>
                        <?php
                            endforeach;
                        endif;
                        ?>
                    </select>
                </div>

                <button type="button" id="archive-filter-btn" class="btn btn-primary"><?php _e('Filter', 'humanitarianblog'); ?></button>
                <button type="button" id="archive-reset-btn" class="btn btn-outline"><?php _e('Reset', 'humanitarianblog'); ?></button>
            </div>
        </div>
    </section>

    <!-- Archive by Year -->
    <section class="archive-content">
        <div class="container">
            <div class="archive-results" id="archive-results">
                <?php
                // Get posts grouped by year
                $current_year = null;
                $archive_query = new WP_Query(array(
                    'posts_per_page' => -1,
                    'post_status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));

                if ($archive_query->have_posts()) :
                    while ($archive_query->have_posts()) : $archive_query->the_post();
                        $post_year = get_the_date('Y');

                        if ($current_year !== $post_year) :
                            if ($current_year !== null) :
                                echo '</div></div>'; // Close previous year
                            endif;
                            $current_year = $post_year;
                ?>
                            <div class="archive-year-section">
                                <h2 class="archive-year-title"><?php echo esc_html($post_year); ?></h2>
                                <div class="archive-year-posts">
                <?php endif; ?>

                                    <article class="archive-post">
                                        <time datetime="<?php echo get_the_date('c'); ?>" class="archive-post-date">
                                            <span class="date-day"><?php echo get_the_date('d'); ?></span>
                                            <span class="date-month"><?php echo get_the_date('M'); ?></span>
                                        </time>
                                        <div class="archive-post-content">
                                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                            <div class="archive-post-meta">
                                                <?php
                                                $categories = get_the_category();
                                                if (!empty($categories)) :
                                                ?>
                                                    <span class="meta-category"><?php echo esc_html($categories[0]->name); ?></span>
                                                <?php endif; ?>
                                                <span class="meta-author"><?php the_author(); ?></span>
                                            </div>
                                        </div>
                                    </article>

                <?php
                    endwhile;
                    echo '</div></div>'; // Close last year
                    wp_reset_postdata();
                else :
                ?>
                    <p class="no-posts"><?php _e('No articles found.', 'humanitarianblog'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Archive Stats -->
    <section class="archive-stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <span class="stat-number"><?php echo wp_count_posts()->publish; ?></span>
                    <span class="stat-label"><?php _e('Total Articles', 'humanitarianblog'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo count(get_categories(array('hide_empty' => true))); ?></span>
                    <span class="stat-label"><?php _e('Categories', 'humanitarianblog'); ?></span>
                </div>
                <div class="stat-item">
                    <?php
                    $first_post = get_posts(array('numberposts' => 1, 'order' => 'ASC'));
                    $years_active = $first_post ? (date('Y') - get_the_date('Y', $first_post[0])) + 1 : 1;
                    ?>
                    <span class="stat-number"><?php echo $years_active; ?></span>
                    <span class="stat-label"><?php _e('Years of Coverage', 'humanitarianblog'); ?></span>
                </div>
                <div class="stat-item">
                    <span class="stat-number"><?php echo count(get_users(array('has_published_posts' => true))); ?></span>
                    <span class="stat-label"><?php _e('Contributors', 'humanitarianblog'); ?></span>
                </div>
            </div>
        </div>
    </section>

</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtn = document.getElementById('archive-filter-btn');
    const resetBtn = document.getElementById('archive-reset-btn');

    if (filterBtn) {
        filterBtn.addEventListener('click', function() {
            const year = document.getElementById('archive-year').value;
            const month = document.getElementById('archive-month').value;
            const category = document.getElementById('archive-category').value;
            const type = document.getElementById('archive-type').value;

            let url = '<?php echo home_url(); ?>';

            if (year && month) {
                url += '/' + year + '/' + month.padStart(2, '0') + '/';
            } else if (year) {
                url += '/' + year + '/';
            }

            if (category) {
                url = '<?php echo home_url('/category/'); ?>' + category + '/';
            }

            if (url !== '<?php echo home_url(); ?>') {
                window.location.href = url;
            }
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            document.querySelectorAll('.archive-filter').forEach(function(select) {
                select.selectedIndex = 0;
            });
        });
    }
});
</script>

<?php
get_footer();
