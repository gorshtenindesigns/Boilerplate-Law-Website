document.documentElement.classList.add("js");

document.addEventListener("DOMContentLoaded", function () {
  var header = document.querySelector(".site-header");

  if (!header) {
    return;
  }

  var toggle = header.querySelector(".site-header__menu-toggle");
  var drawer = header.querySelector(".site-header__drawer");

  if (toggle && drawer) {
    toggle.addEventListener("click", function () {
      var isOpen = header.classList.toggle("is-drawer-open");

      toggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
      drawer.hidden = !isOpen;
    });
  }

  header
    .querySelectorAll(".site-header__drawer-toggle")
    .forEach(function (accordionToggle) {
      accordionToggle.addEventListener("click", function () {
        var item = accordionToggle.closest(".site-header__drawer-item");
        var isOpen = item && item.classList.toggle("is-open");

        accordionToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
      });
    });
});
