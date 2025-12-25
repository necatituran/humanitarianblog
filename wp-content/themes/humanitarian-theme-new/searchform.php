<?php
/**
 * Custom Search Form Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

$unique_id = wp_unique_id('search-form-');
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <div class="search-form__wrapper">
        <label for="<?php echo esc_attr($unique_id); ?>" class="sr-only">
            <?php esc_html_e('Search for:', 'humanitarian'); ?>
        </label>
        <input
            type="search"
            id="<?php echo esc_attr($unique_id); ?>"
            class="search-form__input search-field"
            placeholder="<?php esc_attr_e('Search articles and pages...', 'humanitarian'); ?>"
            value="<?php echo get_search_query(); ?>"
            name="s"
            autocomplete="off"
        >
        <button type="submit" class="search-form__submit">
            <?php esc_html_e('Search', 'humanitarian'); ?>
        </button>
        <!-- Live Search Results Container -->
        <div class="live-search-container"></div>
    </div>
</form>
