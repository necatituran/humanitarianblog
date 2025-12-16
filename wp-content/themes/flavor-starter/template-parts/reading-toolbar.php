<?php
/**
 * Template part for displaying floating reading toolbar
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Check if current post is bookmarked by logged-in user
$is_bookmarked = false;
if (is_user_logged_in()) {
    $current_user_id = get_current_user_id();
    $bookmarks = get_user_meta($current_user_id, 'bookmarked_posts', true);
    if (is_array($bookmarks) && in_array(get_the_ID(), $bookmarks)) {
        $is_bookmarked = true;
    }
}
$bookmark_class = $is_bookmarked ? ' is-bookmarked' : '';
$bookmark_label = $is_bookmarked ? __('Saved', 'humanitarianblog') : __('Save', 'humanitarianblog');
?>

<div class="reading-toolbar" id="reading-toolbar">
	<div class="toolbar-inner">
		<!-- Listen Button (Text-to-Speech) -->
		<button type="button"
		        class="toolbar-button toolbar-listen"
		        id="listen-button"
		        aria-label="<?php esc_attr_e('Listen to article', 'humanitarianblog'); ?>"
		        data-action="listen">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
				<path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path>
			</svg>
			<span class="toolbar-label"><?php _e('Listen', 'humanitarianblog'); ?></span>
		</button>

		<!-- Save Button (Bookmark) -->
		<button type="button"
		        class="toolbar-button toolbar-save<?php echo esc_attr($bookmark_class); ?>"
		        id="save-button"
		        aria-label="<?php esc_attr_e('Save article', 'humanitarianblog'); ?>"
		        data-action="save"
		        data-post-id="<?php echo esc_attr(get_the_ID()); ?>">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
			</svg>
			<span class="toolbar-label"><?php echo esc_html($bookmark_label); ?></span>
		</button>

		<!-- Share Button -->
		<button type="button"
		        class="toolbar-button toolbar-share"
		        id="share-button"
		        aria-label="<?php esc_attr_e('Share article', 'humanitarianblog'); ?>"
		        data-action="share">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<circle cx="18" cy="5" r="3"></circle>
				<circle cx="6" cy="12" r="3"></circle>
				<circle cx="18" cy="19" r="3"></circle>
				<line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
				<line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
			</svg>
			<span class="toolbar-label"><?php _e('Share', 'humanitarianblog'); ?></span>
		</button>

		<!-- PDF Download Button -->
		<button type="button"
		        class="toolbar-button toolbar-pdf"
		        id="pdf-button"
		        aria-label="<?php esc_attr_e('Download PDF', 'humanitarianblog'); ?>"
		        data-action="pdf"
		        data-post-id="<?php echo esc_attr(get_the_ID()); ?>">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
				<polyline points="14 2 14 8 20 8"></polyline>
				<line x1="16" y1="13" x2="8" y2="13"></line>
				<line x1="16" y1="17" x2="8" y2="17"></line>
				<polyline points="10 9 9 9 8 9"></polyline>
			</svg>
			<span class="toolbar-label"><?php _e('PDF', 'humanitarianblog'); ?></span>
		</button>

		<!-- QR Code Button -->
		<button type="button"
		        class="toolbar-button toolbar-qr"
		        id="qr-button"
		        aria-label="<?php esc_attr_e('Generate QR code', 'humanitarianblog'); ?>"
		        data-action="qr"
		        data-post-id="<?php echo esc_attr(get_the_ID()); ?>">
			<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
				<rect x="3" y="3" width="7" height="7"></rect>
				<rect x="14" y="3" width="7" height="7"></rect>
				<rect x="14" y="14" width="7" height="7"></rect>
				<rect x="3" y="14" width="7" height="7"></rect>
			</svg>
			<span class="toolbar-label"><?php _e('QR', 'humanitarianblog'); ?></span>
		</button>
	</div>

	<!-- Audio Player Controls (Hidden by default, shown when listening) -->
	<div class="audio-player" id="audio-player" style="display: none;">
		<div class="audio-controls">
			<button type="button" class="audio-play-pause" id="audio-play-pause" aria-label="<?php esc_attr_e('Play/Pause', 'humanitarianblog'); ?>">
				<svg class="icon-play" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
					<polygon points="5 3 19 12 5 21 5 3"></polygon>
				</svg>
				<svg class="icon-pause" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="display: none;">
					<rect x="6" y="4" width="4" height="16"></rect>
					<rect x="14" y="4" width="4" height="16"></rect>
				</svg>
			</button>

			<div class="audio-progress">
				<div class="audio-progress-bar" id="audio-progress-bar">
					<div class="audio-progress-fill" id="audio-progress-fill"></div>
				</div>
			</div>

			<select class="audio-speed" id="audio-speed" aria-label="<?php esc_attr_e('Playback speed', 'humanitarianblog'); ?>">
				<option value="0.75">0.75x</option>
				<option value="1" selected>1x</option>
				<option value="1.25">1.25x</option>
				<option value="1.5">1.5x</option>
			</select>

			<button type="button" class="audio-stop" id="audio-stop" aria-label="<?php esc_attr_e('Stop', 'humanitarianblog'); ?>">
				<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
					<rect x="6" y="6" width="12" height="12"></rect>
				</svg>
			</button>
		</div>
	</div>
</div>
