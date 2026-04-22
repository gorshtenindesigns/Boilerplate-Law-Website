/**
 * Homepage Reveal Animations
 * Varied GSAP ScrollTrigger reveals for homepage components.
 */
document.addEventListener('DOMContentLoaded', () => {
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') return;

    gsap.registerPlugin(ScrollTrigger);

    // 2. Benefits Section Reveal
    const benefitsHeader = document.querySelector('.benefits-section__header');
    const benefitsLeft = document.querySelector('.benefits-section__left');
    const benefitsAccordions = gsap.utils.toArray('.benefits-section .accordion__item');

    if (benefitsHeader) {
        gsap.to(benefitsHeader, {
            scrollTrigger: {
                trigger: benefitsHeader,
                start: "top 92%",
                once: true
            },
            y: 0,
            opacity: 1,
            duration: 0.6
        });
    }

    if (benefitsLeft) {
        gsap.to(benefitsLeft, {
            scrollTrigger: {
                trigger: benefitsLeft,
                start: "top 90%",
                once: true
            },
            x: 0,
            opacity: 1,
            duration: 0.8,
            ease: "power2.out"
        });
    }

    if (benefitsAccordions.length) {
        // Individual triggers for accordions to ensure they don't pop in if the right column is tall
        benefitsAccordions.forEach((item) => {
            gsap.to(item, {
                scrollTrigger: {
                    trigger: item,
                    start: "top 92%",
                    once: true
                },
                x: 0,
                opacity: 1,
                duration: 0.6,
                ease: "power2.out"
            });
        });
    }

    // 3. Services Carousel Stagger
    const serviceCards = gsap.utils.toArray('.services-carousel .carousel-card');
    if (serviceCards.length) {
        gsap.to(serviceCards, {
            scrollTrigger: {
                trigger: '.services-carousel__track-container',
                start: "top 90%",
                once: true
            },
            scale: 1,
            opacity: 1,
            duration: 0.8,
            stagger: 0.15,
            ease: "back.out(1.4)"
        });
    }

    // 4. Info Accordion Reveal
    const infoTextBlocks = gsap.utils.toArray('.info-accordion__content-blocks p');
    if (infoTextBlocks.length) {
        infoTextBlocks.forEach((p) => {
            gsap.to(p, {
                scrollTrigger: {
                    trigger: p,
                    start: "top 92%",
                    once: true
                },
                y: 0,
                opacity: 0.8,
                duration: 0.6,
                ease: "power2.out"
            });
        });
    }

    // 6. Why Us Section Stagger - Individual for accuracy
    const whyUsItems = gsap.utils.toArray('.why-us-section .accordion__item');
    if (whyUsItems.length) {
        whyUsItems.forEach((item) => {
            gsap.to(item, {
                scrollTrigger: {
                    trigger: item,
                    start: "top 92%",
                    once: true
                },
                y: 0,
                opacity: 1,
                duration: 0.7,
                ease: "power2.out"
            });
        });
    }

    // 7. Steps Section Stagger - Batch for grids
    const stepCards = gsap.utils.toArray('.steps-section .step-card');
    if (stepCards.length) {
        ScrollTrigger.batch(stepCards, {
            onEnter: batch => gsap.to(batch, {
                opacity: 1,
                x: 0,
                stagger: 0.15,
                duration: 0.6,
                ease: "power2.out",
                overwrite: true
            }),
            start: "top 90%",
            once: true
        });
    }

    // Refresh ScrollTrigger after all initializations
    ScrollTrigger.refresh();
    console.log('Homepage reveal animations refined with individual triggers and batching');
});
