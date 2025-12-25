<?php
/**
 * Front Page Template
 *
 * The template for displaying the homepage.
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Get hero post to exclude from other queries
$hero_post = humanitarian_get_hero_post();
$exclude_ids = array();
if ($hero_post) {
    $exclude_ids[] = $hero_post->ID;
}

// Get secondary posts to also exclude
$secondary_args = array(
    'post_type'      => 'post',
    'posts_per_page' => 2,
    'post__not_in'   => $exclude_ids,
    'fields'         => 'ids',
);
if (function_exists('humanitarian_add_language_filter')) {
    $secondary_args = humanitarian_add_language_filter($secondary_args);
}
$secondary_query = new WP_Query($secondary_args);
$exclude_ids = array_merge($exclude_ids, $secondary_query->posts);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container hero-section__inner">
        <?php get_template_part('template-parts/hero/hero', 'main'); ?>
        <?php get_template_part('template-parts/hero/hero', 'secondary'); ?>
    </div>
</section>

<!-- Featured Articles Section -->
<?php
$featured_query = humanitarian_get_featured_posts(4);
if ($featured_query->have_posts()) :
?>
<section class="featured-articles">
    <div class="container">
        <div class="featured-articles__header">
            <h2 class="featured-articles__title"><?php esc_html_e('Featured Articles', 'humanitarian'); ?></h2>
            <div class="featured-articles__line"></div>
        </div>

        <div class="featured-articles__grid">
            <?php
            while ($featured_query->have_posts()) : $featured_query->the_post();
                // Add to exclude list
                $exclude_ids[] = get_the_ID();
                get_template_part('template-parts/cards/card', 'grid');
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Current Coverage Section with Recent News Sidebar -->
<section class="current-coverage">
    <div class="container">
        <div class="current-coverage__wrapper">
            <!-- Main Content -->
            <div class="current-coverage__main">
                <?php
                get_template_part('template-parts/sections/section', 'header', array(
                    'title'     => __('Current Coverage', 'humanitarian'),
                    'subtitle'  => __('Global Dispatches & Updates', 'humanitarian'),
                    'link_url'  => get_post_type_archive_link('post'),
                    'link_text' => __('Browse Archives', 'humanitarian'),
                ));
                ?>

                <div class="current-coverage__grid">
                    <?php
                    $coverage_args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 6,
                        'post__not_in'   => $exclude_ids,
                    );
                    if (function_exists('humanitarian_add_language_filter')) {
                        $coverage_args = humanitarian_add_language_filter($coverage_args);
                    }
                    $coverage_query = new WP_Query($coverage_args);

                    if ($coverage_query->have_posts()) :
                        while ($coverage_query->have_posts()) : $coverage_query->the_post();
                            get_template_part('template-parts/cards/card', 'grid');
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>

                <!-- Mobile View All Link -->
                <div class="current-coverage__mobile-link">
                    <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>">
                        <?php esc_html_e('View All Stories', 'humanitarian'); ?>
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Recent News Sidebar -->
            <aside class="recent-news-sidebar">
                <div class="recent-news-sidebar__header">
                    <h3 class="recent-news-sidebar__title"><?php esc_html_e('Recent News', 'humanitarian'); ?></h3>
                    <button type="button" class="recent-news-sidebar__refresh" id="refreshRecentNews" title="<?php esc_attr_e('Refresh', 'humanitarian'); ?>">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </button>
                </div>
                <div class="recent-news-sidebar__list" id="recentNewsList">
                    <?php
                    $recent_args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 5,
                        'post__not_in'   => $exclude_ids,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                    if (function_exists('humanitarian_add_language_filter')) {
                        $recent_args = humanitarian_add_language_filter($recent_args);
                    }
                    $recent_query = new WP_Query($recent_args);

                    if ($recent_query->have_posts()) :
                        while ($recent_query->have_posts()) : $recent_query->the_post();
                            ?>
                            <article class="recent-news-item">
                                <a href="<?php the_permalink(); ?>" class="recent-news-item__link">
                                    <span class="recent-news-item__date">
                                        <?php echo esc_html(get_the_date('M j')); ?>
                                    </span>
                                    <h4 class="recent-news-item__title"><?php the_title(); ?></h4>
                                </a>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </div>
            </aside>
        </div>
    </div>
</section>

<!-- In-Depth Analysis Section -->
<section class="analysis-section">
    <div class="container">
        <div class="analysis-section__header">
            <div>
                <span class="analysis-section__subtitle"><?php esc_html_e('Premium Reportage', 'humanitarian'); ?></span>
                <h2 class="analysis-section__title"><?php esc_html_e('In-Depth Analysis', 'humanitarian'); ?></h2>
            </div>
            <?php
            // Get the in-depth-analysis term link from article_type taxonomy
            $analysis_term = get_term_by('slug', 'in-depth-analysis', 'article_type');
            $analysis_link = $analysis_term ? get_term_link($analysis_term) : get_post_type_archive_link('post');
            ?>
            <a href="<?php echo esc_url($analysis_link); ?>" class="analysis-section__link">
                <?php esc_html_e('View All Reports', 'humanitarian'); ?>
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>

        <div class="analysis-section__grid">
            <?php
            $analysis_query = humanitarian_get_analysis_posts(2);

            if ($analysis_query->have_posts()) :
                while ($analysis_query->have_posts()) : $analysis_query->the_post();
                    get_template_part('template-parts/cards/card', 'analysis');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Editors' Picks Section -->
<section class="editors-picks">
    <div class="container">
        <div class="editors-picks__header">
            <h2 class="editors-picks__title"><?php esc_html_e("Editors' Picks", 'humanitarian'); ?></h2>
            <div class="editors-picks__line"></div>
        </div>

        <div class="editors-picks__grid">
            <?php
            $picks_query = humanitarian_get_editors_picks(8);

            if ($picks_query->have_posts()) :
                while ($picks_query->have_posts()) : $picks_query->the_post();
                    get_template_part('template-parts/cards/card', 'picks');
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<!-- Newsletter CTA -->
<?php get_template_part('template-parts/sections/newsletter', 'cta'); ?>

<?php
get_footer();
?>
