<?php
/**
 * Template part for displaying opinion article card
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>

<article <?php post_class('article-card article-opinion'); ?>>
	<div class="opinion-author-avatar">
		<?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
	</div>

	<div class="opinion-content">
		<?php
		// Opinion badge
		?>
		<span class="category-badge category-opinion">
			<?php _e('Opinion', 'humanitarianblog'); ?>
		</span>

		<?php the_title('<h3 class="opinion-title"><a href="' . esc_url(get_permalink()) . '">', '</a></h3>'); ?>

		<?php if (has_excerpt()) : ?>
			<div class="opinion-excerpt">
				<?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?>
			</div>
		<?php endif; ?>

		<div class="opinion-meta">
			<span class="author-name">
				<a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
					<?php the_author(); ?>
				</a>
			</span>
			<?php
			// Author title/role if exists
			$author_title = get_the_author_meta('user_title');
			if ($author_title) :
				?>
				<span class="author-title"><?php echo esc_html($author_title); ?></span>
			<?php endif; ?>
			<span class="date"><?php echo get_the_date(); ?></span>
		</div>
	</div>
</article>
