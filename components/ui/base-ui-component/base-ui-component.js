document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".base-ui-component").forEach((component) => {
    component.setAttribute("data-base-ui-component-ready", "true");
  });
});
