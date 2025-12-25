/**
 * HumanitarianBlog Theme - Main JavaScript
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    /**
     * Menu toggle functionality
     */
    window.toggleMenu = function() {
        const menuOverlay = document.getElementById('menuOverlay');
        if (!menuOverlay) return;

        menuOverlay.classList.toggle('active');
        document.body.style.overflow = menuOverlay.classList.contains('active') ? 'hidden' : '';

        // Update ARIA attributes
        const isOpen = menuOverlay.classList.contains('active');
        menuOverlay.setAttribute('aria-hidden', !isOpen);

        // Focus management
        if (isOpen) {
            const closeButton = menuOverlay.querySelector('.menu-overlay__close');
            if (closeButton) {
                closeButton.focus();
            }
        }
    };

    /**
     * Search overlay toggle functionality
     */
    window.toggleSearch = function() {
        const searchOverlay = document.getElementById('searchOverlay');
        if (!searchOverlay) return;

        searchOverlay.classList.toggle('is-active');
        document.body.style.overflow = searchOverlay.classList.contains('is-active') ? 'hidden' : '';

        // Focus search input when opened
        if (searchOverlay.classList.contains('is-active')) {
            const searchInput = searchOverlay.querySelector('.search-field');
            if (searchInput) {
                setTimeout(function() {
                    searchInput.focus();
                }, 100);
            }
        }
    };

    /**
     * Close menu/search on escape key
     */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const menuOverlay = document.getElementById('menuOverlay');
            if (menuOverlay && menuOverlay.classList.contains('active')) {
                toggleMenu();
            }
            const searchOverlay = document.getElementById('searchOverlay');
            if (searchOverlay && searchOverlay.classList.contains('is-active')) {
                toggleSearch();
            }
        }
    });

    /**
     * Header scroll effect
     */
    function handleScroll() {
        const header = document.getElementById('header');
        if (!header) return;

        if (window.scrollY > 40) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }

    // Throttle scroll event for performance
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        if (scrollTimeout) {
            window.cancelAnimationFrame(scrollTimeout);
        }
        scrollTimeout = window.requestAnimationFrame(handleScroll);
    }, { passive: true });

    /**
     * Smooth scroll for anchor links
     */
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    /**
     * Lazy load images with IntersectionObserver
     */
    function lazyLoadImages() {
        const images = document.querySelectorAll('img[loading="lazy"]');

        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries, observer) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px'
            });

            images.forEach(function(img) {
                imageObserver.observe(img);
            });
        }
    }

    /**
     * Add click handlers for card articles (clickable areas)
     */
    function initCardClicks() {
        const cards = document.querySelectorAll('[data-href]');
        cards.forEach(function(card) {
            card.addEventListener('click', function() {
                const href = this.dataset.href;
                if (href) {
                    window.location = href;
                }
            });
        });
    }

    /**
     * Handle search functionality
     */
    function initSearch() {
        const searchBtn = document.querySelector('.header-search-btn');
        const searchMobile = document.querySelector('.header-search-mobile');

        if (searchBtn) {
            searchBtn.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSearch();
            });
        }

        if (searchMobile) {
            searchMobile.addEventListener('click', function(e) {
                e.preventDefault();
                toggleSearch();
            });
        }
    }

    /**
     * Reading Progress Bar
     */
    function initReadingProgress() {
        const progressBar = document.getElementById('readingProgress');
        if (!progressBar) return;

        const article = document.querySelector('.single-post__content');
        if (!article) return;

        function updateProgress() {
            const articleRect = article.getBoundingClientRect();
            const articleTop = articleRect.top + window.scrollY;
            const articleHeight = article.offsetHeight;
            const windowHeight = window.innerHeight;
            const scrollY = window.scrollY;

            // Calculate progress
            const start = articleTop - windowHeight;
            const end = articleTop + articleHeight - windowHeight;
            const progress = Math.min(Math.max((scrollY - start) / (end - start), 0), 1);

            progressBar.style.width = (progress * 100) + '%';
        }

        window.addEventListener('scroll', updateProgress, { passive: true });
        updateProgress();
    }

    /**
     * Copy to clipboard functionality
     */
    window.copyToClipboard = function() {
        const url = window.location.href;
        const button = document.querySelector('.social-share__button--copy');

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(url).then(function() {
                showCopySuccess(button);
            }).catch(function() {
                fallbackCopy(url, button);
            });
        } else {
            fallbackCopy(url, button);
        }
    };

    function fallbackCopy(text, button) {
        const textarea = document.createElement('textarea');
        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        try {
            document.execCommand('copy');
            showCopySuccess(button);
        } catch (err) {
            console.error('Copy failed:', err);
        }
        document.body.removeChild(textarea);
    }

    function showCopySuccess(button) {
        if (!button) return;
        button.classList.add('copied');
        button.innerHTML = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>';

        setTimeout(function() {
            button.classList.remove('copied');
            button.innerHTML = '<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>';
        }, 2000);
    }

    /**
     * Language Dropdown Toggle
     */
    function initLanguageDropdown() {
        const dropdown = document.querySelector('.language-dropdown');
        const toggle = document.getElementById('langDropdownBtn');
        const menu = document.getElementById('langDropdownMenu');

        if (!dropdown || !toggle || !menu) return;

        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdown.classList.toggle('open');
            toggle.setAttribute('aria-expanded', dropdown.classList.contains('open'));
        });

        // Close on outside click
        document.addEventListener('click', function(e) {
            if (!dropdown.contains(e.target)) {
                dropdown.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });

        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && dropdown.classList.contains('open')) {
                dropdown.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /**
     * PDF Download functionality
     */
    function initPDFButton() {
        const pdfBtn = document.getElementById('action-pdf-btn');
        if (!pdfBtn) return;

        pdfBtn.addEventListener('click', function() {
            // Use browser print to PDF
            window.print();
        });
    }

    /**
     * Voice Article (Text-to-Speech) functionality with player controls
     */
    function initVoiceButton() {
        const voiceBtn = document.getElementById('action-voice-btn');
        const voicePlayer = document.getElementById('voice-player');
        const playPauseBtn = document.getElementById('voice-play-pause');
        const stopBtn = document.getElementById('voice-stop');
        const speedSelect = document.getElementById('voice-speed');

        if (!voiceBtn) return;

        let speaking = false;
        let paused = false;
        let utterance = null;
        let chunks = [];
        let currentChunkIndex = 0;

        // Split text into chunks for better reliability
        function splitIntoChunks(text, maxLength) {
            maxLength = maxLength || 200;
            var sentences = text.match(/[^.!?]+[.!?]+/g) || [text];
            var result = [];
            var currentChunk = '';
            for (var i = 0; i < sentences.length; i++) {
                var sentence = sentences[i];
                if ((currentChunk + sentence).length > maxLength && currentChunk.length > 0) {
                    result.push(currentChunk.trim());
                    currentChunk = sentence;
                } else {
                    currentChunk += sentence;
                }
            }
            if (currentChunk.trim().length > 0) {
                result.push(currentChunk.trim());
            }
            return result;
        }

        function readNextChunk() {
            if (currentChunkIndex >= chunks.length) {
                stopSpeaking();
                return;
            }
            if (!speaking || paused) return;

            var chunk = chunks[currentChunkIndex];
            utterance = new SpeechSynthesisUtterance(chunk);
            utterance.rate = speedSelect ? parseFloat(speedSelect.value) : 1;
            utterance.lang = document.documentElement.lang || 'en-US';

            utterance.onend = function() {
                currentChunkIndex++;
                setTimeout(readNextChunk, 100);
            };

            utterance.onerror = function(e) {
                if (e.error === 'interrupted' || e.error === 'canceled') return;
                currentChunkIndex++;
                setTimeout(readNextChunk, 100);
            };

            window.speechSynthesis.speak(utterance);
        }

        function startSpeaking() {
            if (!('speechSynthesis' in window)) {
                alert('Text-to-speech is not supported in your browser.');
                return;
            }

            var content = document.querySelector('.entry-content');
            if (!content) return;

            var text = content.innerText
                .replace(/\s+/g, ' ')
                .replace(/\n+/g, '. ')
                .trim();

            chunks = splitIntoChunks(text, 200);
            currentChunkIndex = 0;
            speaking = true;
            paused = false;

            voiceBtn.classList.add('is-playing');
            voiceBtn.querySelector('span').textContent = 'Playing...';
            if (voicePlayer) {
                voicePlayer.classList.add('is-active');
            }
            updatePlayPauseIcon(true);
            readNextChunk();
        }

        function stopSpeaking() {
            window.speechSynthesis.cancel();
            speaking = false;
            paused = false;
            chunks = [];
            currentChunkIndex = 0;

            voiceBtn.classList.remove('is-playing');
            voiceBtn.querySelector('span').textContent = 'Voice Article';
            if (voicePlayer) {
                voicePlayer.classList.remove('is-active');
            }
            updatePlayPauseIcon(false);
        }

        function togglePause() {
            if (paused) {
                window.speechSynthesis.resume();
                paused = false;
                updatePlayPauseIcon(true);
            } else if (speaking) {
                window.speechSynthesis.pause();
                paused = true;
                updatePlayPauseIcon(false);
            }
        }

        function updatePlayPauseIcon(isPlaying) {
            if (!playPauseBtn) return;
            var playIcon = playPauseBtn.querySelector('.icon-play');
            var pauseIcon = playPauseBtn.querySelector('.icon-pause');
            if (playIcon && pauseIcon) {
                playIcon.style.display = isPlaying ? 'none' : 'block';
                pauseIcon.style.display = isPlaying ? 'block' : 'none';
            }
        }

        // Voice Article button click - start/stop
        voiceBtn.addEventListener('click', function() {
            if (speaking) {
                stopSpeaking();
            } else {
                startSpeaking();
            }
        });

        // Play/Pause button
        if (playPauseBtn) {
            playPauseBtn.addEventListener('click', function() {
                if (!speaking) {
                    startSpeaking();
                } else {
                    togglePause();
                }
            });
        }

        // Stop button
        if (stopBtn) {
            stopBtn.addEventListener('click', stopSpeaking);
        }

        // Speed change
        if (speedSelect) {
            speedSelect.addEventListener('change', function() {
                if (speaking && !paused) {
                    window.speechSynthesis.cancel();
                    setTimeout(readNextChunk, 100);
                }
            });
        }
    }

    /**
     * QR Code functionality
     */
    function initQRButton() {
        const qrBtn = document.getElementById('action-qr-btn');
        if (!qrBtn) return;

        qrBtn.addEventListener('click', function() {
            const url = encodeURIComponent(window.location.href);
            const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + url;

            // Create modal
            const modal = document.createElement('div');
            modal.className = 'qr-modal';
            modal.innerHTML = `
                <div class="qr-modal__overlay"></div>
                <div class="qr-modal__content">
                    <button class="qr-modal__close" aria-label="Close">&times;</button>
                    <h3>Scan QR Code</h3>
                    <img src="${qrUrl}" alt="QR Code for this article" />
                    <p>Scan to open this article on your mobile device</p>
                </div>
            `;
            document.body.appendChild(modal);

            // Show modal
            setTimeout(function() { modal.classList.add('active'); }, 10);

            // Close handlers
            modal.querySelector('.qr-modal__close').addEventListener('click', closeModal);
            modal.querySelector('.qr-modal__overlay').addEventListener('click', closeModal);

            function closeModal() {
                modal.classList.remove('active');
                setTimeout(function() { modal.remove(); }, 300);
            }
        });
    }

    /**
     * Initialize on DOM ready
     */
    document.addEventListener('DOMContentLoaded', function() {
        handleScroll();
        lazyLoadImages();
        initCardClicks();
        initSearch();
        initReadingProgress();
        initLanguageDropdown();
        initPDFButton();
        initVoiceButton();
        initQRButton();

        // Add loaded class to body
        document.body.classList.add('loaded');
    });

    /**
     * Handle window resize
     */
    let resizeTimeout;
    window.addEventListener('resize', function() {
        if (resizeTimeout) {
            window.cancelAnimationFrame(resizeTimeout);
        }
        resizeTimeout = window.requestAnimationFrame(function() {
            // Close menu if window is resized to desktop
            if (window.innerWidth >= 1024) {
                const menuOverlay = document.getElementById('menuOverlay');
                if (menuOverlay && menuOverlay.classList.contains('active')) {
                    toggleMenu();
                }
            }
        });
    });

})();
