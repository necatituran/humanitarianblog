/**
 * Bookmarks Page - Display and manage saved articles
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Check if we're on the bookmarks page
    if (!document.body.classList.contains('page-template-page-bookmarks')) {
        return;
    }

    let allBookmarks = [];
    let filteredBookmarks = [];
    let currentFilter = 'all';
    let currentSort = 'date-desc';

    document.addEventListener('DOMContentLoaded', function() {
        loadBookmarks();
        initControls();
    });

    /**
     * Load bookmarks from localStorage and fetch data from server
     */
    function loadBookmarks() {
        const loadingDiv = document.getElementById('bookmarks-loading');
        const emptyDiv = document.getElementById('bookmarks-empty');
        const gridDiv = document.getElementById('bookmarks-grid');

        // Get bookmark IDs from localStorage
        const bookmarkIds = JSON.parse(localStorage.getItem('bookmarked_posts') || '[]');

        if (bookmarkIds.length === 0) {
            // Show empty state
            if (loadingDiv) loadingDiv.style.display = 'none';
            if (emptyDiv) emptyDiv.style.display = 'block';
            if (gridDiv) gridDiv.style.display = 'none';
            updateCounter(0, 0);
            return;
        }

        // Check if AJAX is configured
        if (typeof humanitarianBlogAjax === 'undefined') {
            if (loadingDiv) loadingDiv.innerHTML = '<p class="error">AJAX not configured</p>';
            return;
        }

        // Fetch bookmark data from server
        fetch(humanitarianBlogAjax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'get_bookmarked_posts',
                nonce: humanitarianBlogAjax.nonce,
                post_ids: JSON.stringify(bookmarkIds)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (loadingDiv) loadingDiv.style.display = 'none';

            if (data.success && data.data.posts && data.data.posts.length > 0) {
                allBookmarks = data.data.posts;
                filteredBookmarks = [...allBookmarks];

                // Populate category filter
                populateCategoryFilter();

                // Sort and display
                sortBookmarks();
                displayBookmarks();

                if (gridDiv) gridDiv.style.display = 'grid';
                if (emptyDiv) emptyDiv.style.display = 'none';
            } else {
                // No valid bookmarks found
                if (emptyDiv) emptyDiv.style.display = 'block';
                if (gridDiv) gridDiv.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error loading bookmarks:', error);
            if (loadingDiv) {
                loadingDiv.innerHTML = '<p class="error">Failed to load bookmarks. Please try again.</p>';
            }
        });
    }

    /**
     * Populate category filter dropdown
     */
    function populateCategoryFilter() {
        const filterSelect = document.getElementById('bookmark-filter-category');
        if (!filterSelect) return;

        // Get unique categories
        const categories = new Set();
        allBookmarks.forEach(bookmark => {
            if (bookmark.category) {
                categories.add(bookmark.category);
            }
        });

        // Add category options
        categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category;
            option.textContent = category;
            filterSelect.appendChild(option);
        });
    }

    /**
     * Initialize controls (filter, sort, clear all)
     */
    function initControls() {
        // Filter
        const filterSelect = document.getElementById('bookmark-filter-category');
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                currentFilter = this.value;
                filterAndDisplay();
            });
        }

        // Sort
        const sortSelect = document.getElementById('bookmark-sort');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                currentSort = this.value;
                sortBookmarks();
                displayBookmarks();
            });
        }

        // Clear all
        const clearBtn = document.getElementById('clear-all-bookmarks');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                if (confirm('Are you sure you want to remove all bookmarks?')) {
                    localStorage.removeItem('bookmarked_posts');
                    location.reload();
                }
            });
        }
    }

    /**
     * Filter and display bookmarks
     */
    function filterAndDisplay() {
        if (currentFilter === 'all') {
            filteredBookmarks = [...allBookmarks];
        } else {
            filteredBookmarks = allBookmarks.filter(bookmark => {
                return bookmark.category === currentFilter;
            });
        }

        sortBookmarks();
        displayBookmarks();

        // Show/hide no results message
        const noResultsDiv = document.getElementById('bookmarks-no-results');
        const gridDiv = document.getElementById('bookmarks-grid');

        if (filteredBookmarks.length === 0) {
            if (noResultsDiv) noResultsDiv.style.display = 'block';
            if (gridDiv) gridDiv.style.display = 'none';
        } else {
            if (noResultsDiv) noResultsDiv.style.display = 'none';
            if (gridDiv) gridDiv.style.display = 'grid';
        }
    }

    /**
     * Sort bookmarks based on current sort option
     */
    function sortBookmarks() {
        filteredBookmarks.sort((a, b) => {
            switch (currentSort) {
                case 'date-desc':
                    return new Date(b.date) - new Date(a.date);
                case 'date-asc':
                    return new Date(a.date) - new Date(b.date);
                case 'title-asc':
                    return a.title.localeCompare(b.title);
                case 'title-desc':
                    return b.title.localeCompare(a.title);
                default:
                    return 0;
            }
        });
    }

    /**
     * Display bookmarks in grid
     */
    function displayBookmarks() {
        const gridDiv = document.getElementById('bookmarks-grid');
        if (!gridDiv) return;

        gridDiv.innerHTML = '';

        filteredBookmarks.forEach(bookmark => {
            const card = createBookmarkCard(bookmark);
            gridDiv.appendChild(card);
        });

        updateCounter(filteredBookmarks.length, allBookmarks.length);
    }

    /**
     * Create bookmark card HTML
     */
    function createBookmarkCard(bookmark) {
        const article = document.createElement('article');
        article.className = 'bookmark-card';
        article.dataset.postId = bookmark.id;

        const thumbnailHtml = bookmark.thumbnail
            ? `<img src="${escapeHtml(bookmark.thumbnail)}" alt="${escapeHtml(bookmark.title)}" loading="lazy">`
            : '<div class="no-thumbnail"></div>';

        article.innerHTML = `
            <div class="bookmark-thumbnail">
                ${thumbnailHtml}
            </div>
            <div class="bookmark-content">
                <div class="bookmark-meta">
                    ${bookmark.category ? `<span class="bookmark-category">${escapeHtml(bookmark.category)}</span>` : ''}
                    <span class="bookmark-date">${escapeHtml(bookmark.date_formatted)}</span>
                </div>
                <h3 class="bookmark-title">
                    <a href="${escapeHtml(bookmark.url)}">${escapeHtml(bookmark.title)}</a>
                </h3>
                <p class="bookmark-excerpt">${escapeHtml(bookmark.excerpt)}</p>
                <div class="bookmark-actions">
                    <a href="${escapeHtml(bookmark.url)}" class="btn btn-primary btn-sm">Read Article</a>
                    <button class="btn btn-outline btn-sm bookmark-remove" data-post-id="${bookmark.id}">Remove</button>
                </div>
            </div>
        `;

        // Add remove handler
        const removeBtn = article.querySelector('.bookmark-remove');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                removeBookmark(bookmark.id);
            });
        }

        return article;
    }

    /**
     * Remove bookmark
     */
    function removeBookmark(postId) {
        let bookmarks = JSON.parse(localStorage.getItem('bookmarked_posts') || '[]');
        bookmarks = bookmarks.filter(id => id !== postId.toString());
        localStorage.setItem('bookmarked_posts', JSON.stringify(bookmarks));

        // Remove from current arrays
        allBookmarks = allBookmarks.filter(b => b.id !== postId);
        filteredBookmarks = filteredBookmarks.filter(b => b.id !== postId);

        // Remove card from DOM with animation
        const card = document.querySelector(`.bookmark-card[data-post-id="${postId}"]`);
        if (card) {
            card.style.opacity = '0';
            card.style.transform = 'scale(0.9)';
            setTimeout(() => {
                card.remove();

                // Check if empty
                if (allBookmarks.length === 0) {
                    const emptyDiv = document.getElementById('bookmarks-empty');
                    const gridDiv = document.getElementById('bookmarks-grid');
                    if (emptyDiv) emptyDiv.style.display = 'block';
                    if (gridDiv) gridDiv.style.display = 'none';
                } else if (filteredBookmarks.length === 0) {
                    const noResultsDiv = document.getElementById('bookmarks-no-results');
                    const gridDiv = document.getElementById('bookmarks-grid');
                    if (noResultsDiv) noResultsDiv.style.display = 'block';
                    if (gridDiv) gridDiv.style.display = 'none';
                }

                updateCounter(filteredBookmarks.length, allBookmarks.length);
            }, 300);
        }

        showNotification('Bookmark removed');
    }

    /**
     * Update counter
     */
    function updateCounter(visible, total) {
        const visibleSpan = document.getElementById('visible-count');
        const totalSpan = document.getElementById('total-count');

        if (visibleSpan) visibleSpan.textContent = visible;
        if (totalSpan) totalSpan.textContent = total;
    }

    /**
     * Show notification
     */
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

})();
