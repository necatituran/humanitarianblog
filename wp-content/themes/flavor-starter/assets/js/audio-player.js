/**
 * Audio Player with Web Speech API (Text-to-Speech)
 *
 * Uses browser's built-in TTS - completely FREE, no API keys needed
 * Works in Chrome, Firefox, Safari, Edge
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    let synth = window.speechSynthesis;
    let utterance = null;
    let isPlaying = false;
    let isPaused = false;
    let currentRate = 1;
    let resumeInterval = null;
    let chunks = [];
    let currentChunkIndex = 0;

    document.addEventListener('DOMContentLoaded', function() {
        initAudioPlayer();
    });

    /**
     * Initialize audio player
     */
    function initAudioPlayer() {
        const listenButton = document.getElementById('listen-button');
        const listenButtonHero = document.getElementById('listen-button-hero');

        // Check browser support
        if (!synth) {
            if (listenButton) listenButton.style.display = 'none';
            if (listenButtonHero) listenButtonHero.style.display = 'none';
            console.log('Speech synthesis not supported');
            return;
        }

        // If neither button exists, exit
        if (!listenButton && !listenButtonHero) return;

        // Bind click events to both buttons
        if (listenButton) {
            listenButton.addEventListener('click', toggleAudio);
        }
        if (listenButtonHero) {
            listenButtonHero.addEventListener('click', toggleAudio);
        }

        // Audio controls (these are in the hidden audio-player div)
        document.addEventListener('click', function(e) {
            if (e.target.closest('#audio-play-pause')) {
                togglePlayPause();
            }
            if (e.target.closest('#audio-stop')) {
                stopAudio();
            }
        });

        // Speed change
        const speedSelect = document.getElementById('audio-speed');
        if (speedSelect) {
            speedSelect.addEventListener('change', function() {
                currentRate = parseFloat(this.value);
                // If playing, restart with new rate
                if (isPlaying && !isPaused) {
                    restartWithNewRate();
                }
            });
        }
    }

    /**
     * Toggle audio on/off
     */
    function toggleAudio() {
        if (isPlaying) {
            stopAudio();
        } else {
            startAudio();
        }
    }

    /**
     * Get clean text from article
     */
    function getArticleText() {
        // Try multiple selectors for article content
        const articleContent = document.querySelector('.article-content') ||
                              document.querySelector('.entry-content') ||
                              document.querySelector('.post-content');

        if (!articleContent) {
            return null;
        }

        // Clone to avoid modifying the DOM
        const clone = articleContent.cloneNode(true);

        // Remove unwanted elements
        const unwanted = clone.querySelectorAll('script, style, noscript, iframe, .share-buttons, .related-articles, .comments, nav, .navigation');
        unwanted.forEach(el => el.remove());

        // Get text
        let text = clone.innerText || clone.textContent;

        // Clean up the text
        text = text
            .replace(/\s+/g, ' ')           // Multiple spaces to single
            .replace(/\n+/g, '. ')          // Newlines to periods
            .replace(/\.+/g, '.')           // Multiple periods to single
            .replace(/\s+\./g, '.')         // Space before period
            .replace(/\.\s*\./g, '.')       // Double periods
            .trim();

        return text;
    }

    /**
     * Split text into chunks for better reliability
     */
    function splitIntoChunks(text, maxLength = 200) {
        const sentences = text.match(/[^.!?]+[.!?]+/g) || [text];
        const result = [];
        let currentChunk = '';

        for (const sentence of sentences) {
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

    /**
     * Start text-to-speech
     */
    function startAudio() {
        const text = getArticleText();

        if (!text) {
            console.log('No article content found');
            showNotification('Could not find article content');
            return;
        }

        // Split into chunks for better reliability
        chunks = splitIntoChunks(text, 200);
        currentChunkIndex = 0;

        console.log(`TTS: Starting with ${chunks.length} chunks`);

        isPlaying = true;
        isPaused = false;
        updateListenButton(true);
        showAudioPlayer();
        showNotification('Reading article...');

        // Start reading first chunk
        readNextChunk();
    }

    /**
     * Read the next chunk of text
     */
    function readNextChunk() {
        if (currentChunkIndex >= chunks.length) {
            // All chunks read
            stopAudio();
            showNotification('Finished reading');
            return;
        }

        if (!isPlaying || isPaused) {
            return;
        }

        const chunk = chunks[currentChunkIndex];
        utterance = new SpeechSynthesisUtterance(chunk);

        // Get rate from speed selector or use default
        const speedSelect = document.getElementById('audio-speed');
        utterance.rate = speedSelect ? parseFloat(speedSelect.value) : currentRate;

        // Set language
        utterance.lang = document.documentElement.lang || 'en-US';

        // When chunk ends, read next
        utterance.onend = function() {
            currentChunkIndex++;
            // Small delay between chunks for natural flow
            setTimeout(readNextChunk, 100);
        };

        utterance.onerror = function(e) {
            console.error('Speech synthesis error:', e.error, e);
            // Try to continue with next chunk on certain errors
            if (e.error === 'interrupted' || e.error === 'canceled') {
                // These are expected when stopping
                return;
            }
            currentChunkIndex++;
            setTimeout(readNextChunk, 100);
        };

        // Chrome bug workaround: pause and resume to prevent stopping
        clearInterval(resumeInterval);
        resumeInterval = setInterval(function() {
            if (synth && synth.speaking && !synth.paused) {
                synth.pause();
                synth.resume();
            }
        }, 10000);

        // Speak
        synth.speak(utterance);
        updatePlayPauseButton(true);
    }

    /**
     * Stop audio
     */
    function stopAudio() {
        if (synth) {
            synth.cancel();
        }

        clearInterval(resumeInterval);
        resumeInterval = null;

        isPlaying = false;
        isPaused = false;
        utterance = null;
        chunks = [];
        currentChunkIndex = 0;

        hideAudioPlayer();
        updateListenButton(false);
    }

    /**
     * Toggle play/pause
     */
    function togglePlayPause() {
        if (!synth) return;

        if (isPaused) {
            // Resume
            synth.resume();
            isPaused = false;
            updatePlayPauseButton(true);
        } else if (synth.speaking) {
            // Pause
            synth.pause();
            isPaused = true;
            updatePlayPauseButton(false);
        }
    }

    /**
     * Restart with new rate
     */
    function restartWithNewRate() {
        synth.cancel();
        // Restart from current chunk
        setTimeout(readNextChunk, 100);
    }

    /**
     * Show audio player controls
     */
    function showAudioPlayer() {
        const audioPlayer = document.getElementById('audio-player');
        if (audioPlayer) {
            audioPlayer.style.display = 'block';
            audioPlayer.classList.add('is-active');
        }
        updatePlayPauseButton(true);
    }

    /**
     * Hide audio player controls
     */
    function hideAudioPlayer() {
        const audioPlayer = document.getElementById('audio-player');
        if (audioPlayer) {
            audioPlayer.style.display = 'none';
            audioPlayer.classList.remove('is-active');
        }
    }

    /**
     * Update listen button state (both toolbar and hero buttons)
     */
    function updateListenButton(active) {
        const listenButton = document.getElementById('listen-button');
        const listenButtonHero = document.getElementById('listen-button-hero');

        // Update toolbar button
        if (listenButton) {
            if (active) {
                listenButton.classList.add('is-active');
            } else {
                listenButton.classList.remove('is-active');
            }
        }

        // Update hero button
        if (listenButtonHero) {
            const textSpan = listenButtonHero.querySelector('.listen-text');
            if (active) {
                listenButtonHero.classList.add('is-active');
                if (textSpan) textSpan.textContent = 'Stop Listening';
            } else {
                listenButtonHero.classList.remove('is-active');
                if (textSpan) textSpan.textContent = 'Listen to Article';
            }
        }
    }

    /**
     * Update play/pause button UI
     */
    function updatePlayPauseButton(playing) {
        const playIcon = document.querySelector('#audio-player .icon-play');
        const pauseIcon = document.querySelector('#audio-player .icon-pause');

        if (playIcon && pauseIcon) {
            if (playing) {
                playIcon.style.display = 'none';
                pauseIcon.style.display = 'block';
            } else {
                playIcon.style.display = 'block';
                pauseIcon.style.display = 'none';
            }
        }
    }

    /**
     * Show notification
     */
    function showNotification(message) {
        // Remove existing notification
        const existing = document.querySelector('.audio-notification');
        if (existing) existing.remove();

        const notification = document.createElement('div');
        notification.className = 'notification audio-notification show';
        notification.textContent = message;
        notification.style.cssText = 'position:fixed;bottom:100px;left:50%;transform:translateX(-50%);background:#10b981;color:white;padding:12px 24px;border-radius:8px;z-index:10001;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

})();
