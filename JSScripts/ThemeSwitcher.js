// JSScripts/ThemeSwitcher.js
(function () {
  const root = document.documentElement;

  // Decide theme: localStorage > system preference > light
  let theme = "light";
  try {
    const saved = localStorage.getItem("theme");
    if (saved) {
      theme = saved;
    } else if (window.matchMedia("(prefers-color-scheme: dark)").matches) {
      theme = "dark";
    }
  } catch (e) {}

  root.setAttribute("data-theme", theme);

  // Toggle button behavior (only if button exists)
  const toggleBtn = document.getElementById("theme-toggle");

  function updateIcon(theme) {
    if (!toggleBtn) return;
    const icon = toggleBtn.querySelector("i");
    if (!icon) return;
    icon.classList.toggle("bx-sun", theme === "dark");
    icon.classList.toggle("bx-moon", theme !== "dark");
  }

  // Initialize icon if toggle exists
  if (toggleBtn) {
    updateIcon(theme);

    toggleBtn.addEventListener("click", () => {
      const current = root.getAttribute("data-theme") || "light";
      const next = current === "light" ? "dark" : "light";
      root.setAttribute("data-theme", next);
      try {
        localStorage.setItem("theme", next);
      } catch (e) {}
      updateIcon(next);
    });
  }
})();
