<?php
/**
 * Template Name: Bookmarks Page
 *
 * Displays user's bookmarked articles (stored in localStorage)
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main bookmarks-page">
    <div class="container">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e('My Bookmarks', 'humanitarianblog'); ?></h1>
            <p class="page-description">
                <?php esc_html_e('Your saved articles for offline reading', 'humanitarianblog'); ?>
            </p>
        </header>

        <!-- Filter & Sort Controls -->
        <div class="bookmarks-controls">
            <div class="bookmarks-filters">
                <label for="bookmark-filter-category">
                    <?php esc_html_e('Filter by category:', 'humanitarianblog'); ?>
                </label>
                <select id="bookmark-filter-category" class="bookmark-filter">
                    <option value="all"><?php esc_html_e('All Categories', 'humanitarianblog'); ?></option>
                    <!-- Categories will be populated dynamically via JavaScript -->
                </select>
            </div>

            <div class="bookmarks-sort">
                <label for="bookmark-sort">
                    <?php esc_html_e('Sort by:', 'humanitarianblog'); ?>
                </label>
                <select id="bookmark-sort" class="bookmark-sort-select">
                    <option value="date-desc"><?php esc_html_e('Newest First', 'humanitarianblog'); ?></option>
                    <option value="date-asc"><?php esc_html_e('Oldest First', 'humanitarianblog'); ?></option>
                    <option value="title-asc"><?php esc_html_e('Title A-Z', 'humanitarianblog'); ?></option>
                    <option value="title-desc"><?php esc_html_e('Title Z-A', 'humanitarianblog'); ?></option>
                </select>
            </div>

            <div class="bookmarks-actions">
                <button id="clear-all-bookmarks" class="btn btn-outline btn-danger">
                    <?php esc_html_e('Clear All', 'humanitarianblog'); ?>
                </button>
            </div>
        </div>

        <!-- Bookmarks Counter -->
        <div class="bookmarks-meta">
            <p id="bookmarks-count">
                <span id="visible-count">0</span>
                <?php esc_html_e('of', 'humanitarianblog'); ?>
                <span id="total-count">0</span>
                <?php esc_html_e('bookmarks', 'humanitarianblog'); ?>
            </p>
        </div>

        <!-- Loading State -->
        <div id="bookmarks-loading" class="bookmarks-loading">
            <p><?php esc_html_e('Loading bookmarks...', 'humanitarianblog'); ?></p>
        </div>

        <!-- Empty State -->
        <div id="bookmarks-empty" class="bookmarks-empty" style="display: none;">
            <div class="empty-state">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                </svg>
                <h2><?php esc_html_e('No Bookmarks Yet', 'humanitarianblog'); ?></h2>
                <p><?php esc_html_e('Start bookmarking articles to read them later offline.', 'humanitarianblog'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                    <?php esc_html_e('Browse Articles', 'humanitarianblog'); ?>
                </a>
            </div>
        </div>

        <!-- No Results State (after filtering) -->
        <div id="bookmarks-no-results" class="bookmarks-empty" style="display: none;">
            <div class="empty-state">
                <h2><?php esc_html_e('No Results Found', 'humanitarianblog'); ?></h2>
                <p><?php esc_html_e('Try adjusting your filters.', 'humanitarianblog'); ?></p>
            </div>
        </div>

        <!-- Bookmarks Grid -->
        <div id="bookmarks-grid" class="bookmarks-grid" style="display: none;">
            <!-- Bookmarks will be populated dynamically via JavaScript -->
        </div>
    </div>
</main>

<?php
get_footer();
