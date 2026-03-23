// Main layout interactions (Nav Search, Scroll Reveal)
// Category and Product rendering is now handled server-side by Laravel Blade.

/* ── NAVBAR SEARCH ────────────────────────────── */
function initNavSearch() {
  const input = document.getElementById('navSearchInput');
  if (!input) return;
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && input.value.trim()) {
      window.location.href = '/shop?search=' + encodeURIComponent(input.value.trim());
    }
  });
}

/* ── SCROLL REVEAL ────────────────────────────── */
function initScrollReveal() {
  const observer = new IntersectionObserver(entries => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
  }, { threshold: 0.12 });
  document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));
}

/* ── INIT ─────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    initNavSearch();
    initScrollReveal();
});
