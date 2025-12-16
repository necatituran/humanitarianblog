(function() {
    "use strict";
    var progressBar = null, toolbar = null;

    document.addEventListener("DOMContentLoaded", function() {
        var isSinglePost = document.body.classList.contains("single-post") ||
                          document.body.classList.contains("single") ||
                          document.querySelector(".single-article") !== null ||
                          document.querySelector("article.post") !== null;
        if (!isSinglePost) return;
        initProgressBar();
        initToolbarVisibility();
    });

    function initProgressBar() {
        if (document.querySelector(".reading-progress-bar")) {
            progressBar = document.querySelector(".reading-progress-bar");
            return;
        }
        progressBar = document.createElement("div");
        progressBar.className = "reading-progress-bar";
        progressBar.innerHTML = "<div class=\"reading-progress-fill\"></div>";
        document.body.appendChild(progressBar);

        var ticking = false;
        window.addEventListener("scroll", function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    updateProgressBar();
                    ticking = false;
                });
                ticking = true;
            }
        });
        updateProgressBar();
    }

    function updateProgressBar() {
        if (!progressBar) return;
        var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
        var windowHeight = window.innerHeight;
        var endElement = document.getElementById("comments") ||
                        document.querySelector(".comments-area") ||
                        document.querySelector(".article-comments");
        if (!endElement) {
            endElement = document.querySelector(".single-article") ||
                        document.querySelector("article.post") ||
                        document.querySelector(".entry-content");
        }
        if (!endElement) {
            var height = document.documentElement.scrollHeight - windowHeight;
            if (height <= 0) return;
            updateProgressDisplay(Math.min((winScroll / height) * 100, 100));
            return;
        }
        var endElementTop = endElement.getBoundingClientRect().top + winScroll;
        var endPoint = endElementTop - windowHeight;
        if (endPoint <= 0) {
            updateProgressDisplay(100);
            return;
        }
        updateProgressDisplay(Math.min(Math.max((winScroll / endPoint) * 100, 0), 100));
    }

    function updateProgressDisplay(scrolled) {
        var fillElement = progressBar.querySelector(".reading-progress-fill");
        if (fillElement) {
            fillElement.style.height = scrolled + "%";
        }
    }

    function initToolbarVisibility() {
        toolbar = document.getElementById("reading-toolbar");
        if (!toolbar) return;
        var isVisible = false, ticking = false;
        window.addEventListener("scroll", function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    if (scrollTop > 200 && !isVisible) {
                        toolbar.classList.add("is-visible");
                        isVisible = true;
                    } else if (scrollTop <= 200 && isVisible) {
                        toolbar.classList.remove("is-visible");
                        isVisible = false;
                    }
                    ticking = false;
                });
                ticking = true;
            }
        });
        if (window.pageYOffset > 200) {
            toolbar.classList.add("is-visible");
            isVisible = true;
        }
    }
})();
