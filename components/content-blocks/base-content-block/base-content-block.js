document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".base-content-block").forEach((block) => {
    block.setAttribute("data-base-content-block-ready", "true");
  });
});
