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
    });

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
            content = `
                <div class="modal-content">
                    <button class="modal-close" aria-label="Close">&times;</button>
                    <h2>Share this article</h2>
                    ${document.querySelector('.share-buttons')?.innerHTML || ''}
                </div>
            `;
        } else if (modalId === 'pdf-modal') {
            const postId = document.querySelector('[data-action="pdf"]')?.dataset.postId || '';
            content = `
                <div class="modal-content">
                    <button class="modal-close" aria-label="Close">&times;</button>
                    <h2>Download PDF</h2>
                    <p>Choose PDF format:</p>
                    <div class="pdf-options">
                        <button class="btn btn-primary pdf-download" data-format="standard" data-post-id="${postId}">
                            <strong>Standard</strong><br>
                            <small>Full color with images</small>
                        </button>
                        <button class="btn btn-outline pdf-download" data-format="light" data-post-id="${postId}">
                            <strong>Light</strong><br>
                            <small>Black & white, no images</small>
                        </button>
                        <button class="btn btn-outline pdf-download" data-format="print" data-post-id="${postId}">
                            <strong>Print-Friendly</strong><br>
                            <small>Optimized for printing</small>
                        </button>
                    </div>
                    <div id="pdf-status"></div>
                </div>
            `;
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
     * Handle PDF download
     */
    function handlePDFDownload(button) {
        const format = button.dataset.format;
        const postId = button.dataset.postId;
        const statusDiv = document.getElementById('pdf-status');

        if (!postId) {
            if (statusDiv) statusDiv.innerHTML = '<p class="error">Post ID not found</p>';
            return;
        }

        if (typeof humanitarianBlogAjax === 'undefined') {
            if (statusDiv) statusDiv.innerHTML = '<p class="error">AJAX not configured</p>';
            return;
        }

        // Disable all PDF buttons
        const allButtons = document.querySelectorAll('.pdf-download');
        allButtons.forEach(btn => btn.disabled = true);

        // Show loading state
        if (statusDiv) statusDiv.innerHTML = '<p class="loading">Generating PDF... This may take a moment.</p>';

        // AJAX request to generate PDF
        fetch(humanitarianBlogAjax.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'generate_pdf',
                nonce: humanitarianBlogAjax.nonce,
                post_id: postId,
                format: format
            })
        })
        .then(response => response.json())
        .then(data => {
            // Re-enable buttons
            allButtons.forEach(btn => btn.disabled = false);

            if (data.success && data.data.file_url) {
                // Show success message
                if (statusDiv) {
                    statusDiv.innerHTML = `<p class="success">PDF generated successfully!</p>`;
                }

                // Trigger download
                const link = document.createElement('a');
                link.href = data.data.file_url;
                link.download = '';
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Show notification
                showNotification('PDF download started');

                // Close modal after 1 second
                setTimeout(() => {
                    const modal = document.getElementById('pdf-modal');
                    if (modal) closeModal(modal);
                }, 1000);

            } else {
                const errorMsg = data.data || 'Failed to generate PDF';
                if (statusDiv) {
                    statusDiv.innerHTML = `<p class="error">${escapeHtml(errorMsg)}</p>`;
                }
            }
        })
        .catch(error => {
            console.error('PDF generation error:', error);
            allButtons.forEach(btn => btn.disabled = false);
            if (statusDiv) {
                statusDiv.innerHTML = '<p class="error">Network error. Please try again.</p>';
            }
        });
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