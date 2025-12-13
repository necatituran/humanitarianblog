<?php
/**
 * Template part for displaying featured/hero article card
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>

<article <?php post_class('article-card article-featured'); ?>>
	<?php if (has_post_thumbnail()) : ?>
		<div class="featured-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('hero-large'); ?>
			</a>

			<div class="featured-overlay">
				<?php
				// Category badge
				$categories = get_the_category();
				if (!empty($categories)) :
					$primary_cat = $categories[0];
					$cat_slug = $primary_cat->slug;
					?>
					<span class="category-badge category-<?php echo esc_attr($cat_slug); ?>">
						<?php echo esc_html($primary_cat->name); ?>
					</span>
				<?php endif; ?>

				<?php the_title('<h2 class="featured-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>

				<?php if (has_excerpt()) : ?>
					<div class="featured-excerpt">
						<?php echo wp_trim_words(get_the_excerpt(), 25, '...'); ?>
					</div>
				<?php endif; ?>

				<div class="featured-meta">
					<span class="author">
						<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
							<?php the_author(); ?>
						</a>
					</span>
					<span class="date"><?php echo get_the_date(); ?></span>
				</div>
			</div>
		</div>
	<?php else : ?>
		<!-- Fallback for posts without featured image -->
		<div class="featured-content-only">
			<?php
			$categories = get_the_category();
			if (!empty($categories)) :
				$primary_cat = $categories[0];
				$cat_slug = $primary_cat->slug;
				?>
				<span class="category-badge category-<?php echo esc_attr($cat_slug); ?>">
					<?php echo esc_html($primary_cat->name); ?>
				</span>
			<?php endif; ?>

			<?php the_title('<h2 class="featured-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h2>'); ?>

			<?php if (has_excerpt()) : ?>
				<div class="featured-excerpt">
					<?php the_excerpt(); ?>
				</div>
			<?php endif; ?>

			<div class="featured-meta">
				<span class="author">
					<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
						<?php the_author(); ?>
					</a>
				</span>
				<span class="date"><?php echo get_the_date(); ?></span>
			</div>
		</div>
	<?php endif; ?>
</article>
