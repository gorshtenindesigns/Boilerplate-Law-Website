/**
 * Law Hero Block JavaScript
 * Handles count-up animations for stats and lazy-load observer
 */

(function () {
    'use strict';

    /**
     * Easing function: ease-out-cubic
     */
    function easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 3);
    }

    /**
     * Parse a stat value string to extract number and suffix
     * e.g. "10+" → { num: 10, suffix: "+" }
     * e.g. "500+ Cases" → { num: 500, suffix: "+ Cases" }
     */
    function parseStatValue(str) {
        var match = str.match(/^(\d+(?:,\d+)*(?:\.\d+)?)(.*?)$/);
        if (!match) {
            return { num: 0, suffix: str };
        }
        var numStr = match[1].replace(/,/g, '');
        return {
            num: parseFloat(numStr) || 0,
            suffix: match[2] || '',
        };
    }

    /**
     * Format a number with comma separators
     */
    function formatNumber(n) {
        return Math.floor(n).toLocaleString();
    }

    /**
     * Animate a stat value from 0 to target
     */
    function animateStat(element, targetStr, duration) {
        duration = duration || 1200;

        var parsed = parseStatValue(targetStr);
        var target = parsed.num;
        var suffix = parsed.suffix;
        var startTime = null;

        function frame(currentTime) {
            if (startTime === null) {
                startTime = currentTime;
            }

            var elapsed = currentTime - startTime;
            var progress = Math.min(elapsed / duration, 1);
            var eased = easeOutCubic(progress);
            var current = target * eased;

            element.textContent = formatNumber(current) + suffix;

            if (progress < 1) {
                requestAnimationFrame(frame);
            } else {
                element.textContent = formatNumber(target) + suffix;
            }
        }

        requestAnimationFrame(frame);
    }

    /**
     * Initialize hero blocks on page load
     */
    function initLawHero() {
        var heroSections = document.querySelectorAll('.law-hero');

        if (heroSections.length === 0) {
            return;
        }

        heroSections.forEach(function (heroSection) {
            var statsContainer = heroSection.querySelector('.law-hero__stats');

            if (!statsContainer) {
                return;
            }

            // Only proceed if IntersectionObserver is available
            if (typeof IntersectionObserver === 'undefined') {
                return;
            }

            var animationTriggered = false;

            var observer = new IntersectionObserver(
                function (entries) {
                    entries.forEach(function (entry) {
                        if (entry.isIntersecting && !animationTriggered) {
                            animationTriggered = true;

                            // Check for prefers-reduced-motion
                            var prefersReducedMotion = window.matchMedia(
                                '(prefers-reduced-motion: reduce)'
                            ).matches;

                            if (!prefersReducedMotion) {
                                // Animate each stat value
                                var statValues = statsContainer.querySelectorAll(
                                    '.law-hero__stat-value'
                                );
                                statValues.forEach(function (valueElement, index) {
                                    setTimeout(function () {
                                        var targetValue =
                                            valueElement.getAttribute('data-count') ||
                                            valueElement.textContent;
                                        animateStat(valueElement, targetValue, 1200);
                                    }, index * 100);
                                });
                            }

                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.3 }
            );

            observer.observe(statsContainer);
        });
    }

    /**
     * Wait for DOM to be ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLawHero);
    } else {
        initLawHero();
    }
})();
