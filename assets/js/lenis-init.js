// Lenis smooth scroll initialization + GSAP ScrollTrigger integration
window.lenis = new Lenis({
  duration: 1.2,
  easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
  smooth: true,
});

// Connect Lenis to GSAP ScrollTrigger so ST-based animations stay in sync
lenis.on("scroll", ScrollTrigger.update);

gsap.ticker.add((time) => {
  lenis.raf(time * 1000);
});

gsap.ticker.lagSmoothing(0);

window.boilerplateScrollTo = (target, options = {}) => {
  const offset = Number.isFinite(options.offset) ? options.offset : 0;
  const duration =
    typeof options.duration === "number" ? options.duration : 1.2;

  if (window.lenis && typeof window.lenis.scrollTo === "function") {
    window.lenis.scrollTo(target, {
      offset,
      duration,
    });
    return true;
  }

  if (typeof target === "number") {
    window.scrollTo({
      top: target,
      behavior: "smooth",
    });
    return true;
  }

  if (target instanceof Element) {
    const top =
      target.getBoundingClientRect().top + window.pageYOffset + offset;
    window.scrollTo({
      top,
      behavior: "smooth",
    });
    return true;
  }

  return false;
};

window.boilerplateModalMotion = (() => {
  const easing = "cubic-bezier(0.22, 1, 0.36, 1)";
  const states = new WeakMap();

  const restoreInlineStyle = (element, styleText) => {
    if (!(element instanceof HTMLElement)) {
      return;
    }

    if (typeof styleText === "string") {
      element.setAttribute("style", styleText);
      return;
    }

    element.removeAttribute("style");
  };

  const getRect = (element) => {
    if (!(element instanceof Element) || !element.isConnected) {
      return null;
    }

    const rect = element.getBoundingClientRect();

    if (rect.width <= 0 || rect.height <= 0) {
      return null;
    }

    return {
      top: rect.top,
      left: rect.left,
      width: rect.width,
      height: rect.height,
    };
  };

  const preparePanel = (panel, rect, radius, duration) => {
    panel.style.position = "fixed";
    panel.style.inset = "auto";
    panel.style.top = `${rect.top}px`;
    panel.style.left = `${rect.left}px`;
    panel.style.width = `${rect.width}px`;
    panel.style.height = `${rect.height}px`;
    panel.style.maxWidth = "none";
    panel.style.maxHeight = "none";
    panel.style.margin = "0";
    panel.style.transform = "none";
    panel.style.borderRadius = radius;
    panel.style.willChange =
      "top, left, width, height, border-radius, transform";
    panel.style.transition = [
      `top ${duration}ms ${easing}`,
      `left ${duration}ms ${easing}`,
      `width ${duration}ms ${easing}`,
      `height ${duration}ms ${easing}`,
      `border-radius ${duration}ms ${easing}`,
    ].join(", ");
  };

  const open = ({
    modal,
    panel,
    trigger,
    duration = 460,
    showModal,
    hideModal,
    activateModal,
    onAfterOpen,
  }) => {
    if (!(modal instanceof HTMLElement) || !(panel instanceof HTMLElement)) {
      return false;
    }

    const state = states.get(modal) || {};
    window.clearTimeout(state.openTimer);
    window.clearTimeout(state.closeTimer);

    state.trigger = trigger instanceof HTMLElement ? trigger : null;
    state.phase = "opening";
    state.panelStyle = panel.getAttribute("style");
    states.set(modal, state);

    if (typeof showModal === "function") {
      showModal();
    } else {
      modal.hidden = false;
    }

    const targetRect = getRect(panel);
    const sourceRect = getRect(state.trigger);
    const panelRadius = window.getComputedStyle(panel).borderRadius || "0px";
    const sourceRadius =
      state.trigger instanceof HTMLElement
        ? window.getComputedStyle(state.trigger).borderRadius || panelRadius
        : panelRadius;

    if (!targetRect || !sourceRect) {
      if (typeof activateModal === "function") {
        activateModal();
      } else {
        modal.classList.add("is-active");
      }

      state.phase = "open";

      if (typeof onAfterOpen === "function") {
        onAfterOpen();
      }

      return true;
    }

    preparePanel(panel, sourceRect, sourceRadius, duration);

    if (typeof activateModal === "function") {
      activateModal();
    } else {
      modal.classList.add("is-active");
    }

    window.requestAnimationFrame(() => {
      panel.style.top = `${targetRect.top}px`;
      panel.style.left = `${targetRect.left}px`;
      panel.style.width = `${targetRect.width}px`;
      panel.style.height = `${targetRect.height}px`;
      panel.style.borderRadius = panelRadius;
    });

    state.openTimer = window.setTimeout(() => {
      restoreInlineStyle(panel, state.panelStyle);
      state.panelStyle = panel.getAttribute("style");
      state.openTimer = null;
      state.phase = "open";

      if (typeof onAfterOpen === "function") {
        onAfterOpen();
      }
    }, duration + 40);

    return true;
  };

  const close = ({
    modal,
    panel,
    duration = 460,
    hideModal,
    deactivateModal,
    onAfterClose,
  }) => {
    if (!(modal instanceof HTMLElement) || !(panel instanceof HTMLElement)) {
      return false;
    }

    const state = states.get(modal) || {};

    if (state.phase === "closing") {
      return false;
    }

    window.clearTimeout(state.openTimer);
    window.clearTimeout(state.closeTimer);

    const currentRect = getRect(panel);
    const targetRect = getRect(state.trigger);
    const panelRadius = window.getComputedStyle(panel).borderRadius || "0px";
    const targetRadius =
      state.trigger instanceof HTMLElement
        ? window.getComputedStyle(state.trigger).borderRadius || panelRadius
        : panelRadius;

    state.phase = "closing";
    state.panelStyle = panel.getAttribute("style");
    states.set(modal, state);

    if (currentRect && targetRect) {
      preparePanel(panel, currentRect, panelRadius, duration);

      window.requestAnimationFrame(() => {
        if (typeof deactivateModal === "function") {
          deactivateModal();
        } else {
          modal.classList.remove("is-active");
        }

        panel.style.top = `${targetRect.top}px`;
        panel.style.left = `${targetRect.left}px`;
        panel.style.width = `${targetRect.width}px`;
        panel.style.height = `${targetRect.height}px`;
        panel.style.borderRadius = targetRadius;
      });
    } else if (typeof deactivateModal === "function") {
      deactivateModal();
    } else {
      modal.classList.remove("is-active");
    }

    state.closeTimer = window.setTimeout(() => {
      restoreInlineStyle(panel, state.panelStyle);
      state.panelStyle = panel.getAttribute("style");

      if (typeof hideModal === "function") {
        hideModal();
      } else {
        modal.hidden = true;
      }

      state.closeTimer = null;
      state.phase = "closed";

      if (typeof onAfterClose === "function") {
        onAfterClose(state.trigger);
      }
    }, duration + 40);

    return true;
  };

  return {
    open,
    close,
  };
})();

const pointerFocusReleaseSelector = [
  "button",
  "[role='button']",
  "summary",
  "a[href]",
  "[tabindex]:not([tabindex='-1'])",
].join(", ");

const shouldReleasePointerFocus = (element) => {
  if (!(element instanceof HTMLElement)) return false;
  if (!element.matches(pointerFocusReleaseSelector)) return false;
  if (element.matches("input, textarea, select, option")) return false;
  if (element.isContentEditable) return false;
  return true;
};

let pointerFocusCandidate = null;

document.addEventListener(
  "pointerdown",
  (event) => {
    const target =
      event.target instanceof Element
        ? event.target.closest(pointerFocusReleaseSelector)
        : null;

    pointerFocusCandidate = shouldReleasePointerFocus(target) ? target : null;
  },
  true,
);

document.addEventListener(
  "click",
  () => {
    const target = pointerFocusCandidate;
    pointerFocusCandidate = null;

    if (!shouldReleasePointerFocus(target)) {
      return;
    }

    window.requestAnimationFrame(() => {
      if (document.activeElement === target) {
        target.blur();
      }
    });
  },
  true,
);

// Handle Anchor Links securely with Lenis
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    const hash = this.getAttribute("href");

    // Skip for empty hashes or just '#'
    if (!hash || hash === "#") {
      e.preventDefault();
      return;
    }

    const target = document.querySelector(hash);
    if (target) {
      e.preventDefault();
      window.boilerplateScrollTo(target, {
        offset: -100,
        duration: 1.2,
      });
    }
  });
});
