<?php
/**
 * Template part for displaying standard article card
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Get article type for visual differentiation
$article_type = humanitarianblog_get_article_type();
$type_attr = $article_type ? ' data-article-type="' . esc_attr($article_type['slug']) . '"' : '';
$is_breaking = humanitarianblog_is_breaking();
?>

<article <?php post_class('article-card'); ?><?php echo $type_attr; ?>>
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
		<?php if ($is_breaking) : ?>
			<span class="breaking-indicator"><?php _e('Breaking', 'humanitarianblog'); ?></span>
		<?php endif; ?>

		<?php if ($article_type && !$is_breaking) : ?>
			<a href="<?php echo esc_url($article_type['url']); ?>" class="category-badge category-badge--<?php echo esc_attr($article_type['color']); ?>">
				<?php echo esc_html($article_type['name']); ?>
			</a>
		<?php endif; ?>

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
