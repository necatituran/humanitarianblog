<?php
/**
 * The template for displaying comments
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password,
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}
?>

<div id="comments" class="comments-area">

    <?php if (have_comments()) : ?>
        <h2 class="comments-title">
            <?php
            $comment_count = get_comments_number();
            if ('1' === $comment_count) {
                printf(
                    /* translators: 1: title. */
                    esc_html__('One comment on &ldquo;%1$s&rdquo;', 'humanitarianblog'),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title. */
                    esc_html(_n('%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $comment_count, 'humanitarianblog')),
                    number_format_i18n($comment_count),
                    '<span>' . wp_kses_post(get_the_title()) . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments([
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 60,
                'callback'    => 'humanitarianblog_comment_callback',
            ]);
            ?>
        </ol>

        <?php
        the_comments_navigation([
            'prev_text' => __('&larr; Older Comments', 'humanitarianblog'),
            'next_text' => __('Newer Comments &rarr;', 'humanitarianblog'),
        ]);

        // If comments are closed and there are comments, show notice
        if (!comments_open()) :
            ?>
            <p class="no-comments"><?php esc_html_e('Comments are closed.', 'humanitarianblog'); ?></p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    comment_form([
        'title_reply'          => __('Leave a Comment', 'humanitarianblog'),
        'title_reply_to'       => __('Reply to %s', 'humanitarianblog'),
        'cancel_reply_link'    => __('Cancel Reply', 'humanitarianblog'),
        'label_submit'         => __('Post Comment', 'humanitarianblog'),
        'comment_notes_before' => '<p class="comment-notes">' . __('Your email address will not be published. Required fields are marked *', 'humanitarianblog') . '</p>',
        'class_container'      => 'comment-respond',
        'class_form'           => 'comment-form',
        'class_submit'         => 'btn btn-primary submit-comment',
    ]);
    ?>

</div><!-- #comments -->
