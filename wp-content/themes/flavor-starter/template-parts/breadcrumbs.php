<?php
/**
 * Template part for displaying breadcrumbs
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Don't show on homepage
if (is_front_page()) {
	return;
}
?>

<nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', 'humanitarianblog'); ?>">
	<ol class="breadcrumb-list" vocab="https://schema.org/" typeof="BreadcrumbList">
		<!-- Home -->
		<li property="itemListElement" typeof="ListItem">
			<a property="item" typeof="WebPage" href="<?php echo esc_url(home_url('/')); ?>">
				<span property="name"><?php _e('Home', 'humanitarianblog'); ?></span>
			</a>
			<meta property="position" content="1">
		</li>

		<?php
		$position = 2;

		if (is_category() || is_single()) :
			// Category
			$categories = get_the_category();
			if (!empty($categories)) :
				$category = $categories[0];
				?>
				<li property="itemListElement" typeof="ListItem">
					<a property="item" typeof="WebPage" href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
						<span property="name"><?php echo esc_html($category->name); ?></span>
					</a>
					<meta property="position" content="<?php echo esc_attr($position); ?>">
				</li>
				<?php
				$position++;
			endif;
		endif;

		if (is_single()) :
			// Single post title
			?>
			<li property="itemListElement" typeof="ListItem">
				<span property="name"><?php the_title(); ?></span>
				<meta property="position" content="<?php echo esc_attr($position); ?>">
			</li>
		<?php
		elseif (is_tag()) :
			// Tag archive
			?>
			<li property="itemListElement" typeof="ListItem">
				<span property="name"><?php single_tag_title(); ?></span>
				<meta property="position" content="<?php echo esc_attr($position); ?>">
			</li>
		<?php
		elseif (is_author()) :
			// Author archive
			?>
			<li property="itemListElement" typeof="ListItem">
				<span property="name"><?php the_author(); ?></span>
				<meta property="position" content="<?php echo esc_attr($position); ?>">
			</li>
		<?php
		elseif (is_search()) :
			// Search results
			?>
			<li property="itemListElement" typeof="ListItem">
				<span property="name">
					<?php
					printf(
						esc_html__('Search Results for: %s', 'humanitarianblog'),
						'<span>' . get_search_query() . '</span>'
					);
					?>
				</span>
				<meta property="position" content="<?php echo esc_attr($position); ?>">
			</li>
		<?php
		elseif (is_404()) :
			// 404 page
			?>
			<li property="itemListElement" typeof="ListItem">
				<span property="name"><?php _e('Page Not Found', 'humanitarianblog'); ?></span>
				<meta property="position" content="<?php echo esc_attr($position); ?>">
			</li>
		<?php
		elseif (is_archive()) :
			// Other archives
			?>
			<li property="itemListElement" typeof="ListItem">
				<span property="name"><?php the_archive_title(); ?></span>
				<meta property="position" content="<?php echo esc_attr($position); ?>">
			</li>
		<?php
		elseif (is_page()) :
			// Page
			?>
			<li property="itemListElement" typeof="ListItem">
				<span property="name"><?php the_title(); ?></span>
				<meta property="position" content="<?php echo esc_attr($position); ?>">
			</li>
		<?php
		endif;
		?>
	</ol>
</nav>
