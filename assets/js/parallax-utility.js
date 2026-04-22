/**
 * Parallax Utility
 * 
 * Usage:
 * Add data-parallax-range="number" to any element.
 * Optional: data-parallax-direction="y" or "x" (default is "y")
 * 
 * Example: <div data-parallax-range="100">...</div>
 */

document.addEventListener("DOMContentLoaded", function () {
  const parallaxElements = document.querySelectorAll("[data-parallax-range]");

  parallaxElements.forEach((el) => {
    const range = parseFloat(el.getAttribute("data-parallax-range")) || 100;
    const direction = el.getAttribute("data-parallax-direction") === "x" ? "x" : "y";

    gsap.to(el, {
      [direction]: range,
      ease: "none",
      scrollTrigger: {
        trigger: el,
        start: "top bottom", // Start when the element's top hits the bottom of the viewport
        end: "bottom top",   // End when the element's bottom hits the top of the viewport
        scrub: true,         // Smoothly link animation to scroll
      },
    });
  });
});
