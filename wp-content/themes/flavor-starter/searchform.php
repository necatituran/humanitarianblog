<?php
/**
 * Search form template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field" class="screen-reader-text"><?php _e('Search for:', 'humanitarianblog'); ?></label>
    <div class="search-form-inner">
        <input
            type="search"
            id="search-field"
            class="search-field"
            placeholder="<?php esc_attr_e('Search articles...', 'humanitarianblog'); ?>"
            value="<?php echo get_search_query(); ?>"
            name="s"
        />
    </div>
    <p class="search-hint"><?php _e('Start typing to search. e.g. "earthquake", "climate", "refugees"', 'humanitarianblog'); ?></p>
</form>
