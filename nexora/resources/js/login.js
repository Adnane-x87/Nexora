// ── PASSWORD TOGGLE ───────────────────────────────
document.querySelectorAll('.pass-toggle').forEach(btn => {
  btn.addEventListener('click', () => {
    const input = document.getElementById(btn.dataset.target);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
  });
});

// Note: Authentication is now handled by Laravel. 
// The handleLogin function has been removed.
