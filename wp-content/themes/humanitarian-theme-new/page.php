<?php
/**
 * Page Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<div class="page-template">
    <div class="container">
        <?php while (have_posts()) : the_post(); ?>

        <header class="page-template__header">
            <h1 class="page-template__title"><?php the_title(); ?></h1>
        </header>

        <div class="page-template__content">
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
        </div>

        <?php endwhile; ?>
    </div>
</div>

<?php
get_footer();
?>
