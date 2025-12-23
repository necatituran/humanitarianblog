<?php
/**
 * Header Template
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if (is_single()) : ?>
<!-- Reading Progress Bar -->
<div class="reading-progress" id="readingProgress"></div>
<?php endif; ?>

<a class="skip-link screen-reader-text" href="#content">
    <?php esc_html_e('Skip to content', 'humanitarian'); ?>
</a>

<!-- Top Strip - Navy Blue -->
<div class="top-strip">
    <div class="container top-strip__inner">
        <div class="top-strip__left">
            <!-- Language Switcher Dropdown -->
            <div class="language-dropdown">
                <button type="button" class="language-dropdown__toggle" id="langDropdownBtn" aria-expanded="false" aria-haspopup="true">
                    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                    </svg>
                    <span><?php esc_html_e('Language', 'humanitarian'); ?></span>
                    <svg class="language-dropdown__arrow" width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                    </svg>
                </button>
                <div class="language-dropdown__menu" id="langDropdownMenu" role="menu">
                    <?php
                    // Simple language system - no Polylang needed
                    if (function_exists('humanitarian_language_switcher')) {
                        echo humanitarian_language_switcher();
                    }
                    ?>
                </div>
            </div>
            <div class="top-strip__tagline">
                <?php echo esc_html(get_theme_mod('humanitarian_top_tagline', __('No News. Just Knowledge—by People Like You.', 'humanitarian'))); ?>
            </div>
        </div>
        <div class="top-strip__right">
            <nav class="top-strip__nav">
                <?php
                if (has_nav_menu('top-strip')) {
                    wp_nav_menu(array(
                        'theme_location' => 'top-strip',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'link_before'    => '<span class="top-strip__link">',
                        'link_after'     => '</span>',
                        'depth'          => 1,
                    ));
                } else {
                    // Default links - About Us, Publish With Us, Contact Us
                    ?>
                    <a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="top-strip__link"><?php esc_html_e('About Us', 'humanitarian'); ?></a>
                    <a href="<?php echo esc_url(home_url('/publish-with-us/')); ?>" class="top-strip__link"><?php esc_html_e('Publish With Us', 'humanitarian'); ?></a>
                    <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="top-strip__link"><?php esc_html_e('Contact Us', 'humanitarian'); ?></a>
                    <?php
                }
                ?>
            </nav>
            <!-- Privacy Dropdown -->
            <div class="privacy-dropdown">
                <button type="button" class="privacy-dropdown__toggle">
                    <span><?php esc_html_e('Privacy', 'humanitarian'); ?></span>
                    <svg class="privacy-dropdown__arrow" width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m19 9-7 7-7-7"/>
                    </svg>
                </button>
                <div class="privacy-dropdown__menu">
                    <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" class="privacy-dropdown__item">
                        <?php esc_html_e('Privacy Policy', 'humanitarian'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/child-safeguard/')); ?>" class="privacy-dropdown__item">
                        <?php esc_html_e('Child Safeguard Policy', 'humanitarian'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/content-usage/')); ?>" class="privacy-dropdown__item">
                        <?php esc_html_e('Content Usage Policy', 'humanitarian'); ?>
                    </a>
                    <a href="<?php echo esc_url(home_url('/user-agreement/')); ?>" class="privacy-dropdown__item">
                        <?php esc_html_e('User Agreement', 'humanitarian'); ?>
                    </a>
                </div>
            </div>
            <!-- Login/Member Button -->
            <a href="<?php echo is_user_logged_in() ? esc_url(admin_url('profile.php')) : esc_url(wp_login_url(get_permalink())); ?>" class="top-strip__login">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                </svg>
                <?php
                if (is_user_logged_in()) {
                    $current_user = wp_get_current_user();
                    echo esc_html($current_user->display_name);
                } else {
                    esc_html_e('Login', 'humanitarian');
                }
                ?>
            </a>
        </div>
    </div>
</div>

<!-- Header -->
<header id="header" class="site-header">
    <!-- Top Row: Navigation + Actions -->
    <div class="container site-header__nav-row">
        <!-- Mobile Menu Button -->
        <button class="site-header__mobile-menu-btn" onclick="toggleMenu()" aria-label="<?php esc_attr_e('Open Menu', 'humanitarian'); ?>">
            <svg class="w-6 h-6" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Desktop Navigation -->
        <nav class="main-nav" aria-label="<?php esc_attr_e('Primary Navigation', 'humanitarian'); ?>">
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'walker'         => new Humanitarian_Primary_Nav_Walker(),
                    'depth'          => 1,
                ));
            } else {
                // Default navigation - Main categories
                $main_categories = array(
                    'technical-guides' => __('Technical Guides', 'humanitarian'),
                    'aid-and-policy' => __('Aid and Policy', 'humanitarian'),
                    'environment-and-conflict' => __('Environment and Conflict', 'humanitarian'),
                    'stories-from-the-field' => __('Stories from the Field', 'humanitarian'),
                );
                foreach ($main_categories as $slug => $name) {
                    $category = get_category_by_slug($slug);
                    $link = $category ? get_category_link($category->term_id) : home_url('/category/' . $slug . '/');
                    printf(
                        '<a href="%s" class="main-nav__link">%s</a>',
                        esc_url($link),
                        esc_html($name)
                    );
                }
            }
            ?>
        </nav>

        <!-- Actions -->
        <div class="site-header__actions">
            <button class="header-search-btn" aria-label="<?php esc_attr_e('Search', 'humanitarian'); ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path stroke-linecap="round" d="m21 21-4.35-4.35"/>
                </svg>
            </button>
            <button class="header-menu-btn" onclick="toggleMenu()">
                <svg class="header-menu-btn__icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <span><?php esc_html_e('Menu', 'humanitarian'); ?></span>
            </button>
            <button class="header-search-mobile" aria-label="<?php esc_attr_e('Search', 'humanitarian'); ?>">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <path stroke-linecap="round" d="m21 21-4.35-4.35"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Bottom Row: Centered Logo -->
    <div class="container site-header__logo-row">
        <?php humanitarian_site_logo(); ?>
    </div>
</header>

<!-- Menu Overlay -->
<div id="menuOverlay" class="menu-overlay">
    <div class="container menu-overlay__inner">
        <div class="menu-overlay__header">
            <?php humanitarian_site_logo(); ?>
            <button class="menu-overlay__close" onclick="toggleMenu()" aria-label="<?php esc_attr_e('Close Menu', 'humanitarian'); ?>">
                <svg width="32" height="32" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <div class="menu-overlay__content">
            <!-- Sections -->
            <div class="menu-overlay__sections">
                <h3 class="menu-overlay__sections-title"><?php esc_html_e('Sections', 'humanitarian'); ?></h3>
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                        'walker'         => new Humanitarian_Overlay_Nav_Walker(),
                        'depth'          => 1,
                    ));
                } else {
                    // Default navigation - Main categories
                    $overlay_categories = array(
                        'technical-guides' => __('Technical Guides', 'humanitarian'),
                        'aid-and-policy' => __('Aid and Policy', 'humanitarian'),
                        'environment-and-conflict' => __('Environment and Conflict', 'humanitarian'),
                        'stories-from-the-field' => __('Stories from the Field', 'humanitarian'),
                    );
                    foreach ($overlay_categories as $slug => $name) {
                        $category = get_category_by_slug($slug);
                        $link = $category ? get_category_link($category->term_id) : home_url("/category/{$slug}/");
                        printf(
                            '<a href="%s" class="menu-overlay__section-link">%s</a>',
                            esc_url($link),
                            esc_html($name)
                        );
                    }
                }
                ?>
            </div>

            <!-- More Links -->
            <div class="menu-overlay__more">
                <h3 class="menu-overlay__more-title"><?php esc_html_e('More', 'humanitarian'); ?></h3>
                <ul class="menu-overlay__more-links">
                    <li>
                        <a href="<?php echo esc_url(home_url('/about-us/')); ?>" class="menu-overlay__more-link">
                            <span><?php esc_html_e('About Us', 'humanitarian'); ?></span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/publish-with-us/')); ?>" class="menu-overlay__more-link">
                            <span><?php esc_html_e('Publish With Us', 'humanitarian'); ?></span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo esc_url(home_url('/contact-us/')); ?>" class="menu-overlay__more-link">
                            <span><?php esc_html_e('Contact Us', 'humanitarian'); ?></span>
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/>
                            </svg>
                        </a>
                    </li>
                </ul>

                <!-- Newsletter Box -->
                <div class="menu-overlay__newsletter">
                    <h4 class="menu-overlay__newsletter-title"><?php esc_html_e('Subscribe to our newsletter', 'humanitarian'); ?></h4>
                    <p class="menu-overlay__newsletter-text"><?php esc_html_e('Join humanitarians worldwide getting the latest knowledge delivered to their inbox.', 'humanitarian'); ?></p>
                    <a href="<?php echo esc_url(get_theme_mod('humanitarian_newsletter_url', '#')); ?>" class="menu-overlay__newsletter-btn">
                        <?php esc_html_e('Subscribe', 'humanitarian'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Overlay -->
<div id="searchOverlay" class="search-overlay">
    <button class="search-overlay__close" onclick="toggleSearch()" aria-label="<?php esc_attr_e('Close search', 'humanitarian'); ?>">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6 6 18"></path>
            <path d="m6 6 12 12"></path>
        </svg>
    </button>
    <div class="search-overlay__inner">
        <?php get_search_form(); ?>
    </div>
</div>

<main id="content" class="site-main">
