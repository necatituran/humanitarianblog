<?php
/**
 * Template part for displaying standard article card
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>

<article <?php post_class('article-card'); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<div class="card-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('card-medium'); ?>
			</a>

			<?php
			// Category badge on thumbnail
			$categories = get_the_category();
			if (!empty($categories)) :
				$primary_cat = $categories[0];
				$cat_slug = $primary_cat->slug;
				?>
				<span class="category-badge category-<?php echo esc_attr($cat_slug); ?>">
					<?php echo esc_html($primary_cat->name); ?>
				</span>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="card-content">
		<?php the_title('<h3 class="card-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>

		<?php if (has_excerpt()) : ?>
			<div class="card-excerpt">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>

		<div class="card-meta">
			<span class="author">
				<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
					<?php the_author(); ?>
				</a>
			</span>
			<span class="date"><?php echo get_the_date(); ?></span>
		</div>
	</div>
</article>
