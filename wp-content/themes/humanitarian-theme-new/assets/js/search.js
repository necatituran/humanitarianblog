/**
 * Live Search with AJAX
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    let searchTimeout;
    const DEBOUNCE_DELAY = 300;

    document.addEventListener('DOMContentLoaded', function() {
        initLiveSearch();
        initSearchHistory();
    });

    /**
     * Initialize live search
     */
    function initLiveSearch() {
        const searchInputs = document.querySelectorAll('.search-field');

        searchInputs.forEach(function(input) {
            input.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);

                if (query.length < 3) {
                    hideSearchResults();
                    return;
                }

                // Debounce search
                searchTimeout = setTimeout(function() {
                    performSearch(query, input);
                }, DEBOUNCE_DELAY);
            });

            // Enter key: immediate search (bypass debounce)
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const query = this.value.trim();

                    if (query.length >= 3) {
                        clearTimeout(searchTimeout);
                        performSearch(query, input);
                    }
                } else {
                    // Other keyboard navigation
                    handleSearchKeyboard.call(this, e);
                }
            });
        });
    }

    /**
     * Perform AJAX search
     */
    function performSearch(query, inputElement) {
        const resultsContainer = getOrCreateResultsContainer(inputElement);

        // Show loading state
        resultsContainer.innerHTML = '<div class="search-loading">Searching...</div>';
        resultsContainer.classList.add('is-visible');

        // AJAX request
        const formData = new URLSearchParams();
        formData.append('action', 'live_search');
        formData.append('nonce', humanitarianBlogAjax.search_nonce);
        formData.append('query', query);

        fetch(humanitarianBlogAjax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displaySearchResults(data.data, resultsContainer, query);
                saveToSearchHistory(query);
            } else {
                resultsContainer.innerHTML = '<div class="search-error">Search failed. Please try again.</div>';
            }
        })
        .catch(error => {
            console.error('Search error:', error);
            resultsContainer.innerHTML = '<div class="search-error">An error occurred.</div>';
        });
    }

    /**
     * Display search results
     */
    function displaySearchResults(results, container, query) {
        if (!results || results.length === 0) {
            container.innerHTML = '<div class="search-no-results">No results found for "' + escapeHtml(query) + '"</div>';
            return;
        }

        let html = '<ul class="live-search-results">';

        results.forEach(function(result) {
            html += '<li class="search-result-item">';
            html += '<a href="' + result.url + '">';
            if (result.thumbnail) {
                html += '<img src="' + result.thumbnail + '" alt="">';
            }
            html += '<div class="result-content">';
            html += '<span class="result-category">' + result.category + '</span>';
            html += '<h4>' + highlightTerm(result.title, query) + '</h4>';
            html += '<p>' + highlightTerm(result.excerpt, query) + '</p>';
            html += '</div>';
            html += '</a>';
            html += '</li>';
        });

        html += '</ul>';
        container.innerHTML = html;
    }

    /**
     * Highlight search term in text (XSS-safe)
     */
    function highlightTerm(text, term) {
        // Escape HTML in both text and term first (XSS prevention)
        const escapedText = escapeHtml(text);
        const escapedTerm = escapeHtml(term);

        // Then apply highlighting with escaped values
        const regex = new RegExp('(' + escapeRegex(escapedTerm) + ')', 'gi');
        return escapedText.replace(regex, '<mark>$1</mark>');
    }

    /**
     * Escape HTML (XSS prevention)
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Escape regex special characters
     */
    function escapeRegex(text) {
        return text.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    /**
     * Get or create results container
     */
    function getOrCreateResultsContainer(inputElement) {
        let container = inputElement.parentElement.querySelector('.live-search-container');

        if (!container) {
            container = document.createElement('div');
            container.className = 'live-search-container';
            inputElement.parentElement.appendChild(container);
        }

        return container;
    }

    /**
     * Hide search results
     */
    function hideSearchResults() {
        const containers = document.querySelectorAll('.live-search-container');
        containers.forEach(function(container) {
            container.classList.remove('is-visible');
        });
    }

    /**
     * Handle keyboard navigation in search results
     */
    function handleSearchKeyboard(e) {
        const resultsContainer = this.parentElement.querySelector('.live-search-container');
        if (!resultsContainer || !resultsContainer.classList.contains('is-visible')) return;

        const results = resultsContainer.querySelectorAll('.search-result-item a');
        if (results.length === 0) return;

        let currentIndex = -1;
        results.forEach(function(result, index) {
            if (result === document.activeElement) {
                currentIndex = index;
            }
        });

        // Arrow Down
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (currentIndex < results.length - 1) {
                results[currentIndex + 1].focus();
            }
        }
        // Arrow Up
        else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentIndex > 0) {
                results[currentIndex - 1].focus();
            } else {
                this.focus();
            }
        }
        // Escape
        else if (e.key === 'Escape') {
            hideSearchResults();
            this.blur();
        }
    }

    /**
     * Search history with localStorage
     */
    function initSearchHistory() {
        const HISTORY_KEY = 'humanitarian_search_history';
        const MAX_HISTORY = 5;

        window.saveToSearchHistory = function(query) {
            if (!window.localStorage) return;

            let history = JSON.parse(localStorage.getItem(HISTORY_KEY) || '[]');

            // Remove duplicates
            history = history.filter(item => item !== query);

            // Add to beginning
            history.unshift(query);

            // Limit size
            history = history.slice(0, MAX_HISTORY);

            localStorage.setItem(HISTORY_KEY, JSON.stringify(history));
        };

        window.getSearchHistory = function() {
            if (!window.localStorage) return [];
            return JSON.parse(localStorage.getItem(HISTORY_KEY) || '[]');
        };
    }

    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.search-form') && !e.target.closest('.live-search-container')) {
            hideSearchResults();
        }
    });

})();