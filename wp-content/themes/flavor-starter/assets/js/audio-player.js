/**
 * Audio Player with Web Speech API (Text-to-Speech)
 *
 * @package HumanitarianBlog
 * @since 1.0.0
 */

(function() {
    'use strict';

    let synth = window.speechSynthesis;
    let utterance;
    let isPlaying = false;

    document.addEventListener('DOMContentLoaded', function() {
        initAudioPlayer();
    });

    /**
     * Initialize audio player
     */
    function initAudioPlayer() {
        const listenButton = document.getElementById('listen-button');
        if (!listenButton) return;

        // Check browser support
        if (!synth) {
            listenButton.style.display = 'none';
            return;
        }

        listenButton.addEventListener('click', toggleAudio);

        // Audio controls
        const playPauseBtn = document.getElementById('audio-play-pause');
        const stopBtn = document.getElementById('audio-stop');
        const speedSelect = document.getElementById('audio-speed');

        if (playPauseBtn) playPauseBtn.addEventListener('click', togglePlayPause);
        if (stopBtn) stopBtn.addEventListener('click', stopAudio);
        if (speedSelect) speedSelect.addEventListener('change', changeSpeed);
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
     * Start text-to-speech
     */
    function startAudio() {
        const articleContent = document.querySelector('.entry-content');
        if (!articleContent) return;

        // Get text content
        const text = articleContent.innerText;

        // Create utterance
        utterance = new SpeechSynthesisUtterance(text);
        utterance.rate = parseFloat(document.getElementById('audio-speed').value);
        utterance.lang = document.documentElement.lang || 'en-US';

        // Event listeners
        utterance.onend = function() {
            stopAudio();
        };

        // Start speaking
        synth.speak(utterance);
        isPlaying = true;

        // Show audio player
        document.getElementById('audio-player').style.display = 'block';
        updatePlayPauseButton(true);
    }

    /**
     * Stop audio
     */
    function stopAudio() {
        if (synth) {
            synth.cancel();
        }
        isPlaying = false;

        // Hide audio player
        const audioPlayer = document.getElementById('audio-player');
        if (audioPlayer) {
            audioPlayer.style.display = 'none';
        }

        updatePlayPauseButton(false);
    }

    /**
     * Toggle play/pause
     */
    function togglePlayPause() {
        if (synth.paused) {
            synth.resume();
            updatePlayPauseButton(true);
        } else if (synth.speaking) {
            synth.pause();
            updatePlayPauseButton(false);
        }
    }

    /**
     * Change playback speed
     */
    function changeSpeed() {
        if (utterance) {
            const newRate = parseFloat(this.value);
            // Need to restart with new rate
            const wasPaused = synth.paused;
            stopAudio();
            if (!wasPaused) {
                startAudio();
            }
        }
    }

    /**
     * Update play/pause button UI
     */
    function updatePlayPauseButton(playing) {
        const playIcon = document.querySelector('.icon-play');
        const pauseIcon = document.querySelector('.icon-pause');

        if (playing) {
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
        } else {
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        }
    }

})();