<?php
/**
 * Template Name: Homepage
 * The front page template file - Editorial Magazine Style
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();

// Get featured post for hero (most recent or sticky)
$sticky = get_option('sticky_posts');
$hero_post_id = null;

if (!empty($sticky)) {
    $hero_post_id = $sticky[0];
} else {
    $latest = get_posts(['numberposts' => 1]);
    if ($latest) {
        $hero_post_id = $latest[0]->ID;
    }
}
?>

<main id="primary" class="site-main homepage">

    <?php
    // ==========================================================================
    // NEW HERO SECTION - Diagonal Split Design
    // ==========================================================================
    ?>
    <section class="hero-banner">
        <div class="hero-banner-inner">
            <!-- Left Side - Slogan -->
            <div class="hero-slogan">
                <!-- Decorative background elements -->
                <div class="hero-slogan-bg-pattern"></div>
                <div class="hero-slogan-accent"></div>

                <div class="hero-slogan-content">
                    <!-- Top Badge -->
                    <div class="hero-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/>
                        </svg>
                        <span><?php _e('HUMANITARIAN JOURNALISM', 'humanitarianblog'); ?></span>
                    </div>

                    <h1 class="hero-slogan-text">
                        <span class="word-together">TOGETHER</span>
                        <span class="word-we">WE</span>
                        <span class="word-are">ARE</span>
                        <span class="word-strong">STRONG</span>
                    </h1>

                    <!-- Tagline below main text -->
                    <p class="hero-tagline"><?php _e('Illuminating stories from crisis zones, amplifying voices that need to be heard.', 'humanitarianblog'); ?></p>

                    <!-- CTA Button -->
                    <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="hero-cta">
                        <?php _e('Explore Stories', 'humanitarianblog'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/>
                            <path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Diagonal Navy Stripe (between slogan and featured) -->
            <div class="hero-diagonal"></div>

            <!-- Right Side - Featured Post -->
            <div class="hero-featured">
                <?php
                if ($hero_post_id) :
                    $hero_post = get_post($hero_post_id);
                    setup_postdata($hero_post);
                    $thumbnail_url = get_the_post_thumbnail_url($hero_post_id, 'hero-large');
                    ?>
                    <a href="<?php echo get_permalink($hero_post_id); ?>" class="hero-featured-link" <?php if ($thumbnail_url) : ?>style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');"<?php endif; ?>>
                        <div class="hero-featured-overlay"></div>
                        <div class="hero-featured-content">
                            <?php
                            $categories = get_the_category($hero_post_id);
                            if (!empty($categories)) :
                                ?>
                                <span class="hero-category"><?php echo esc_html($categories[0]->name); ?></span>
                            <?php endif; ?>
                            <h2 class="hero-featured-title"><?php echo get_the_title($hero_post_id); ?></h2>
                            <div class="hero-featured-meta">
                                <span><?php echo get_the_date('', $hero_post_id); ?></span>
                                <span class="separator">·</span>
                                <span><?php echo humanitarianblog_reading_time(); ?></span>
                            </div>
                        </div>
                    </a>
                    <?php
                    wp_reset_postdata();
                endif;
                ?>
            </div>
        </div>
    </section>

    <?php
    // ==========================================================================
    // CURRENT COVERAGE SECTION WITH LATEST NEWS SIDEBAR
    // ==========================================================================
    $exclude_ids = $hero_post_id ? [$hero_post_id] : [];

    $current_coverage = new WP_Query([
        'posts_per_page' => 6,
        'post__not_in'   => $exclude_ids,
    ]);

    // Latest news for sidebar (most recent by date)
    $latest_news = new WP_Query([
        'posts_per_page' => 8,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);

    if ($current_coverage->have_posts()) :
        ?>
        <section class="current-coverage-section">
            <div class="container">
                <div class="coverage-with-sidebar">
                    <!-- Main Content -->
                    <div class="coverage-main">
                        <header class="section-header">
                            <div>
                                <h2 class="section-title"><?php _e('Current Coverage', 'humanitarianblog'); ?></h2>
                                <p class="section-subtitle"><?php _e('Global Emergencies & Updates', 'humanitarianblog'); ?></p>
                            </div>
                            <a href="<?php echo get_permalink(get_option('page_for_posts')); ?>" class="section-link">
                                <?php _e('View All', 'humanitarianblog'); ?> →
                            </a>
                        </header>

                        <div class="articles-grid">
                            <?php while ($current_coverage->have_posts()) : $current_coverage->the_post(); ?>
                                <article <?php post_class('article-card'); ?>>
                                    <?php if (has_post_thumbnail()) : ?>
                                        <a href="<?php the_permalink(); ?>" class="card-thumbnail">
                                            <?php the_post_thumbnail('card-medium'); ?>
                                            <?php
                                            // Photo caption (foto etiketi) - two lines
                                            $photo_caption = humanitarian_get_photo_caption();
                                            if ($photo_caption) :
                                                $cats = get_the_category();
                                                $cat_name = !empty($cats) ? $cats[0]->name : __('Report', 'humanitarianblog');
                                            ?>
                                                <div class="photo-caption-wrapper">
                                                    <span class="photo-caption"><?php echo esc_html($cat_name); ?></span>
                                                    <span class="photo-caption-line2"><?php echo esc_html($photo_caption); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    <?php endif; ?>
                                    <div class="card-content">
                                        <?php
                                        $categories = get_the_category();
                                        if (!empty($categories)) :
                                            $cat_colors = ['category-badge--red', 'category-badge--blue', 'category-badge--green', 'category-badge--orange'];
                                            $cat_color = $cat_colors[array_rand($cat_colors)];
                                            ?>
                                            <a href="<?php echo esc_url(get_category_link($categories[0]->term_id)); ?>" class="category-badge <?php echo esc_attr($cat_color); ?>">
                                                <?php echo esc_html($categories[0]->name); ?>
                                            </a>
                                        <?php endif; ?>
                                        <?php the_title('<h3 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
                                        <div class="card-meta">
                                            <span class="date"><?php echo get_the_date(); ?></span>
                                            <span class="separator">·</span>
                                            <span class="read-time"><?php echo humanitarianblog_reading_time(); ?></span>
                                        </div>
                                    </div>
                                </article>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <!-- Latest News Sidebar -->
                    <aside class="latest-news-sidebar">
                        <div class="sidebar-header">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/>
                                <path d="M18 14h-8"/><path d="M15 18h-5"/><path d="M10 6h8v4h-8V6Z"/>
                            </svg>
                            <h3><?php _e('Latest News', 'humanitarianblog'); ?></h3>
                        </div>
                        <div class="sidebar-meta">
                            <span class="update-time"><?php _e('Updated:', 'humanitarianblog'); ?> <?php echo date_i18n('j M Y, H:i'); ?></span>
                            <button class="refresh-btn" onclick="location.reload();">
                                <?php _e('Refresh', 'humanitarianblog'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                                    <path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/>
                                    <path d="M16 21h5v-5"/>
                                </svg>
                            </button>
                        </div>
                        <ul class="latest-news-list">
                            <?php
                            if ($latest_news->have_posts()) :
                                while ($latest_news->have_posts()) : $latest_news->the_post();
                                    $cats = get_the_category();
                                    $cat_name = !empty($cats) ? $cats[0]->name : '';
                            ?>
                                <li class="latest-news-item">
                                    <a href="<?php the_permalink(); ?>">
                                        <h4 class="news-title"><?php the_title(); ?></h4>
                                        <div class="news-meta">
                                            <?php if ($cat_name) : ?>
                                                <span class="news-category"><?php echo esc_html($cat_name); ?></span>
                                            <?php endif; ?>
                                            <span class="news-date"><?php echo get_the_date('j M Y'); ?></span>
                                        </div>
                                    </a>
                                </li>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </ul>
                        <div class="sidebar-cta">
                            <p><?php _e('While you\'re here, could you support our work?', 'humanitarianblog'); ?></p>
                            <a href="#newsletter" class="btn btn-amber"><?php _e('Subscribe', 'humanitarianblog'); ?></a>
                        </div>
                    </aside>
                </div>
            </div>
        </section>
        <?php
        wp_reset_postdata();
    endif;
    ?>

    <?php
    // ==========================================================================
    // BRAND STRIP SECTION - Logo + Tagline
    // ==========================================================================
    ?>
    <section class="brand-strip">
        <div class="container">
            <div class="brand-strip-inner">
                <div class="brand-strip-logo">
                    <?php
                    $theme_logo = HUMANITARIAN_THEME_URI . '/assets/images/humanitarian-logo.png';
                    ?>
                    <img src="<?php echo esc_url($theme_logo); ?>" alt="<?php bloginfo('name'); ?>" class="brand-strip-logo-img" />
                </div>
                <div class="brand-strip-text">
                    <p class="brand-strip-tagline"><?php _e('Illuminating stories from conflict zones. Independent journalism for humanitarian awareness.', 'humanitarianblog'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <?php
    // ==========================================================================
    // OPINIONS & COMMENTARY SECTION - Magazine Style
    // ==========================================================================
    $opinions = new WP_Query([
        'posts_per_page' => 3,
        'tax_query'      => [
            [
                'taxonomy' => 'article_type',
                'field'    => 'slug',
                'terms'    => 'opinion',
            ],
        ],
    ]);

    // Fallback if no opinion posts
    if (!$opinions->have_posts()) {
        $opinions = new WP_Query([
            'posts_per_page' => 3,
            'offset'         => 8,
            'post__not_in'   => $exclude_ids,
        ]);
    }

    if ($opinions->have_posts()) :
        ?>
        <section class="opinions-section-v2">
            <div class="container">
                <!-- Section Header -->
                <div class="opinions-header-v2">
                    <div class="opinions-header-left">
                        <span class="section-badge"><?php _e('OPINION', 'humanitarianblog'); ?></span>
                        <h2><?php _e('Voices & Perspectives', 'humanitarianblog'); ?></h2>
                    </div>
                    <a href="<?php echo get_term_link('opinion', 'article_type'); ?>" class="section-view-all">
                        <?php _e('All Opinions', 'humanitarianblog'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                        </svg>
                    </a>
                </div>

                <!-- Opinions Cards -->
                <div class="opinions-cards-v2">
                    <?php while ($opinions->have_posts()) : $opinions->the_post(); ?>
                        <article class="opinion-item-v2">
                            <div class="opinion-quote">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M11.192 15.757c0-.88-.23-1.618-.69-2.217-.326-.412-.768-.683-1.327-.812-.55-.128-1.07-.137-1.54-.028-.16-.95.1-1.956.76-3.022.66-1.065 1.515-1.867 2.558-2.403L9.373 5c-.8.396-1.56.898-2.26 1.505-.71.607-1.34 1.305-1.9 2.094s-.98 1.68-1.25 2.69-.346 2.04-.217 3.1c.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368l.002.004zm9.124 0c0-.88-.23-1.618-.69-2.217-.326-.42-.768-.695-1.327-.825-.55-.13-1.07-.14-1.54-.03-.16-.94.09-1.95.75-3.02.66-1.06 1.514-1.86 2.557-2.4L18.49 5c-.8.396-1.555.898-2.26 1.505-.708.607-1.34 1.305-1.894 2.094-.556.79-.97 1.68-1.24 2.69-.273 1-.345 2.04-.217 3.1.168 1.4.62 2.52 1.356 3.35.735.84 1.652 1.26 2.748 1.26.965 0 1.766-.29 2.4-.878.628-.576.94-1.365.94-2.368h-.007z"/>
                                </svg>
                            </div>
                            <div class="opinion-body">
                                <?php the_title('<h3><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
                                <p class="opinion-excerpt-v2"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                            </div>
                            <div class="opinion-meta-v2">
                                <div class="opinion-author-v2">
                                    <?php echo get_avatar(get_the_author_meta('ID'), 48); ?>
                                    <div>
                                        <strong><?php the_author(); ?></strong>
                                        <span><?php echo get_the_date(); ?></span>
                                    </div>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="opinion-arrow" aria-label="<?php esc_attr_e('Read more', 'humanitarianblog'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                                    </svg>
                                </a>
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
    // ==========================================================================
    // NEWSLETTER SECTION
    // ==========================================================================
    ?>
    <section class="newsletter-section" id="newsletter">
        <div class="container">
            <div class="newsletter-inner">
                <div class="newsletter-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="20" height="16" x="2" y="4" rx="2"/>
                        <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
                    </svg>
                </div>
                <div class="newsletter-content">
                    <h2 class="newsletter-title"><?php _e('Stay Informed', 'humanitarianblog'); ?></h2>
                    <p class="newsletter-description"><?php _e('Get weekly updates on humanitarian crises and in-depth analysis delivered to your inbox.', 'humanitarianblog'); ?></p>
                </div>
                <form class="newsletter-form" action="#" method="post">
                    <input type="email" name="email" placeholder="<?php esc_attr_e('Enter your email', 'humanitarianblog'); ?>" required>
                    <button type="submit"><?php _e('Subscribe', 'humanitarianblog'); ?></button>
                </form>
            </div>
        </div>
    </section>

    <?php
    // ==========================================================================
    // EDITORS' PICKS SECTION
    // ==========================================================================
    $editors_picks = new WP_Query([
        'posts_per_page' => 4,
        'meta_key'       => '_editors_pick',
        'meta_value'     => '1',
    ]);

    // Fallback if no editors picks
    if (!$editors_picks->have_posts()) {
        $editors_picks = new WP_Query([
            'posts_per_page' => 4,
            'orderby'        => 'comment_count',
            'order'          => 'DESC',
        ]);
    }

    if ($editors_picks->have_posts()) :
        ?>
        <section class="editors-picks-section">
            <div class="container">
                <header class="section-header">
                    <h2 class="section-title"><?php _e("Editor's Picks", 'humanitarianblog'); ?></h2>
                    <div class="section-line"></div>
                </header>

                <div class="editors-picks-grid">
                    <?php while ($editors_picks->have_posts()) : $editors_picks->the_post(); ?>
                        <article class="editors-pick-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>" class="card-thumbnail">
                                    <?php the_post_thumbnail('card-small'); ?>
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) :
                                        ?>
                                        <span class="category-badge category-badge--dark">
                                            <?php echo esc_html($categories[0]->name); ?>
                                        </span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                            <?php the_title('<h3 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>
                            <a href="<?php the_permalink(); ?>" class="read-more"><?php _e('Read Article', 'humanitarianblog'); ?> →</a>
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
