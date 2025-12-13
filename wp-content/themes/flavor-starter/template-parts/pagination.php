<?php
/**
 * Template part for displaying pagination
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

global $wp_query;

// Don't print empty markup if there's only one page
if ($wp_query->max_num_pages < 2) {
	return;
}
?>

<nav class="pagination" aria-label="<?php esc_attr_e('Posts pagination', 'humanitarianblog'); ?>">
	<?php
	$pagination_args = array(
		'mid_size'           => 2,
		'prev_text'          => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"></polyline></svg> ' . __('Previous', 'humanitarianblog'),
		'next_text'          => __('Next', 'humanitarianblog') . ' <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>',
		'type'               => 'list',
		'before_page_number' => '<span class="screen-reader-text">' . __('Page', 'humanitarianblog') . ' </span>',
	);

	the_posts_pagination($pagination_args);
	?>
</nav>
