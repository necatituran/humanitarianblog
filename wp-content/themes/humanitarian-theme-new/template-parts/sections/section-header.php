<?php
/**
 * Section Header Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 *
 * @param array $args {
 *     @type string $title    Section title
 *     @type string $subtitle Section subtitle (optional)
 *     @type string $link_url Link URL (optional)
 *     @type string $link_text Link text (optional)
 * }
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Get arguments with defaults
$title = isset($args['title']) ? $args['title'] : '';
$subtitle = isset($args['subtitle']) ? $args['subtitle'] : '';
$link_url = isset($args['link_url']) ? $args['link_url'] : '';
$link_text = isset($args['link_text']) ? $args['link_text'] : __('View All', 'humanitarian');

if (!$title) {
    return;
}
?>

<div class="section-header">
    <div class="section-header__top">
        <div class="section-header__title-wrapper">
            <h2 class="section-header__title"><?php echo esc_html($title); ?></h2>
            <?php if ($subtitle) : ?>
            <p class="section-header__subtitle"><?php echo esc_html($subtitle); ?></p>
            <?php endif; ?>
        </div>
        <?php if ($link_url) : ?>
        <a href="<?php echo esc_url($link_url); ?>" class="section-header__link">
            <?php echo esc_html($link_text); ?>
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </a>
        <?php endif; ?>
    </div>
    <div class="section-header__line"></div>
</div>
