/**
 * Hero Block JavaScript
 * Handles animations and interactions for the hero section
 */

(function () {
    'use strict';

    function initHeroBlock() {
        const heroBlocks = document.querySelectorAll('.hero-block');

        heroBlocks.forEach((block) => {
            // Observe element visibility for animation triggers
            if ('IntersectionObserver' in window) {
                const observer = new IntersectionObserver(
                    (entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                entry.target.classList.add('is-visible');
                                observer.unobserve(entry.target);
                            }
                        });
                    },
                    { threshold: 0.1 }
                );

                observer.observe(block);
            }
        });
    }

    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHeroBlock);
    } else {
        initHeroBlock();
    }
})();
