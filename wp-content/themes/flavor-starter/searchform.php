<?php
/**
 * Search form template
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field" class="screen-reader-text"><?php _e('Search for:', 'flavor-starter'); ?></label>
    <div class="search-form-inner">
        <input
            type="search"
            id="search-field"
            class="search-field"
            placeholder="<?php esc_attr_e('Search articles...', 'flavor-starter'); ?>"
            value="<?php echo get_search_query(); ?>"
            name="s"
            required
        />
        <button type="submit" class="search-submit btn btn-primary">
            <span class="screen-reader-text"><?php _e('Search', 'flavor-starter'); ?></span>
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM19 19l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
    </div>
</form>
