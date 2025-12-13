<?php
/**
 * Template part for displaying search result item
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>

<article <?php post_class('search-result-item'); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<div class="result-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('card-small'); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="result-content">
		<?php
		// Category
		$categories = get_the_category();
		if (!empty($categories)) :
			$primary_cat = $categories[0];
			$cat_slug = $primary_cat->slug;
			?>
			<span class="category-badge category-<?php echo esc_attr($cat_slug); ?>">
				<?php echo esc_html($primary_cat->name); ?>
			</span>
		<?php endif; ?>

		<?php the_title('<h3 class="result-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>

		<div class="result-excerpt">
			<?php
			// Highlight search term in excerpt
			$excerpt = get_the_excerpt();
			$search_query = get_search_query();

			if ($search_query) {
				// Simple highlight: wrap search term with <mark>
				$excerpt = preg_replace(
					'/(' . preg_quote($search_query, '/') . ')/i',
					'<mark>$1</mark>',
					$excerpt
				);
			}

			echo wp_kses_post($excerpt);
			?>
		</div>

		<div class="result-meta">
			<span class="author">
				<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
					<?php the_author(); ?>
				</a>
			</span>
			<span class="date"><?php echo get_the_date(); ?></span>

			<?php
			// Article type if exists
			$article_types = get_the_terms(get_the_ID(), 'article_type');
			if ($article_types && !is_wp_error($article_types)) :
				$type = $article_types[0];
				?>
				<span class="article-type"><?php echo esc_html($type->name); ?></span>
			<?php endif; ?>
		</div>
	</div>
</article>
