/**
 * Reading Experience - Progress bar and toolbar visibility
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    let progressBar;
    let toolbar;

    document.addEventListener('DOMContentLoaded', function() {
        // Check if single post (WordPress adds 'single' class, check both for compatibility)
        const isSinglePost = document.body.classList.contains('single-post') ||
                           (document.body.classList.contains('single') && document.body.classList.contains('single-format-standard'));

        if (!isSinglePost) return;

        initProgressBar();
        initToolbarVisibility();
    });

    /**
     * Initialize reading progress bar
     */
    function initProgressBar() {
        // Create progress bar
        progressBar = document.createElement('div');
        progressBar.className = 'reading-progress-bar';
        progressBar.innerHTML = '<div class="reading-progress-fill"></div>';
        document.body.prepend(progressBar);

        // Throttled scroll handler for progress bar (performance)
        let ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateProgressBar();
                    ticking = false;
                });
                ticking = true;
            }
        });

        updateProgressBar(); // Initial update
    }

    /**
     * Update progress bar based on scroll position
     */
    function updateProgressBar() {
        const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const scrolled = (winScroll / height) * 100;

        const fillElement = progressBar.querySelector('.reading-progress-fill');
        fillElement.style.width = scrolled + '%';
    }

    /**
     * Initialize toolbar visibility on scroll
     */
    function initToolbarVisibility() {
        toolbar = document.getElementById('reading-toolbar');
        if (!toolbar) return;

        let lastScrollTop = 0;
        let isVisible = false;
        let ticking = false;

        // Throttled scroll handler (performance optimization)
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;

                    // Show toolbar after scrolling 200px
                    if (scrollTop > 200 && !isVisible) {
                        toolbar.classList.add('is-visible');
                        isVisible = true;
                    }
                    // Hide when at top
                    else if (scrollTop <= 200 && isVisible) {
                        toolbar.classList.remove('is-visible');
                        isVisible = false;
                    }

                    lastScrollTop = scrollTop;
                    ticking = false;
                });
                ticking = true;
            }
        });
    }

})();