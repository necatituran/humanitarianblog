<?php
/**
 * Template part for displaying newsletter signup form
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>

<div class="newsletter-form">
	<div class="newsletter-content">
		<h3 class="newsletter-title">
			<?php _e('Stay Informed', 'humanitarianblog'); ?>
		</h3>
		<p class="newsletter-description">
			<?php _e('Get our latest humanitarian journalism delivered to your inbox.', 'humanitarianblog'); ?>
		</p>
	</div>

	<form class="newsletter-form-inner" method="post" action="#" id="newsletter-signup">
		<?php wp_nonce_field('newsletter_signup', 'newsletter_nonce'); ?>

		<div class="form-group">
			<label for="newsletter-email" class="screen-reader-text">
				<?php _e('Email address', 'humanitarianblog'); ?>
			</label>
			<input
				type="email"
				id="newsletter-email"
				name="newsletter_email"
				class="newsletter-input"
				placeholder="<?php esc_attr_e('Enter your email', 'humanitarianblog'); ?>"
				required
				aria-required="true"
			/>
		</div>

		<div class="form-group frequency-group">
			<p class="frequency-label"><?php _e('How often?', 'humanitarianblog'); ?></p>
			<div class="frequency-options">
				<label class="frequency-option">
					<input
						type="radio"
						name="newsletter_frequency"
						value="daily"
						id="frequency-daily"
					/>
					<span><?php _e('Daily', 'humanitarianblog'); ?></span>
				</label>

				<label class="frequency-option">
					<input
						type="radio"
						name="newsletter_frequency"
						value="weekly"
						id="frequency-weekly"
						checked
					/>
					<span><?php _e('Weekly', 'humanitarianblog'); ?></span>
				</label>

				<label class="frequency-option">
					<input
						type="radio"
						name="newsletter_frequency"
						value="monthly"
						id="frequency-monthly"
					/>
					<span><?php _e('Monthly', 'humanitarianblog'); ?></span>
				</label>
			</div>
		</div>

		<div class="form-group">
			<label class="privacy-checkbox">
				<input
					type="checkbox"
					name="newsletter_privacy"
					id="newsletter-privacy"
					required
					aria-required="true"
				/>
				<span>
					<?php
					printf(
						__('I agree to the %1$sPrivacy Policy%2$s', 'humanitarianblog'),
						'<a href="' . esc_url(get_privacy_policy_url()) . '" target="_blank">',
						'</a>'
					);
					?>
				</span>
			</label>
		</div>

		<button type="submit" class="btn btn-primary newsletter-submit">
			<?php _e('Subscribe', 'humanitarianblog'); ?>
			<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<polyline points="9 18 15 12 9 6"></polyline>
			</svg>
		</button>

		<div class="newsletter-message" id="newsletter-message" style="display: none;"></div>
	</form>
</div>
