/**
 * Accessibility Features for Elderly Readers
 * Font size, line spacing, dark mode, high contrast
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Settings storage key
    const STORAGE_KEY = 'humanitarian_a11y_settings';

    // Default settings
    const defaultSettings = {
        fontSize: 100,      // percentage (80-140)
        lineSpacing: 'normal', // 'normal' or 'wide'
        darkMode: false,
        highContrast: false,
        toolbarOpen: false  // Start collapsed
    };

    // Current settings
    let settings = { ...defaultSettings };

    // DOM elements
    let toolbar, fontDecreaseBtn, fontResetBtn, fontIncreaseBtn;
    let spacingNormalBtn, spacingWideBtn, themeToggleBtn, contrastToggleBtn;
    let toolbarToggleBtn;

    /**
     * Initialize accessibility features
     */
    function init() {
        // Load saved settings
        loadSettings();

        // Get DOM elements
        toolbar = document.getElementById('accessibility-toolbar');
        fontDecreaseBtn = document.getElementById('font-decrease');
        fontResetBtn = document.getElementById('font-reset');
        fontIncreaseBtn = document.getElementById('font-increase');
        spacingNormalBtn = document.getElementById('spacing-normal');
        spacingWideBtn = document.getElementById('spacing-wide');
        themeToggleBtn = document.getElementById('theme-toggle');
        contrastToggleBtn = document.getElementById('contrast-toggle');
        toolbarToggleBtn = document.getElementById('a11y-toggle');

        if (!toolbar) return;

        // Bind events
        bindEvents();

        // Apply saved settings
        applySettings();

        // Update button states
        updateButtonStates();
    }

    /**
     * Load settings from localStorage
     */
    function loadSettings() {
        try {
            const saved = localStorage.getItem(STORAGE_KEY);
            if (saved) {
                settings = { ...defaultSettings, ...JSON.parse(saved) };
            }
        } catch (e) {
            console.log('Could not load accessibility settings');
        }
    }

    /**
     * Save settings to localStorage
     */
    function saveSettings() {
        try {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(settings));
        } catch (e) {
            console.log('Could not save accessibility settings');
        }
    }

    /**
     * Bind event listeners
     */
    function bindEvents() {
        // Font size controls
        if (fontDecreaseBtn) {
            fontDecreaseBtn.addEventListener('click', function() {
                changeFontSize(-10);
            });
        }

        if (fontResetBtn) {
            fontResetBtn.addEventListener('click', function() {
                settings.fontSize = 100;
                applyFontSize();
                saveSettings();
                updateButtonStates();
                showFeedback('Font size reset');
            });
        }

        if (fontIncreaseBtn) {
            fontIncreaseBtn.addEventListener('click', function() {
                changeFontSize(10);
            });
        }

        // Line spacing controls
        if (spacingNormalBtn) {
            spacingNormalBtn.addEventListener('click', function() {
                settings.lineSpacing = 'normal';
                applyLineSpacing();
                saveSettings();
                updateButtonStates();
                showFeedback('Normal line spacing');
            });
        }

        if (spacingWideBtn) {
            spacingWideBtn.addEventListener('click', function() {
                settings.lineSpacing = 'wide';
                applyLineSpacing();
                saveSettings();
                updateButtonStates();
                showFeedback('Wide line spacing');
            });
        }

        // Dark mode toggle
        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                settings.darkMode = !settings.darkMode;
                applyDarkMode();
                saveSettings();
                updateButtonStates();
                showFeedback(settings.darkMode ? 'Dark mode on' : 'Light mode on');
            });
        }

        // High contrast toggle
        if (contrastToggleBtn) {
            contrastToggleBtn.addEventListener('click', function() {
                settings.highContrast = !settings.highContrast;
                applyHighContrast();
                saveSettings();
                updateButtonStates();
                showFeedback(settings.highContrast ? 'High contrast on' : 'High contrast off');
            });
        }

        // Toolbar toggle
        if (toolbarToggleBtn) {
            toolbarToggleBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                settings.toolbarOpen = !settings.toolbarOpen;
                toggleToolbar();
                saveSettings();
            });
        }

        // Close panel when clicking outside
        document.addEventListener('click', function(e) {
            if (toolbar && settings.toolbarOpen && !toolbar.contains(e.target)) {
                settings.toolbarOpen = false;
                toggleToolbar();
                saveSettings();
            }
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + A: Toggle accessibility toolbar
            if (e.altKey && e.key === 'a') {
                e.preventDefault();
                settings.toolbarOpen = !settings.toolbarOpen;
                toggleToolbar();
                saveSettings();
            }

            // Alt + Plus: Increase font
            if (e.altKey && (e.key === '+' || e.key === '=')) {
                e.preventDefault();
                changeFontSize(10);
            }

            // Alt + Minus: Decrease font
            if (e.altKey && e.key === '-') {
                e.preventDefault();
                changeFontSize(-10);
            }

            // Alt + D: Toggle dark mode
            if (e.altKey && e.key === 'd') {
                e.preventDefault();
                settings.darkMode = !settings.darkMode;
                applyDarkMode();
                saveSettings();
                updateButtonStates();
            }
        });
    }

    /**
     * Apply all settings
     */
    function applySettings() {
        applyFontSize();
        applyLineSpacing();
        applyDarkMode();
        applyHighContrast();
        toggleToolbar();
    }

    /**
     * Change font size
     */
    function changeFontSize(delta) {
        const newSize = settings.fontSize + delta;

        // Limit range: 80% to 150%
        if (newSize >= 80 && newSize <= 150) {
            settings.fontSize = newSize;
            applyFontSize();
            saveSettings();
            updateButtonStates();
            showFeedback('Font size: ' + settings.fontSize + '%');
        }
    }

    /**
     * Apply font size to document
     */
    function applyFontSize() {
        document.documentElement.style.setProperty('--a11y-font-scale', settings.fontSize / 100);
        document.body.classList.toggle('a11y-font-large', settings.fontSize > 100);
        document.body.classList.toggle('a11y-font-small', settings.fontSize < 100);

        // Apply to specific content areas
        const contentAreas = document.querySelectorAll('.article-content, .entry-content, .page-content, .card-content, .single-article');
        contentAreas.forEach(function(el) {
            el.style.fontSize = settings.fontSize + '%';
        });
    }

    /**
     * Apply line spacing
     */
    function applyLineSpacing() {
        document.body.classList.remove('a11y-spacing-normal', 'a11y-spacing-wide');
        document.body.classList.add('a11y-spacing-' + settings.lineSpacing);
    }

    /**
     * Apply dark mode
     */
    function applyDarkMode() {
        document.body.classList.toggle('dark-mode', settings.darkMode);
        document.documentElement.setAttribute('data-theme', settings.darkMode ? 'dark' : 'light');

        // Update theme toggle icon
        if (themeToggleBtn) {
            const moonIcon = themeToggleBtn.querySelector('.icon-moon');
            const sunIcon = themeToggleBtn.querySelector('.icon-sun');
            const label = themeToggleBtn.querySelector('.theme-label');

            if (moonIcon && sunIcon) {
                moonIcon.style.display = settings.darkMode ? 'none' : 'block';
                sunIcon.style.display = settings.darkMode ? 'block' : 'none';
            }

            if (label) {
                label.textContent = settings.darkMode ? 'Light' : 'Dark';
            }
        }
    }

    /**
     * Apply high contrast mode
     */
    function applyHighContrast() {
        document.body.classList.toggle('high-contrast', settings.highContrast);
    }

    /**
     * Toggle toolbar visibility (new side panel design)
     */
    function toggleToolbar() {
        if (toolbar) {
            toolbar.classList.toggle('is-open', settings.toolbarOpen);

            // Update toggle button aria-expanded
            if (toolbarToggleBtn) {
                toolbarToggleBtn.setAttribute('aria-expanded', settings.toolbarOpen ? 'true' : 'false');
            }
        }
    }

    /**
     * Update button active states
     */
    function updateButtonStates() {
        // Font size buttons
        if (fontDecreaseBtn) {
            fontDecreaseBtn.disabled = settings.fontSize <= 80;
            fontDecreaseBtn.classList.toggle('disabled', settings.fontSize <= 80);
        }

        if (fontIncreaseBtn) {
            fontIncreaseBtn.disabled = settings.fontSize >= 150;
            fontIncreaseBtn.classList.toggle('disabled', settings.fontSize >= 150);
        }

        if (fontResetBtn) {
            fontResetBtn.classList.toggle('active', settings.fontSize === 100);
        }

        // Line spacing buttons
        if (spacingNormalBtn) {
            spacingNormalBtn.classList.toggle('active', settings.lineSpacing === 'normal');
        }

        if (spacingWideBtn) {
            spacingWideBtn.classList.toggle('active', settings.lineSpacing === 'wide');
        }

        // Theme toggle
        if (themeToggleBtn) {
            themeToggleBtn.classList.toggle('active', settings.darkMode);
        }

        // Contrast toggle
        if (contrastToggleBtn) {
            contrastToggleBtn.classList.toggle('active', settings.highContrast);
        }
    }

    /**
     * Show feedback notification
     */
    function showFeedback(message) {
        // Remove existing notification
        const existing = document.querySelector('.a11y-feedback');
        if (existing) existing.remove();

        const notification = document.createElement('div');
        notification.className = 'a11y-feedback';
        notification.setAttribute('role', 'status');
        notification.setAttribute('aria-live', 'polite');
        notification.textContent = message;

        document.body.appendChild(notification);

        // Animate in
        requestAnimationFrame(function() {
            notification.classList.add('is-visible');
        });

        // Remove after delay
        setTimeout(function() {
            notification.classList.remove('is-visible');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 2000);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    // System dark mode preference - DISABLED
    // Site always starts in light mode, user can manually switch to dark mode
    // The setting is saved in localStorage and persists across visits

    /*
    // Uncomment below to enable system preference detection
    if (window.matchMedia) {
        const darkModeQuery = window.matchMedia('(prefers-color-scheme: dark)');

        // Only apply system preference if user hasn't set a preference
        if (!localStorage.getItem(STORAGE_KEY)) {
            if (darkModeQuery.matches) {
                settings.darkMode = true;
                applyDarkMode();
            }
        }

        // Listen for system preference changes
        darkModeQuery.addEventListener('change', function(e) {
            // Only auto-switch if user hasn't manually set preference
            const saved = localStorage.getItem(STORAGE_KEY);
            if (!saved) {
                settings.darkMode = e.matches;
                applyDarkMode();
                updateButtonStates();
            }
        });
    }
    */

})();
