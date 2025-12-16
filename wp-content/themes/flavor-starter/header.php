<?php
/**
 * The header template file - Editorial Magazine Style
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php _e('Skip to content', 'humanitarianblog'); ?></a>

    <!-- Accessibility Toolbar - Left Side Panel -->
    <div class="accessibility-toolbar" id="accessibility-toolbar">
        <!-- Toggle Button (Always Visible) -->
        <button type="button" class="a11y-toolbar-toggle" id="a11y-toggle" aria-label="<?php esc_attr_e('Toggle accessibility tools', 'flavor-starter'); ?>" aria-expanded="false">
            <svg class="icon-a11y" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="4.5" r="2.5"></circle><path d="m10.2 6.3-3.9 3.9"></path><path d="m14 6.4 3.9 3.9"></path><path d="M12 12v3"></path><path d="m8 21 3.5-6"></path><path d="m16 21-3.5-6"></path></svg>
            <svg class="icon-close" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>

        <!-- Toolbar Panel -->
        <div class="a11y-toolbar-panel">
            <div class="a11y-toolbar-header">
                <span class="a11y-toolbar-title"><?php _e('Accessibility', 'flavor-starter'); ?></span>
            </div>

            <!-- Font Size Controls -->
            <div class="a11y-group a11y-font-size">
                <span class="a11y-label"><?php _e('Font Size', 'flavor-starter'); ?></span>
                <div class="a11y-btn-group">
                    <button type="button" class="a11y-btn" id="font-decrease" aria-label="<?php esc_attr_e('Decrease font size', 'flavor-starter'); ?>">A-</button>
                    <button type="button" class="a11y-btn" id="font-reset" aria-label="<?php esc_attr_e('Reset font size', 'flavor-starter'); ?>">A</button>
                    <button type="button" class="a11y-btn" id="font-increase" aria-label="<?php esc_attr_e('Increase font size', 'flavor-starter'); ?>">A+</button>
                </div>
            </div>

            <!-- Line Spacing -->
            <div class="a11y-group a11y-line-spacing">
                <span class="a11y-label"><?php _e('Line Spacing', 'flavor-starter'); ?></span>
                <div class="a11y-btn-group">
                    <button type="button" class="a11y-btn" id="spacing-normal" aria-label="<?php esc_attr_e('Normal spacing', 'flavor-starter'); ?>"><?php _e('Normal', 'flavor-starter'); ?></button>
                    <button type="button" class="a11y-btn" id="spacing-wide" aria-label="<?php esc_attr_e('Wide spacing', 'flavor-starter'); ?>"><?php _e('Wide', 'flavor-starter'); ?></button>
                </div>
            </div>

            <!-- Dark Mode Toggle -->
            <div class="a11y-group a11y-theme">
                <span class="a11y-label"><?php _e('Theme', 'flavor-starter'); ?></span>
                <button type="button" class="a11y-btn a11y-theme-toggle" id="theme-toggle" aria-label="<?php esc_attr_e('Toggle dark mode', 'flavor-starter'); ?>">
                    <svg class="icon-moon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    <svg class="icon-sun" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <span class="theme-label"><?php _e('Dark', 'flavor-starter'); ?></span>
                </button>
            </div>

            <!-- High Contrast -->
            <div class="a11y-group a11y-contrast">
                <span class="a11y-label"><?php _e('Contrast', 'flavor-starter'); ?></span>
                <button type="button" class="a11y-btn" id="contrast-toggle" aria-label="<?php esc_attr_e('Toggle high contrast', 'flavor-starter'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a10 10 0 0 1 0 20z" fill="currentColor"></path></svg>
                    <span><?php _e('High', 'flavor-starter'); ?></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Top Bar (Navy) with Search -->
    <div class="top-bar">
        <div class="container">
            <div class="top-bar-content">
                <!-- Search Button (Left - Prominent) -->
                <button class="search-toggle top-bar-search" aria-label="<?php esc_attr_e('Search', 'humanitarianblog'); ?>">
                    <span class="search-text"><?php _e('SEARCH', 'humanitarianblog'); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                </button>

                <!-- Right Side Links -->
                <nav class="top-bar-nav">
                    <a href="<?php echo esc_url(home_url('/about-us/')); ?>"><?php _e('About', 'humanitarianblog'); ?></a>
                    <a href="<?php echo esc_url(home_url('/authors/')); ?>"><?php _e('Authors', 'humanitarianblog'); ?></a>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>"><?php _e('Contact', 'humanitarianblog'); ?></a>
                    <a href="<?php echo esc_url(home_url('/donate/')); ?>" class="top-bar-donate"><?php _e('Donate', 'humanitarianblog'); ?></a>
                    <a href="#newsletter" class="top-bar-cta"><?php _e('Newsletter', 'humanitarianblog'); ?></a>

                    <?php if (is_user_logged_in()) :
                        $current_user = wp_get_current_user();
                        $bookmarks = get_user_meta($current_user->ID, 'bookmarked_posts', true);
                        $bookmark_count = is_array($bookmarks) ? count($bookmarks) : 0;
                    ?>
                        <!-- Bookmark Icon -->
                        <a href="<?php echo esc_url(home_url('/my-account/?tab=bookmarks')); ?>" class="top-bar-bookmark" title="<?php esc_attr_e('My Bookmarks', 'flavor-starter'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                            <?php if ($bookmark_count > 0) : ?>
                                <span class="bookmark-badge"><?php echo $bookmark_count; ?></span>
                            <?php endif; ?>
                        </a>

                        <!-- User Dropdown -->
                        <div class="user-dropdown">
                            <button class="user-dropdown-toggle" aria-expanded="false">
                                <?php echo get_avatar($current_user->ID, 32); ?>
                                <span class="user-name"><?php echo esc_html($current_user->display_name); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="user-dropdown-menu">
                                <a href="<?php echo esc_url(home_url('/my-account/?tab=bookmarks')); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                                    <?php _e('My Bookmarks', 'flavor-starter'); ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/my-account/?tab=profile')); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <?php _e('Edit Profile', 'flavor-starter'); ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/my-account/?tab=settings')); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                    <?php _e('Settings', 'flavor-starter'); ?>
                                </a>
                                <?php if (current_user_can('edit_posts')) : ?>
                                    <div class="dropdown-divider"></div>
                                    <a href="<?php echo esc_url(admin_url()); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
                                        <?php _e('Dashboard', 'flavor-starter'); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?php echo esc_url(wp_logout_url(home_url('/login/?logged_out=true'))); ?>" class="dropdown-logout">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                                    <?php _e('Sign Out', 'flavor-starter'); ?>
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url(home_url('/login/')); ?>" class="top-bar-login">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <?php _e('Sign In', 'flavor-starter'); ?>
                        </a>
                    <?php endif; ?>
                </nav>
            </div>
        </div>
    </div>

    <!-- Main Header (Pill Style) -->
    <header id="masthead" class="site-header">
        <div class="container">
            <div class="header-pill">
                <!-- Left: Logo -->
                <div class="header-brand">
                    <div class="site-branding">
                        <?php
                        if (has_custom_logo()) {
                            the_custom_logo();
                        } else {
                            $theme_logo = HUMANITARIAN_THEME_URI . '/assets/images/humanitarian-logo.png';
                            ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="site-title-link">
                                <img src="<?php echo esc_url($theme_logo); ?>" alt="<?php bloginfo('name'); ?>" class="site-logo" />
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                </div>

                <!-- Right: Navigation -->
                <nav id="site-navigation" class="site-navigation" aria-label="<?php esc_attr_e('Primary Menu', 'humanitarianblog'); ?>">
                    <?php
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'menu_id'        => 'primary-menu',
                        'menu_class'     => 'nav-menu',
                        'fallback_cb'    => 'humanitarianblog_fallback_menu',
                        'container'      => false,
                    ]);
                    ?>
                </nav>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" aria-expanded="false" aria-label="<?php esc_attr_e('Menu', 'humanitarianblog'); ?>">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>
            </div>
        </div>
    </header>

    <!-- Search Overlay -->
    <div class="search-overlay" id="search-overlay">
        <button class="search-close" aria-label="<?php esc_attr_e('Close search', 'humanitarianblog'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18"></path>
                <path d="m6 6 12 12"></path>
            </svg>
        </button>
        <div class="search-overlay-content">
            <?php get_search_form(); ?>
            <div id="live-search-results" class="search-results-container" style="display: none;">
                <p class="search-results-title"><?php _e('Results', 'humanitarianblog'); ?></p>
                <ul class="search-results-list"></ul>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobile-menu-overlay">
        <div class="mobile-menu-content">
            <div class="mobile-menu-header">
                <span class="mobile-menu-title"><?php bloginfo('name'); ?></span>
                <button class="mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'humanitarianblog'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <nav class="mobile-nav">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'mobile-menu',
                    'menu_class'     => 'mobile-nav-menu',
                    'fallback_cb'    => false,
                    'container'      => false,
                ));
                ?>
            </nav>
            <div class="mobile-menu-footer">
                <a href="#newsletter" class="btn btn-primary btn-block"><?php _e('Subscribe to Newsletter', 'humanitarianblog'); ?></a>
            </div>
        </div>
    </div>

    <div id="content" class="site-content">
