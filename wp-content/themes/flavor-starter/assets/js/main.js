/**
 * Main JavaScript file - Editorial Magazine Style
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {

        // Initialize all features
        initStickyHeader();
        initMobileMenu();
        initSearchOverlay();
        initSmoothScroll();
        initLazyLoading();
        initCopyLinkButton();
        initBackToTop();
        initImageHoverEffects();
        initBlogAjaxPagination();
        initUserDropdown();
        initTableOfContents();

    });

    // Global event handlers (stored to prevent memory leaks)
    let escapeHandler = null;
    let clickOutsideHandler = null;

    /**
     * Sticky Header with scroll detection
     * Mobile: Hides top-bar on scroll down, shows on scroll up
     */
    function initStickyHeader() {
        const header = document.querySelector('.site-header');
        const topBar = document.querySelector('.top-bar');
        if (!header) return;

        let lastScroll = 0;
        let ticking = false;
        const scrollThreshold = 100;

        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    const currentScroll = window.pageYOffset;

                    // Add/remove scrolled class
                    if (currentScroll > 50) {
                        header.classList.add('is-scrolled');
                    } else {
                        header.classList.remove('is-scrolled');
                    }

                    // Mobile: Hide/show top-bar based on scroll direction
                    if (window.innerWidth <= 768) {
                        if (currentScroll > scrollThreshold) {
                            document.body.classList.add('scrolled-down');
                        } else {
                            document.body.classList.remove('scrolled-down');
                        }
                    }

                    lastScroll = currentScroll;
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.body.classList.remove('scrolled-down');
            }
        });
    }

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const mobileOverlay = document.querySelector('.mobile-menu-overlay');
        const mobileClose = document.querySelector('.mobile-menu-close');
        const body = document.body;

        if (!menuToggle || !mobileOverlay) return;

        // Open menu
        menuToggle.addEventListener('click', function() {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';

            menuToggle.setAttribute('aria-expanded', !isExpanded);
            mobileOverlay.classList.toggle('is-open');
            body.classList.toggle('menu-open');

            if (!isExpanded) {
                attachMobileMenuListeners(menuToggle, mobileOverlay, body);
            }
        });

        // Close button
        if (mobileClose) {
            mobileClose.addEventListener('click', function() {
                closeMobileMenu(menuToggle, mobileOverlay, body);
            });
        }

        // Close on link click
        const menuLinks = mobileOverlay.querySelectorAll('a');
        menuLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                closeMobileMenu(menuToggle, mobileOverlay, body);
            });
        });
    }

    /**
     * Close mobile menu
     */
    function closeMobileMenu(menuToggle, mobileOverlay, body) {
        menuToggle.setAttribute('aria-expanded', 'false');
        mobileOverlay.classList.remove('is-open');
        body.classList.remove('menu-open');
        removeMenuEventListeners();
    }

    /**
     * Attach mobile menu event listeners
     */
    function attachMobileMenuListeners(menuToggle, mobileOverlay, body) {
        removeMenuEventListeners();

        // Escape key handler
        escapeHandler = function(e) {
            if (e.key === 'Escape' && mobileOverlay.classList.contains('is-open')) {
                closeMobileMenu(menuToggle, mobileOverlay, body);
            }
        };

        // Click outside handler (on backdrop)
        clickOutsideHandler = function(e) {
            if (mobileOverlay.classList.contains('is-open') &&
                !mobileOverlay.querySelector('.mobile-menu-content').contains(e.target)) {
                closeMobileMenu(menuToggle, mobileOverlay, body);
            }
        };

        document.addEventListener('keydown', escapeHandler);
        // Use body::before as backdrop
        body.addEventListener('click', function(e) {
            if (e.target === body && body.classList.contains('menu-open')) {
                closeMobileMenu(menuToggle, mobileOverlay, body);
            }
        });
    }

    /**
     * Remove menu event listeners
     */
    function removeMenuEventListeners() {
        if (escapeHandler) {
            document.removeEventListener('keydown', escapeHandler);
            escapeHandler = null;
        }
        if (clickOutsideHandler) {
            document.removeEventListener('click', clickOutsideHandler);
            clickOutsideHandler = null;
        }
    }

    /**
     * Search Overlay Toggle with Live Search
     */
    function initSearchOverlay() {
        const searchToggle = document.querySelector('.search-toggle');
        const searchOverlay = document.querySelector('.search-overlay');
        const searchClose = document.querySelector('.search-close');
        const searchField = searchOverlay ? searchOverlay.querySelector('.search-field') : null;
        const resultsContainer = document.getElementById('live-search-results');
        const resultsList = resultsContainer ? resultsContainer.querySelector('.search-results-list') : null;

        if (!searchToggle || !searchOverlay) return;

        let searchTimeout = null;

        // Open search
        searchToggle.addEventListener('click', function() {
            searchOverlay.classList.add('is-open');
            document.body.classList.add('search-open');

            // Focus search input
            if (searchField) {
                setTimeout(function() {
                    searchField.focus();
                }, 100);
            }

            // Escape to close
            document.addEventListener('keydown', function closeOnEscape(e) {
                if (e.key === 'Escape') {
                    closeSearchOverlay();
                    document.removeEventListener('keydown', closeOnEscape);
                }
            });
        });

        // Live search functionality
        if (searchField && resultsContainer && resultsList) {
            searchField.addEventListener('input', function() {
                const query = this.value.trim();

                // Clear previous timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }

                // Hide results if query is empty
                if (query.length < 2) {
                    resultsContainer.style.display = 'none';
                    resultsList.innerHTML = '';
                    return;
                }

                // Debounce search
                searchTimeout = setTimeout(function() {
                    performLiveSearch(query);
                }, 300);
            });
        }

        function performLiveSearch(query) {
            // Show loading
            resultsContainer.style.display = 'block';
            resultsList.innerHTML = '<li class="search-loading">Searching...</li>';

            // Use WordPress REST API
            fetch('/wp-json/wp/v2/posts?search=' + encodeURIComponent(query) + '&per_page=5&_embed')
                .then(function(response) {
                    return response.json();
                })
                .then(function(posts) {
                    if (posts.length === 0) {
                        resultsList.innerHTML = '<li class="search-no-results">No results found</li>';
                        return;
                    }

                    resultsList.innerHTML = posts.map(function(post) {
                        var thumbnail = '';
                        if (post._embedded && post._embedded['wp:featuredmedia'] && post._embedded['wp:featuredmedia'][0]) {
                            var media = post._embedded['wp:featuredmedia'][0];
                            var imgUrl = media.media_details && media.media_details.sizes && media.media_details.sizes.thumbnail
                                ? media.media_details.sizes.thumbnail.source_url
                                : media.source_url;
                            thumbnail = '<div class="search-result-thumb"><img src="' + imgUrl + '" alt=""></div>';
                        }

                        var category = '';
                        if (post._embedded && post._embedded['wp:term'] && post._embedded['wp:term'][0] && post._embedded['wp:term'][0][0]) {
                            category = '<span class="search-result-category">' + post._embedded['wp:term'][0][0].name + '</span> · ';
                        }

                        var date = new Date(post.date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });

                        return '<li class="search-result-item">' +
                            '<a href="' + post.link + '" class="search-result-link">' +
                            thumbnail +
                            '<div class="search-result-content">' +
                            '<h4 class="search-result-title">' + post.title.rendered + '</h4>' +
                            '<p class="search-result-meta">' + category + date + '</p>' +
                            '</div>' +
                            '</a>' +
                            '</li>';
                    }).join('');
                })
                .catch(function(error) {
                    resultsList.innerHTML = '<li class="search-no-results">Error searching. Please try again.</li>';
                });
        }

        // Close search
        if (searchClose) {
            searchClose.addEventListener('click', closeSearchOverlay);
        }

        // Close on backdrop click (only on the overlay background, not content)
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                closeSearchOverlay();
            }
        });

        function closeSearchOverlay() {
            searchOverlay.classList.remove('is-open');
            document.body.classList.remove('search-open');
            // Clear search results
            if (searchField) searchField.value = '';
            if (resultsContainer) resultsContainer.style.display = 'none';
            if (resultsList) resultsList.innerHTML = '';
        }
    }

    /**
     * Image Hover Effects (cinematic zoom)
     */
    function initImageHoverEffects() {
        // Add hover effect classes to article card images
        const articleCards = document.querySelectorAll('.article-card, .hero-article, .opinion-card');

        articleCards.forEach(function(card) {
            card.classList.add('hover-lift');
        });
    }

    /**
     * Smooth Scroll for anchor links
     */
    function initSmoothScroll() {
        const anchorLinks = document.querySelectorAll('a[href^="#"]');

        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const targetId = this.getAttribute('href');

                // Ignore if href is just "#"
                if (targetId === '#' || targetId === '#0') return;

                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    e.preventDefault();

                    const headerOffset = 100; // Account for sticky header + top bar
                    const elementPosition = targetElement.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    // Update focus for accessibility
                    targetElement.focus({ preventScroll: true });

                    // Update URL hash
                    if (history.pushState) {
                        history.pushState(null, null, targetId);
                    }
                }
            });
        });
    }

    /**
     * Lazy Loading for images
     */
    function initLazyLoading() {
        const lazyImages = document.querySelectorAll('img[data-src], img[loading="lazy"]');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;

                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }

                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                            img.removeAttribute('data-srcset');
                        }

                        img.classList.add('loaded');
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.01
            });

            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        } else {
            // Fallback
            lazyImages.forEach(function(img) {
                if (img.dataset.src) img.src = img.dataset.src;
                if (img.dataset.srcset) img.srcset = img.dataset.srcset;
            });
        }
    }

    /**
     * Copy Link button functionality
     */
    function initCopyLinkButton() {
        const copyButtons = document.querySelectorAll('.share-copy');

        copyButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const url = this.dataset.url || window.location.href;

                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(url).then(function() {
                        showCopyFeedback(button, true);
                    }).catch(function() {
                        fallbackCopyText(url, button);
                    });
                } else {
                    fallbackCopyText(url, button);
                }
            });
        });
    }

    function fallbackCopyText(text, button) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.select();

        try {
            document.execCommand('copy');
            showCopyFeedback(button, true);
        } catch (err) {
            showCopyFeedback(button, false);
        }

        document.body.removeChild(textArea);
    }

    function showCopyFeedback(button, success) {
        const spanEl = button.querySelector('span');
        if (!spanEl) return;

        const originalText = spanEl.textContent;
        const feedbackText = success ? 'Copied!' : 'Failed';

        spanEl.textContent = feedbackText;
        button.classList.add(success ? 'copy-success' : 'copy-error');

        setTimeout(function() {
            spanEl.textContent = originalText;
            button.classList.remove('copy-success', 'copy-error');
        }, 2000);
    }

    /**
     * Back to Top button
     */
    function initBackToTop() {
        let backToTopButton = document.querySelector('.back-to-top');

        if (!backToTopButton) {
            backToTopButton = createBackToTopButton();
        }

        // Throttled scroll handler
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    if (window.pageYOffset > 400) {
                        backToTopButton.classList.add('is-visible');
                    } else {
                        backToTopButton.classList.remove('is-visible');
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });

        // Scroll to top on click
        backToTopButton.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    function createBackToTopButton() {
        const button = document.createElement('button');
        button.className = 'back-to-top';
        button.setAttribute('aria-label', 'Back to top');
        button.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"></polyline></svg>';

        document.body.appendChild(button);
        return button;
    }

    /**
     * Blog AJAX Pagination & Filtering
     * Loads posts without page refresh
     */
    function initBlogAjaxPagination() {
        const articlesGrid = document.querySelector('.blog-posts-section .articles-grid');
        const paginationNav = document.querySelector('.blog-pagination');
        const sortSelect = document.querySelector('.blog-sort-select');

        if (!articlesGrid) return;

        let currentPage = 1;
        let currentOrderBy = 'date';
        let isLoading = false;

        // Get initial values from URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('paged')) {
            currentPage = parseInt(urlParams.get('paged')) || 1;
        }
        if (urlParams.get('orderby')) {
            currentOrderBy = urlParams.get('orderby');
        }

        // Sort select change handler
        if (sortSelect) {
            // Remove the onchange attribute and handle via JS
            sortSelect.removeAttribute('onchange');
            sortSelect.value = currentOrderBy;

            sortSelect.addEventListener('change', function() {
                currentOrderBy = this.value;
                currentPage = 1;
                loadPosts();
            });
        }

        // Event delegation for pagination clicks
        if (paginationNav) {
            paginationNav.addEventListener('click', function(e) {
                var link = e.target.closest('a');
                if (!link) return;

                e.preventDefault();

                // Extract page number from href
                var href = link.getAttribute('href');
                var pageMatch = href.match(/[?&]paged=(\d+)/);
                if (pageMatch) {
                    currentPage = parseInt(pageMatch[1]);
                } else {
                    // Check for /page/X/ format
                    pageMatch = href.match(/\/page\/(\d+)/);
                    if (pageMatch) {
                        currentPage = parseInt(pageMatch[1]);
                    }
                }

                loadPosts();
            });
        }

        function loadPosts() {
            if (isLoading) return;
            isLoading = true;

            // Show loading state
            articlesGrid.style.opacity = '0.5';
            articlesGrid.style.pointerEvents = 'none';

            // Get AJAX settings
            var ajaxUrl = typeof humanitarianBlogAjax !== 'undefined'
                ? humanitarianBlogAjax.ajax_url
                : '/wp-admin/admin-ajax.php';
            var nonce = typeof humanitarianBlogAjax !== 'undefined'
                ? humanitarianBlogAjax.nonce
                : '';

            // Build form data
            var formData = new FormData();
            formData.append('action', 'load_blog_posts');
            formData.append('nonce', nonce);
            formData.append('page', currentPage);
            formData.append('orderby', currentOrderBy);

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    renderPosts(data.data.posts);
                    renderPagination(data.data.pagination);
                    updateURL();

                    // Scroll to top of posts section
                    var section = document.querySelector('.blog-posts-section');
                    if (section) {
                        section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                }
            })
            .catch(function(error) {
                console.error('Error loading posts:', error);
            })
            .finally(function() {
                isLoading = false;
                articlesGrid.style.opacity = '1';
                articlesGrid.style.pointerEvents = 'auto';
            });
        }

        function renderPosts(posts) {
            if (!posts || posts.length === 0) {
                articlesGrid.innerHTML = '<div class="no-posts-message"><p>No articles found.</p></div>';
                return;
            }

            var html = posts.map(function(post) {
                var thumbnailHtml = '';
                if (post.thumbnail) {
                    var captionHtml = '';
                    if (post.photo_caption) {
                        captionHtml = '<div class="photo-caption-wrapper">' +
                            '<span class="photo-caption">' + escapeHtml(post.category) + '</span>' +
                            '<span class="photo-caption-line2">' + escapeHtml(post.photo_caption) + '</span>' +
                            '</div>';
                    }
                    thumbnailHtml = '<a href="' + post.url + '" class="card-thumbnail">' +
                        '<img src="' + post.thumbnail + '" alt="">' +
                        captionHtml +
                        '</a>';
                }

                var categoryHtml = '';
                if (post.category) {
                    categoryHtml = '<a href="' + post.category_link + '" class="category-badge ' + post.category_color + '">' +
                        escapeHtml(post.category) +
                        '</a>';
                }

                return '<article class="' + post.classes + '">' +
                    thumbnailHtml +
                    '<div class="card-content">' +
                    categoryHtml +
                    '<h3 class="card-title"><a href="' + post.url + '">' + post.title + '</a></h3>' +
                    '<div class="card-meta">' +
                    '<span class="date">' + post.date + '</span>' +
                    '<span class="separator">·</span>' +
                    '<span class="read-time">' + post.reading_time + '</span>' +
                    '</div>' +
                    '</div>' +
                    '</article>';
            }).join('');

            articlesGrid.innerHTML = html;
        }

        function renderPagination(pagination) {
            if (!paginationNav || !pagination || pagination.length === 0) {
                if (paginationNav) paginationNav.innerHTML = '';
                return;
            }

            var html = '<nav class="navigation pagination" aria-label="Posts"><div class="nav-links">';

            pagination.forEach(function(item) {
                if (item.type === 'prev') {
                    html += '<a class="prev page-numbers" href="?paged=' + item.page + '&orderby=' + currentOrderBy + '">' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>' +
                        'Previous</a>';
                } else if (item.type === 'next') {
                    html += '<a class="next page-numbers" href="?paged=' + item.page + '&orderby=' + currentOrderBy + '">' +
                        'Next' +
                        '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg></a>';
                } else if (item.type === 'dots') {
                    html += '<span class="page-numbers dots">…</span>';
                } else if (item.type === 'page') {
                    if (item.current) {
                        html += '<span aria-current="page" class="page-numbers current">' + item.page + '</span>';
                    } else {
                        html += '<a class="page-numbers" href="?paged=' + item.page + '&orderby=' + currentOrderBy + '">' + item.page + '</a>';
                    }
                }
            });

            html += '</div></nav>';
            paginationNav.innerHTML = html;
        }

        function updateURL() {
            var url = new URL(window.location.href);
            url.searchParams.set('paged', currentPage);
            url.searchParams.set('orderby', currentOrderBy);
            window.history.pushState({}, '', url);
        }

        function escapeHtml(text) {
            if (!text) return '';
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }

    /**
     * User Dropdown Toggle
     */
    function initUserDropdown() {
        const dropdown = document.querySelector('.user-dropdown');
        if (!dropdown) return;

        const toggle = dropdown.querySelector('.user-dropdown-toggle');
        const menu = dropdown.querySelector('.user-dropdown-menu');

        if (!toggle || !menu) return;

        // Toggle dropdown on click
        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = dropdown.classList.contains('open');

            // Close all other dropdowns first
            document.querySelectorAll('.user-dropdown.open').forEach(function(d) {
                d.classList.remove('open');
            });

            if (!isOpen) {
                dropdown.classList.add('open');
                toggle.setAttribute('aria-expanded', 'true');
            } else {
                dropdown.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Close on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dropdown.classList.contains('open')) {
                dropdown.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.focus();
            }
        });

        // Close on menu item click
        menu.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                dropdown.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    }

    /**
     * Initialize Bookmark (Save) Button in Reading Toolbar
     */
    document.addEventListener('DOMContentLoaded', function() {
        initBookmarkButton();
    });

    function initBookmarkButton() {
        const saveButton = document.getElementById('save-button');
        if (!saveButton) return;

        const postId = saveButton.getAttribute('data-post-id');
        if (!postId) return;

        // Check if bookmark data is available
        if (typeof humanitarianBookmark === 'undefined') {
            console.log('Bookmark config not loaded');
            return;
        }

        // Update button state based on localStorage (for non-logged users) or server
        updateBookmarkState(saveButton, postId);

        // Handle click
        saveButton.addEventListener('click', function(e) {
            e.preventDefault();

            if (!humanitarianBookmark.isLoggedIn) {
                // Show login prompt
                if (confirm('Please sign in to save articles. Go to login page?')) {
                    window.location.href = humanitarianBookmark.loginUrl + '?redirect_to=' + encodeURIComponent(window.location.href);
                }
                return;
            }

            // Toggle bookmark via AJAX
            toggleBookmark(saveButton, postId);
        });
    }

    function updateBookmarkState(button, postId) {
        // For logged-in users, we check server-side on page load
        // The button state should reflect if it's already bookmarked
        // We can add a class on the PHP side or check via localstorage sync

        // Check localStorage for initial state (hybrid approach)
        const localBookmarks = JSON.parse(localStorage.getItem('user_bookmarks') || '[]');
        if (localBookmarks.includes(postId) || localBookmarks.includes(parseInt(postId))) {
            button.classList.add('is-bookmarked');
            const label = button.querySelector('.toolbar-label');
            if (label) label.textContent = 'Saved';
        }
    }

    function toggleBookmark(button, postId) {
        if (button.classList.contains('is-loading')) return;

        button.classList.add('is-loading');

        const formData = new FormData();
        formData.append('action', 'toggle_bookmark');
        formData.append('post_id', postId);
        formData.append('nonce', humanitarianBookmark.nonce);

        fetch(humanitarianBookmark.ajaxurl, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            button.classList.remove('is-loading');

            if (data.success) {
                const isNowBookmarked = data.data.bookmarked;
                const label = button.querySelector('.toolbar-label');

                if (isNowBookmarked) {
                    button.classList.add('is-bookmarked');
                    if (label) label.textContent = 'Saved';
                    // Update localStorage
                    syncLocalBookmark(postId, true);
                } else {
                    button.classList.remove('is-bookmarked');
                    if (label) label.textContent = 'Save';
                    // Update localStorage
                    syncLocalBookmark(postId, false);
                }

                // Update header bookmark badge
                updateHeaderBookmarkBadge(data.data.count);

                // Show feedback
                showBookmarkFeedback(data.data.message);
            } else {
                showBookmarkFeedback(data.data || 'Error occurred', true);
            }
        })
        .catch(function(error) {
            button.classList.remove('is-loading');
            console.error('Bookmark error:', error);
            showBookmarkFeedback('Connection error', true);
        });
    }

    function syncLocalBookmark(postId, add) {
        let bookmarks = JSON.parse(localStorage.getItem('user_bookmarks') || '[]');
        postId = parseInt(postId);

        if (add) {
            if (!bookmarks.includes(postId)) {
                bookmarks.push(postId);
            }
        } else {
            bookmarks = bookmarks.filter(function(id) {
                return id !== postId;
            });
        }

        localStorage.setItem('user_bookmarks', JSON.stringify(bookmarks));
    }

    function updateHeaderBookmarkBadge(count) {
        const badge = document.querySelector('.bookmark-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }
    }

    function showBookmarkFeedback(message, isError) {
        // Remove existing notification
        const existing = document.querySelector('.bookmark-notification');
        if (existing) existing.remove();

        const notification = document.createElement('div');
        notification.className = 'bookmark-notification' + (isError ? ' is-error' : '');
        notification.innerHTML = '<span>' + message + '</span>';
        notification.style.cssText = 'position:fixed;bottom:100px;left:50%;transform:translateX(-50%);' +
            'background:' + (isError ? '#ef4444' : '#10b981') + ';color:#fff;' +
            'padding:12px 24px;border-radius:8px;font-size:14px;font-weight:500;' +
            'z-index:100000;box-shadow:0 4px 20px rgba(0,0,0,0.2);opacity:0;transition:opacity 0.3s;';

        document.body.appendChild(notification);

        // Fade in
        requestAnimationFrame(function() {
            notification.style.opacity = '1';
        });

        // Fade out and remove
        setTimeout(function() {
            notification.style.opacity = '0';
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 2500);
    }

    /**
     * Table of Contents functionality
     */
    function initTableOfContents() {
        const toc = document.getElementById('table-of-contents');
        if (!toc) return;

        const toggle = toc.querySelector('.toc-toggle');
        const list = toc.querySelector('.toc-list');
        const links = toc.querySelectorAll('.toc-item a');

        // Toggle collapse/expand
        if (toggle && list) {
            toggle.addEventListener('click', function() {
                const isExpanded = toggle.getAttribute('aria-expanded') === 'true';
                toggle.setAttribute('aria-expanded', !isExpanded);
                list.classList.toggle('collapsed');
            });
        }

        // Smooth scroll to heading
        links.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const target = document.querySelector(targetId);
                if (target) {
                    const headerOffset = 100;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });

                    // Update active state
                    links.forEach(function(l) { l.classList.remove('active'); });
                    link.classList.add('active');
                }
            });
        });

        // Highlight active section on scroll
        if (links.length > 0) {
            const headings = [];
            links.forEach(function(link) {
                const id = link.getAttribute('href').substring(1);
                const heading = document.getElementById(id);
                if (heading) headings.push({ id: id, element: heading, link: link });
            });

            let ticking = false;
            window.addEventListener('scroll', function() {
                if (!ticking) {
                    window.requestAnimationFrame(function() {
                        const scrollPos = window.pageYOffset + 150;
                        let activeHeading = null;

                        headings.forEach(function(h) {
                            if (h.element.offsetTop <= scrollPos) {
                                activeHeading = h;
                            }
                        });

                        links.forEach(function(l) { l.classList.remove('active'); });
                        if (activeHeading) {
                            activeHeading.link.classList.add('active');
                        }

                        ticking = false;
                    });
                    ticking = true;
                }
            });
        }
    }

})();
