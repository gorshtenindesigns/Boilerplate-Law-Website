/**
 * Global Divider Animations
 * Animates all elements with .divider, .separator, or class containing "__divider" or "__separator" on scroll.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Ensure GSAP and ScrollTrigger are loaded
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        console.warn('GSAP or ScrollTrigger not found for global animations');
        return;
    }

    // Register ScrollTrigger plugin
    gsap.registerPlugin(ScrollTrigger);

    // Target dividers and separators
    const allDividers = gsap.utils.toArray('.divider, .separator, [class*="__divider"], [class*="__separator"]');
    
    if (allDividers.length) {
        allDividers.forEach((divider) => {
            // Check if already initialized to avoid duplicate triggers
            if (divider.dataset.dividerInit) return;
            divider.dataset.dividerInit = 'true';

            // Ensure it starts from scaleX: 0 if not already set by CSS
            // but we use gsap.to so if it's already 0 it won't flash.
            // If it's already 1, we should probably set it to 0 first to animate it.
            // But let's stick to the ones we intended to hide.
            
            gsap.to(divider, {
                scrollTrigger: {
                    trigger: divider,
                    start: "top 92%", // Trigger when the divider itself enters
                    once: true,
                },
                scaleX: 1,
                duration: 1,
                ease: "power2.out",
                delay: 0.1
            });
        });
    }
});
