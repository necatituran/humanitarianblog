/**
 * Main JavaScript File
 *
 * @package Flavor_Starter
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * Initialize theme
     */
    function init() {
        console.log('Flavor Starter Theme Loaded');

        // Mobile menu toggle will be added in Phase 5
        // Search functionality will be added in Phase 5
        // Other JS features will be added in later phases
    }

    // DOM Ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();
