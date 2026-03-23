// ── ADMIN CREDENTIALS (change these!) ─────────────
const ADMIN_EMAIL = 'admin@nexora.com';
const ADMIN_PASS  = 'nexora123';

// ── PASSWORD TOGGLE ───────────────────────────────
document.querySelectorAll('.pass-toggle').forEach(btn => {
  btn.addEventListener('click', () => {
    const input = document.getElementById(btn.dataset.target);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
  });
});

// ── LOGIN ─────────────────────────────────────────
document.getElementById('loginBtn').addEventListener('click', handleLogin);
document.getElementById('loginPass').addEventListener('keydown', e => { if (e.key === 'Enter') handleLogin(); });

function handleLogin() {
  const email = document.getElementById('loginEmail').value.trim();
  const pass  = document.getElementById('loginPass').value.trim();
  const err   = document.getElementById('loginError');
  err.textContent = '';

  if (!email) { err.textContent = 'Please enter your email.'; return; }
  if (!pass)  { err.textContent = 'Please enter your password.'; return; }

  // Check if admin
  if (email === ADMIN_EMAIL && pass === ADMIN_PASS) {
    localStorage.setItem('nexora_session', JSON.stringify({ role: 'admin', name: 'Admin', email }));
    window.location.href = 'dashboard.html';
    return;
  }

  // Check regular users
  const users = JSON.parse(localStorage.getItem('nexora_users') || '[]');
  const user  = users.find(u => u.email === email);
  if (!user) { err.textContent = 'No account found with this email.'; return; }

  localStorage.setItem('nexora_session', JSON.stringify({ role: 'user', name: user.name, email: user.email }));
  window.location.href = 'index.html';
}
