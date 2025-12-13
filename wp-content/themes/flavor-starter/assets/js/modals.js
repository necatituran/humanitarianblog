/**
 * Modal System for Share, PDF, QR
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

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

        // Trap focus
        trapFocus(modal);

        // Close on Escape
        document.addEventListener('keydown', handleEscapeKey);
    }

    /**
     * Close modal
     */
    function closeModal(modal) {
        modal.classList.remove('is-open');
        document.body.classList.remove('modal-open');
        document.removeEventListener('keydown', handleEscapeKey);
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
            content = `
                <div class="modal-content">
                    <button class="modal-close" aria-label="Close">&times;</button>
                    <h2>Download PDF</h2>
                    <p>Choose PDF format:</p>
                    <div class="pdf-options">
                        <button class="btn btn-primary pdf-download" data-format="standard">Standard (Color)</button>
                        <button class="btn btn-outline pdf-download" data-format="light">Light (B&W)</button>
                        <button class="btn btn-outline pdf-download" data-format="print">Print-Friendly</button>
                    </div>
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

})();