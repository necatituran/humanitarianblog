/**
 * Main JavaScript file
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {

        // Initialize all features
        initMobileMenu();
        initSmoothScroll();
        initLazyLoading();
        initCopyLinkButton();
        initBackToTop();

    });

    /**
     * Mobile Menu Toggle
     */
    function initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const navigation = document.querySelector('.site-navigation');
        const body = document.body;

        if (!menuToggle || !navigation) return;

        menuToggle.addEventListener('click', function() {
            const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';

            menuToggle.setAttribute('aria-expanded', !isExpanded);
            navigation.classList.toggle('is-open');
            body.classList.toggle('menu-open');

            // Trap focus in menu when open
            if (!isExpanded) {
                trapFocus(navigation);
            }
        });

        // Close menu on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navigation.classList.contains('is-open')) {
                menuToggle.setAttribute('aria-expanded', 'false');
                navigation.classList.remove('is-open');
                body.classList.remove('menu-open');
                menuToggle.focus();
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (navigation.classList.contains('is-open') &&
                !navigation.contains(e.target) &&
                !menuToggle.contains(e.target)) {
                menuToggle.setAttribute('aria-expanded', 'false');
                navigation.classList.remove('is-open');
                body.classList.remove('menu-open');
            }
        });
    }

    /**
     * Trap focus within element (for accessibility)
     */
    function trapFocus(element) {
        const focusableElements = element.querySelectorAll(
            'a[href], button:not([disabled]), textarea, input, select'
        );

        if (focusableElements.length === 0) return;

        const firstFocusable = focusableElements[0];
        const lastFocusable = focusableElements[focusableElements.length - 1];

        element.addEventListener('keydown', function(e) {
            if (e.key !== 'Tab') return;

            if (e.shiftKey) {
                if (document.activeElement === firstFocusable) {
                    lastFocusable.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastFocusable) {
                    firstFocusable.focus();
                    e.preventDefault();
                }
            }
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

                    const headerOffset = 80; // Account for sticky header
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
     * Uses Intersection Observer API
     */
    function initLazyLoading() {
        const lazyImages = document.querySelectorAll('img[data-src], img[loading="lazy"]');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;

                        // Load image
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }

                        // Load srcset if exists
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                            img.removeAttribute('data-srcset');
                        }

                        img.classList.add('loaded');

                        // Stop observing this image
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px', // Start loading 50px before entering viewport
                threshold: 0.01
            });

            lazyImages.forEach(function(img) {
                imageObserver.observe(img);
            });
        } else {
            // Fallback for browsers without IntersectionObserver
            lazyImages.forEach(function(img) {
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                }
                if (img.dataset.srcset) {
                    img.srcset = img.dataset.srcset;
                }
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

                // Try modern Clipboard API
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

    /**
     * Fallback copy method for older browsers
     */
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

    /**
     * Show feedback after copy
     */
    function showCopyFeedback(button, success) {
        const originalText = button.querySelector('span').textContent;
        const feedbackText = success ? 'Copied!' : 'Failed';

        button.querySelector('span').textContent = feedbackText;
        button.classList.add(success ? 'copy-success' : 'copy-error');

        setTimeout(function() {
            button.querySelector('span').textContent = originalText;
            button.classList.remove('copy-success', 'copy-error');
        }, 2000);
    }

    /**
     * Back to Top button
     */
    function initBackToTop() {
        const backToTopButton = document.querySelector('.back-to-top');

        if (!backToTopButton) {
            // Create button if it doesn't exist
            createBackToTopButton();
            return;
        }

        // Show/hide on scroll
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                backToTopButton.classList.add('is-visible');
            } else {
                backToTopButton.classList.remove('is-visible');
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

    /**
     * Create Back to Top button dynamically
     */
    function createBackToTopButton() {
        const button = document.createElement('button');
        button.className = 'back-to-top';
        button.setAttribute('aria-label', 'Back to top');
        button.innerHTML = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="18 15 12 9 6 15"></polyline></svg>';

        document.body.appendChild(button);

        // Initialize after creation
        initBackToTop();
    }

})();
