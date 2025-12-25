<?php
/**
 * Gutenberg Block Editor Customizations
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Restrict allowed block types to essentials only
 */
function humanitarian_allowed_block_types($allowed_blocks, $editor_context) {
    // Only restrict for posts
    if (!empty($editor_context->post) && $editor_context->post->post_type === 'post') {
        return array(
            // Text blocks
            'core/paragraph',
            'core/heading',
            'core/list',
            'core/list-item',
            'core/quote',

            // Media blocks
            'core/image',
            'core/gallery',
            'core/video',
            'core/audio',
            'core/embed',

            // Layout blocks
            'core/separator',
            'core/spacer',

            // Common embeds
            'core-embed/youtube',
            'core-embed/twitter',
            'core-embed/vimeo',
        );
    }

    // Allow all blocks for pages
    return $allowed_blocks;
}
add_filter('allowed_block_types_all', 'humanitarian_allowed_block_types', 10, 2);

/**
 * Customize heading levels (only H2, H3, H4)
 */
function humanitarian_heading_levels() {
    ?>
    <script>
    wp.domReady(function() {
        // Remove H1, H5, H6 from heading block
        wp.blocks.unregisterBlockStyle('core/heading', 'default');

        // Custom heading level restriction via filter
        if (wp.hooks) {
            wp.hooks.addFilter(
                'blocks.registerBlockType',
                'humanitarian/heading-levels',
                function(settings, name) {
                    if (name === 'core/heading') {
                        return Object.assign({}, settings, {
                            attributes: Object.assign({}, settings.attributes, {
                                level: {
                                    type: 'number',
                                    default: 2
                                }
                            })
                        });
                    }
                    return settings;
                }
            );
        }
    });
    </script>
    <?php
}
add_action('enqueue_block_editor_assets', 'humanitarian_heading_levels');

/**
 * Enqueue block editor styles
 */
function humanitarian_block_editor_styles() {
    // Google Fonts for editor
    wp_enqueue_style(
        'humanitarian-editor-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,700&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;0,900;1,400&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400&display=swap',
        array(),
        null
    );

    // Custom editor styles
    wp_enqueue_style(
        'humanitarian-editor-style',
        HUMANITARIAN_URI . '/assets/css/editor-style.css',
        array('humanitarian-editor-fonts'),
        HUMANITARIAN_VERSION
    );
}
add_action('enqueue_block_editor_assets', 'humanitarian_block_editor_styles');

/**
 * Add editor inline styles
 */
function humanitarian_editor_inline_styles() {
    $css = "
        /* Typography */
        .editor-styles-wrapper {
            font-family: 'Merriweather', Georgia, serif;
            font-size: 18px;
            line-height: 1.8;
            color: #44403c;
        }

        .editor-styles-wrapper h1,
        .editor-styles-wrapper h2,
        .editor-styles-wrapper h3,
        .editor-styles-wrapper h4 {
            font-family: 'Playfair Display', Georgia, serif;
            font-weight: 700;
            color: #1a1919;
        }

        .editor-styles-wrapper h2 {
            font-size: 1.875rem;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }

        .editor-styles-wrapper h3 {
            font-size: 1.5rem;
            margin-top: 2rem;
            margin-bottom: 0.75rem;
        }

        .editor-styles-wrapper h4 {
            font-size: 1.25rem;
            margin-top: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .editor-styles-wrapper p {
            margin-bottom: 1.5rem;
        }

        .editor-styles-wrapper a {
            color: #0D5C63;
        }

        .editor-styles-wrapper blockquote {
            border-left: 4px solid #0D5C63;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            background: #f4f1ea;
            padding: 1.5rem;
        }

        .editor-styles-wrapper ul,
        .editor-styles-wrapper ol {
            margin: 1.5rem 0;
            padding-left: 1.5rem;
        }

        .editor-styles-wrapper li {
            margin-bottom: 0.5rem;
        }

        .editor-styles-wrapper img {
            border-radius: 2px;
        }

        /* Post title */
        .editor-post-title__input {
            font-family: 'Playfair Display', Georgia, serif !important;
            font-size: 2.5rem !important;
            font-weight: 700 !important;
            color: #1a1919 !important;
        }

        /* Max width for content */
        .editor-styles-wrapper .wp-block {
            max-width: 768px;
        }

        .editor-styles-wrapper .wp-block[data-align='wide'] {
            max-width: 1100px;
        }

        .editor-styles-wrapper .wp-block[data-align='full'] {
            max-width: none;
        }
    ";

    wp_add_inline_style('humanitarian-editor-style', $css);
}
add_action('enqueue_block_editor_assets', 'humanitarian_editor_inline_styles', 20);

/**
 * Disable block directory (installing blocks from editor)
 */
remove_action('enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets');

/**
 * Disable pattern directory
 */
add_filter('should_load_remote_block_patterns', '__return_false');

/**
 * Remove default block patterns
 */
function humanitarian_remove_block_patterns() {
    remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'humanitarian_remove_block_patterns');

/**
 * Add custom block patterns (optional)
 */
function humanitarian_register_block_patterns() {
    // Pull quote pattern
    register_block_pattern(
        'humanitarian/pull-quote',
        array(
            'title'       => __('Pull Quote', 'humanitarian'),
            'description' => __('A styled pull quote for highlighting important text.', 'humanitarian'),
            'categories'  => array('text'),
            'content'     => '<!-- wp:quote {"className":"is-style-pull-quote"} -->
                <blockquote class="wp-block-quote is-style-pull-quote">
                    <p>Add your pull quote text here...</p>
                    <cite>Attribution</cite>
                </blockquote>
                <!-- /wp:quote -->',
        )
    );

    // Image with caption pattern
    register_block_pattern(
        'humanitarian/image-caption',
        array(
            'title'       => __('Image with Caption', 'humanitarian'),
            'description' => __('An image with a descriptive caption below.', 'humanitarian'),
            'categories'  => array('media'),
            'content'     => '<!-- wp:image {"align":"wide"} -->
                <figure class="wp-block-image alignwide">
                    <img src="" alt=""/>
                    <figcaption>Add image caption here...</figcaption>
                </figure>
                <!-- /wp:image -->',
        )
    );
}
add_action('init', 'humanitarian_register_block_patterns');

/**
 * Register block pattern category
 */
function humanitarian_register_pattern_category() {
    register_block_pattern_category(
        'humanitarian',
        array('label' => __('HumanitarianBlog', 'humanitarian'))
    );
}
add_action('init', 'humanitarian_register_pattern_category');

/**
 * Disable openverse media library
 */
add_filter('block_editor_settings_all', function($settings) {
    $settings['enableOpenverseMediaCategory'] = false;
    return $settings;
});

/**
 * Simplify image block settings
 */
function humanitarian_simplify_image_block() {
    ?>
    <script>
    wp.domReady(function() {
        // Remove image block styles we don't need
        wp.blocks.unregisterBlockStyle('core/image', 'rounded');
    });
    </script>
    <?php
}
add_action('enqueue_block_editor_assets', 'humanitarian_simplify_image_block');
