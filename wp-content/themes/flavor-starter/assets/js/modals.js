/**
 * Modal System for Share, PDF, QR
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    // Store escape handler for cleanup
    let escapeKeyHandler = null;

    document.addEventListener('DOMContentLoaded', function() {
        initModalTriggers();
        initModalClose();
        checkBookmarkStatus();
    });

    /**
     * Check if current post is bookmarked and update UI
     */
    function checkBookmarkStatus() {
        if (!window.localStorage) return;

        const bookmarks = JSON.parse(localStorage.getItem('bookmarked_posts') || '[]');
        const saveButtons = document.querySelectorAll('[data-action="save"]');

        saveButtons.forEach(btn => {
            const postId = btn.dataset.postId;
            if (postId && bookmarks.includes(postId)) {
                btn.classList.add('is-bookmarked');
            }
        });
    }

    /**
     * Initialize modal triggers
     */
    function initModalTriggers() {
        // Share button
        const shareButtons = document.querySelectorAll('[data-action="share"]');
        shareButtons.forEach(btn => {
            btn.addEventListener('click', () => openModal('share-modal'));
        });

        // PDF button
        const pdfButtons = document.querySelectorAll('[data-action="pdf"]');
        pdfButtons.forEach(btn => {
            btn.addEventListener('click', () => openModal('pdf-modal'));
        });

        // QR button
        const qrButtons = document.querySelectorAll('[data-action="qr"]');
        qrButtons.forEach(btn => {
            btn.addEventListener('click', () => openModal('qr-modal'));
        });

        // Save button (localStorage bookmark)
        const saveButtons = document.querySelectorAll('[data-action="save"]');
        saveButtons.forEach(btn => {
            btn.addEventListener('click', toggleBookmark);
        });
    }

    /**
     * Open modal
     */
    function openModal(modalId) {
        let modal = document.getElementById(modalId);

        // Create modal if doesn't exist
        if (!modal) {
            modal = createModal(modalId);
        }

        modal.classList.add('is-open');
        document.body.classList.add('modal-open');

        // Generate QR code if it's a QR modal
        if (modalId === 'qr-modal') {
            generateQRCode(modal);
        }

        // Trap focus
        trapFocus(modal);

        // Remove old escape handler if exists (prevent duplicates)
        if (escapeKeyHandler) {
            document.removeEventListener('keydown', escapeKeyHandler);
        }

        // Create new escape handler
        escapeKeyHandler = function(e) {
            handleEscapeKey(e);
        };

        // Attach escape handler
        document.addEventListener('keydown', escapeKeyHandler);
    }

    /**
     * Close modal
     */
    function closeModal(modal) {
        modal.classList.remove('is-open');
        document.body.classList.remove('modal-open');

        // Remove escape handler (cleanup)
        if (escapeKeyHandler) {
            document.removeEventListener('keydown', escapeKeyHandler);
            escapeKeyHandler = null;
        }
    }

    /**
     * Create modal dynamically
     */
    function createModal(modalId) {
        const modal = document.createElement('div');
        modal.id = modalId;
        modal.className = 'modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');

        let content = '';

        if (modalId === 'share-modal') {
            const pageUrl = window.location.href;
            const pageTitle = document.title;
            content = `
                <div class="modal-content">
                    <button class="modal-close" aria-label="Close">&times;</button>
                    <h2>Share this article</h2>
                    <p class="share-subtitle">Spread the word on social media</p>

                    <div class="share-modal-buttons">
                        <a href="https://twitter.com/intent/tweet?url=${encodeURIComponent(pageUrl)}&text=${encodeURIComponent(pageTitle)}"
                           target="_blank" rel="noopener" class="share-btn share-twitter" aria-label="Share on Twitter">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(pageUrl)}"
                           target="_blank" rel="noopener" class="share-btn share-facebook" aria-label="Share on Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(pageUrl)}"
                           target="_blank" rel="noopener" class="share-btn share-linkedin" aria-label="Share on LinkedIn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <button class="share-btn share-copy" aria-label="Copy link" onclick="navigator.clipboard.writeText('${pageUrl}').then(() => { this.innerHTML = '<svg viewBox=\\'0 0 24 24\\' fill=\\'none\\' stroke=\\'currentColor\\' stroke-width=\\'2\\'><polyline points=\\'20 6 9 17 4 12\\'></polyline></svg>'; setTimeout(() => { this.innerHTML = '<svg viewBox=\\'0 0 24 24\\' fill=\\'none\\' stroke=\\'currentColor\\' stroke-width=\\'2\\'><path d=\\'M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71\\'></path><path d=\\'M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71\\'></path></svg>'; }, 2000); })">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="share-copy-section">
                        <label>Or copy link</label>
                        <div class="share-copy-input">
                            <input type="text" value="${pageUrl}" readonly onclick="this.select()">
                            <button onclick="navigator.clipboard.writeText('${pageUrl}'); this.textContent = 'Copied!'; this.classList.add('copied'); setTimeout(() => { this.textContent = 'Copy'; this.classList.remove('copied'); }, 2000)">Copy</button>
                        </div>
                    </div>
                </div>
            `;
        } else if (modalId === 'pdf-modal') {
            const postId = document.querySelector('[data-action="pdf"]')?.dataset.postId || '';
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

            if (isMobile) {
                // Mobile: Show alternative options
                content = `
                    <div class="modal-content">
                        <button class="modal-close" aria-label="Close">&times;</button>
                        <h2>Save Article</h2>
                        <p>To save this article as PDF on mobile:</p>
                        <div class="pdf-mobile-tips">
                            <div class="tip"><strong>iOS Safari:</strong> Tap Share > Print > Pinch to zoom > Share > Save to Files</div>
                            <div class="tip"><strong>Android Chrome:</strong> Menu (3 dots) > Share > Print > Save as PDF</div>
                        </div>
                        <button class="btn btn-primary pdf-download" data-format="print" data-post-id="${postId}">
                            Open Print Dialog
                        </button>
                        <p class="pdf-note">Or use the <strong>Save</strong> button to bookmark this article for later reading.</p>
                    </div>
                `;
            } else {
                // Desktop: Simple print button
                content = `
                    <div class="modal-content">
                        <button class="modal-close" aria-label="Close">&times;</button>
                        <h2>Save as PDF</h2>
                        <p>Click the button below and select "Save as PDF" in the print dialog.</p>
                        <button class="btn btn-primary pdf-download" data-format="print" data-post-id="${postId}">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 8px;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                            </svg>
                            Save as PDF
                        </button>
                        <p class="pdf-note">Tip: In the print dialog, select "Save as PDF" as the destination.</p>
                    </div>
                `;
            }
        } else if (modalId === 'qr-modal') {
            content = `
                <div class="modal-content">
                    <button class="modal-close" aria-label="Close">&times;</button>
                    <h2>QR Code</h2>
                    <div class="qr-code-container">
                        <div id="qr-code">Loading...</div>
                    </div>
                    <p>Scan to share this article</p>
                </div>
            `;
        }

        modal.innerHTML = `<div class="modal-backdrop"></div>` + content;
        document.body.appendChild(modal);

        return modal;
    }

    /**
     * Initialize modal close handlers
     */
    function initModalClose() {
        document.addEventListener('click', function(e) {
            // Close button
            if (e.target.classList.contains('modal-close')) {
                const modal = e.target.closest('.modal');
                closeModal(modal);
            }

            // Backdrop click
            if (e.target.classList.contains('modal-backdrop')) {
                const modal = e.target.closest('.modal');
                closeModal(modal);
            }

            // PDF download button
            if (e.target.closest('.pdf-download')) {
                const button = e.target.closest('.pdf-download');
                handlePDFDownload(button);
            }
        });
    }

    /**
     * Handle Escape key
     */
    function handleEscapeKey(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal.is-open');
            if (openModal) {
                closeModal(openModal);
            }
        }
    }

    /**
     * Trap focus in modal
     */
    function trapFocus(modal) {
        const focusable = modal.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
        const first = focusable[0];
        const last = focusable[focusable.length - 1];

        modal.addEventListener('keydown', function(e) {
            if (e.key !== 'Tab') return;

            if (e.shiftKey) {
                if (document.activeElement === first) {
                    last.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === last) {
                    first.focus();
                    e.preventDefault();
                }
            }
        });

        // Focus first element
        if (first) first.focus();
    }

    /**
     * Toggle bookmark (save button)
     */
    function toggleBookmark(e) {
        const button = e.currentTarget;
        const postId = button.dataset.postId;

        if (!window.localStorage) return;

        let bookmarks = JSON.parse(localStorage.getItem('bookmarked_posts') || '[]');

        if (bookmarks.includes(postId)) {
            // Remove bookmark
            bookmarks = bookmarks.filter(id => id !== postId);
            button.classList.remove('is-bookmarked');
            showNotification('Bookmark removed');
        } else {
            // Add bookmark
            bookmarks.push(postId);
            button.classList.add('is-bookmarked');
            showNotification('Article bookmarked');
        }

        localStorage.setItem('bookmarked_posts', JSON.stringify(bookmarks));

        // Cleanup old bookmarks occasionally (10% chance on each save)
        if (Math.random() < 0.1) {
            cleanupBookmarks();
        }
    }

    /**
     * Cleanup invalid bookmarks (posts that no longer exist)
     */
    function cleanupBookmarks() {
        if (!window.localStorage || typeof humanitarianBlogAjax === 'undefined') return;

        const bookmarks = JSON.parse(localStorage.getItem('bookmarked_posts') || '[]');

        if (bookmarks.length === 0) return;

        // AJAX request to validate bookmark IDs
        fetch(humanitarianBlogAjax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'validate_bookmarks',
                nonce: humanitarianBlogAjax.nonce,
                post_ids: JSON.stringify(bookmarks)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.valid_ids) {
                // Update bookmarks with only valid IDs
                localStorage.setItem('bookmarked_posts', JSON.stringify(data.data.valid_ids));
                console.log('Bookmarks cleaned up:', bookmarks.length, '->', data.data.valid_ids.length);
            }
        })
        .catch(error => {
            console.error('Bookmark cleanup error:', error);
        });
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
     * Generate QR Code via AJAX
     */
    function generateQRCode(modal) {
        const qrContainer = modal.querySelector('#qr-code');

        if (!qrContainer) return;

        // Get post ID from button that triggered the modal
        const qrButton = document.querySelector('[data-action="qr"]');
        const postId = qrButton ? qrButton.dataset.postId : null;

        if (!postId) {
            qrContainer.innerHTML = '<p class="error">Post ID not found</p>';
            return;
        }

        // Check if humanitarianBlogAjax is defined
        if (typeof humanitarianBlogAjax === 'undefined') {
            qrContainer.innerHTML = '<p class="error">AJAX not configured</p>';
            return;
        }

        // Show loading state
        qrContainer.innerHTML = '<div class="qr-loading">Generating QR Code...</div>';

        // AJAX request to generate QR code
        fetch(humanitarianBlogAjax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'generate_qr',
                nonce: humanitarianBlogAjax.nonce,
                post_id: postId,
                size: 'medium'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.qr_code) {
                // Display QR code image
                qrContainer.innerHTML = `
                    <img src="${data.data.qr_code}" alt="QR Code for ${escapeHtml(data.data.post_title)}" class="qr-image" />
                    <p class="qr-url">${escapeHtml(data.data.post_url)}</p>
                `;
            } else {
                qrContainer.innerHTML = '<p class="error">Failed to generate QR code</p>';
            }
        })
        .catch(error => {
            console.error('QR generation error:', error);
            qrContainer.innerHTML = '<p class="error">Network error. Please try again.</p>';
        });
    }

    /**
     * Handle PDF download - Uses browser print-to-PDF
     */
    function handlePDFDownload(button) {
        const format = button.dataset.format;
        const statusDiv = document.getElementById('pdf-status');

        // Close modal first
        const modal = document.getElementById('pdf-modal');
        if (modal) closeModal(modal);

        // Show notification
        showNotification('Opening print dialog - save as PDF');

        // Small delay then open print dialog
        setTimeout(() => {
            window.print();
        }, 300);
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