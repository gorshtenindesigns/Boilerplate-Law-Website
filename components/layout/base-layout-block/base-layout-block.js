document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".base-layout-block").forEach((block) => {
    block.setAttribute("data-base-layout-block-ready", "true");
  });
});
