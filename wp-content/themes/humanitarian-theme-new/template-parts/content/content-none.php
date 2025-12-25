<?php
/**
 * No Results Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="no-results">
    <div class="container">
        <h2 class="no-results__title">
            <?php
            if (is_search()) {
                esc_html_e('No results found', 'humanitarian');
            } else {
                esc_html_e('Nothing found', 'humanitarian');
            }
            ?>
        </h2>
        <p class="no-results__text">
            <?php
            if (is_search()) {
                esc_html_e('Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'humanitarian');
            } else {
                esc_html_e('It seems we can\'t find what you\'re looking for. Perhaps searching can help.', 'humanitarian');
            }
            ?>
        </p>
        <div class="no-results__search">
            <?php get_search_form(); ?>
        </div>
    </div>
</section>
