/**
 * DeepL Translation Admin JavaScript
 *
 * Handles translation UI interactions in the post editor
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialize translation functionality
     */
    function init() {
        bindTranslateButtons();
    }

    /**
     * Bind click events to translate buttons
     */
    function bindTranslateButtons() {
        $(document).on('click', '.deepl-translate-btn', function(e) {
            e.preventDefault();

            var $button = $(this);
            var $box = $button.closest('.deepl-translation-box');
            var $status = $box.find('.deepl-status');
            var $result = $box.find('.deepl-result');

            var postId = $button.data('post-id');
            var sourceLang = $button.data('source-lang');
            var targetLang = $button.data('target-lang');
            var hasTranslation = $button.data('has-translation') === '1' || $button.data('has-translation') === 1;

            // Confirm if updating existing translation
            if (hasTranslation) {
                if (!confirm(deeplTranslation.strings.confirm_update)) {
                    return;
                }
            }

            // Show loading state
            $button.prop('disabled', true);
            $box.find('.deepl-translate-btn').prop('disabled', true);
            $status.show();
            $result.hide().removeClass('success error');

            // Make AJAX request
            $.ajax({
                url: deeplTranslation.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'deepl_translate_post',
                    nonce: deeplTranslation.nonce,
                    post_id: postId,
                    source_lang: sourceLang,
                    target_lang: targetLang
                },
                success: function(response) {
                    $status.hide();

                    if (response.success) {
                        var message = response.data.is_update
                            ? deeplTranslation.strings.updated
                            : deeplTranslation.strings.success;

                        $result
                            .addClass('success')
                            .html(
                                '<strong>' + message + '</strong><br><br>' +
                                '<a href="' + response.data.edit_url + '" class="button" target="_blank">' +
                                deeplTranslation.strings.edit_translation +
                                '</a> ' +
                                '<a href="' + response.data.view_url + '" class="button" target="_blank">' +
                                deeplTranslation.strings.view_translation +
                                '</a>'
                            )
                            .show();

                        // Update button state
                        $button
                            .removeClass('button-primary')
                            .addClass('button')
                            .text($button.text().replace('Translate to', 'Update'))
                            .data('has-translation', '1');

                    } else {
                        $result
                            .addClass('error')
                            .html('<strong>' + deeplTranslation.strings.error + '</strong><br>' + response.data.message)
                            .show();
                    }

                    $box.find('.deepl-translate-btn').prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    $status.hide();
                    $result
                        .addClass('error')
                        .html('<strong>' + deeplTranslation.strings.error + '</strong><br>' + error)
                        .show();

                    $box.find('.deepl-translate-btn').prop('disabled', false);
                }
            });
        });
    }

    // Initialize on document ready
    $(document).ready(init);

})(jQuery);
