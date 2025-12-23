<?php
/**
 * Single Post Content Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>

    <!-- Hero Image with Title Overlay -->
    <?php if (has_post_thumbnail()) : ?>
    <div class="single-post__hero">
        <div class="single-post__hero-image">
            <?php the_post_thumbnail('full'); ?>
            <div class="single-post__hero-overlay"></div>
        </div>
        <div class="single-post__hero-content">
            <div class="single-post__badge">
                <?php humanitarian_category_badge(); ?>
            </div>
            <h1 class="single-post__title"><?php the_title(); ?></h1>
        </div>
    </div>
    <?php else : ?>
    <!-- Post Header (no image) -->
    <header class="single-post__header single-post__header--no-image">
        <div class="single-post__badge">
            <?php humanitarian_category_badge(); ?>
        </div>
        <h1 class="single-post__title"><?php the_title(); ?></h1>
    </header>
    <?php endif; ?>

    <!-- Post Meta -->
    <div class="single-post__meta-wrapper">
        <div class="single-post__meta">
            <div class="single-post__author">
                <div class="single-post__author-avatar">
                    <?php echo get_avatar(get_the_author_meta('ID'), 40); ?>
                </div>
                <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="single-post__author-name">
                    <?php the_author(); ?>
                </a>
            </div>
            <span class="single-post__meta-separator">&bull;</span>
            <time datetime="<?php echo esc_attr(get_the_date(DATE_W3C)); ?>">
                <?php echo esc_html(get_the_date()); ?>
            </time>
            <span class="single-post__meta-separator">&bull;</span>
            <span><?php echo esc_html(humanitarian_reading_time()); ?></span>
        </div>

        <!-- Article Action Buttons: PDF, Voice, QR -->
        <div class="single-post__actions">
            <button type="button" id="action-pdf-btn" class="single-post__action-btn" title="<?php esc_attr_e('Download as PDF', 'humanitarian'); ?>">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11v6m-3-3h6"/>
                </svg>
                <span><?php esc_html_e('PDF', 'humanitarian'); ?></span>
            </button>
            <button type="button" id="action-voice-btn" class="single-post__action-btn" title="<?php esc_attr_e('Listen to Article', 'humanitarian'); ?>">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072M18.364 5.636a9 9 0 010 12.728M12 12h.01M8 9l-5 3 5 3V9z"/>
                </svg>
                <span><?php esc_html_e('Voice Article', 'humanitarian'); ?></span>
            </button>
            <button type="button" id="action-qr-btn" class="single-post__action-btn" title="<?php esc_attr_e('Show QR Code', 'humanitarian'); ?>">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h6v6H3zM15 3h6v6h-6zM3 15h6v6H3zM15 15h3v3h-3zM18 18h3v3h-3zM15 21h3M21 15v3"/>
                </svg>
                <span><?php esc_html_e('QR', 'humanitarian'); ?></span>
            </button>
        </div>

        <!-- Voice Player Controls (shows when Voice Article is clicked) -->
        <div id="voice-player" class="voice-player">
            <button type="button" id="voice-play-pause" class="voice-player__btn" title="<?php esc_attr_e('Play/Pause', 'humanitarian'); ?>">
                <svg class="icon-play" width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"/>
                </svg>
                <svg class="icon-pause" width="16" height="16" fill="currentColor" viewBox="0 0 24 24" style="display:none;">
                    <path d="M6 4h4v16H6zM14 4h4v16h-4z"/>
                </svg>
            </button>
            <button type="button" id="voice-stop" class="voice-player__btn" title="<?php esc_attr_e('Stop', 'humanitarian'); ?>">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <rect x="6" y="6" width="12" height="12"/>
                </svg>
            </button>
            <span class="voice-player__label"><?php esc_html_e('Speed', 'humanitarian'); ?>:</span>
            <select id="voice-speed" class="voice-player__speed">
                <option value="0.75">0.75x</option>
                <option value="1" selected>1x</option>
                <option value="1.25">1.25x</option>
                <option value="1.5">1.5x</option>
                <option value="2">2x</option>
            </select>
        </div>
    </div>

    <!-- Post Content -->
    <div class="single-post__content">
        <div class="entry-content">
            <?php
            the_content();

            wp_link_pages(array(
                'before' => '<div class="page-links">' . esc_html__('Pages:', 'humanitarian'),
                'after'  => '</div>',
            ));
            ?>
        </div>

        <!-- Tags -->
        <?php
        $tags = get_the_tags();
        if ($tags) :
        ?>
        <div class="post-tags">
            <span class="post-tags__label"><?php esc_html_e('Tags:', 'humanitarian'); ?></span>
            <?php foreach ($tags as $tag) : ?>
            <a href="<?php echo esc_url(get_tag_link($tag->term_id)); ?>" class="post-tags__link"><?php echo esc_html($tag->name); ?></a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Social Share -->
        <?php $share_urls = humanitarian_get_share_urls(); ?>
        <div class="social-share">
            <span class="social-share__label"><?php esc_html_e('Share', 'humanitarian'); ?></span>
            <div class="social-share__buttons">
                <a href="<?php echo esc_url($share_urls['twitter']); ?>" class="social-share__button social-share__button--twitter" target="_blank" rel="noopener noreferrer" aria-label="Share on Twitter">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="<?php echo esc_url($share_urls['facebook']); ?>" class="social-share__button social-share__button--facebook" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="<?php echo esc_url($share_urls['linkedin']); ?>" class="social-share__button social-share__button--linkedin" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <a href="<?php echo esc_url($share_urls['email']); ?>" class="social-share__button social-share__button--email" aria-label="Share via Email">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                </a>
                <button class="social-share__button social-share__button--copy" onclick="copyToClipboard()" aria-label="Copy link">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Author Box (LinkedIn Style) -->
    <?php
    $author_id = get_the_author_meta('ID');
    $author_info = humanitarian_get_author_info($author_id);
    ?>
    <div class="author-box author-box--linkedin">
        <div class="author-box__avatar">
            <?php echo get_avatar($author_id, 100); ?>
        </div>
        <div class="author-box__info">
            <h4 class="author-box__name">
                <?php the_author(); ?>
                <?php if (!empty($author_info['linkedin_url'])) : ?>
                <a href="<?php echo esc_url($author_info['linkedin_url']); ?>" class="author-box__linkedin" target="_blank" rel="noopener noreferrer" title="<?php esc_attr_e('View LinkedIn Profile', 'humanitarian'); ?>">
                    <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
                <?php endif; ?>
            </h4>
            <?php if (!empty($author_info['profession'])) : ?>
            <p class="author-box__profession"><?php echo esc_html($author_info['profession']); ?></p>
            <?php endif; ?>
            <?php if (!empty($author_info['experience_years']) && !empty($author_info['experience_field'])) : ?>
            <p class="author-box__experience">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <?php
                printf(
                    esc_html__('%d+ years in %s', 'humanitarian'),
                    intval($author_info['experience_years']),
                    esc_html($author_info['experience_field'])
                );
                ?>
            </p>
            <?php endif; ?>
            <?php if (get_the_author_meta('description')) : ?>
            <p class="author-box__bio"><?php echo esc_html(get_the_author_meta('description')); ?></p>
            <?php endif; ?>
            <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="author-box__link">
                <?php esc_html_e('View all posts', 'humanitarian'); ?> &rarr;
            </a>
        </div>
    </div>

</article>
