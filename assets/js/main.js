// basic utilities
const yearEl = document.getElementById('year');
if (yearEl) yearEl.textContent = new Date().getFullYear();

// theme toggle
const btn = document.getElementById('theme-toggle');
if (btn) {
  btn.addEventListener('click', () => {
    document.body.classList.toggle('dark');
  });
}
