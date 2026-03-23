// ── PASSWORD TOGGLE ───────────────────────────────
document.querySelectorAll('.pass-toggle').forEach(btn => {
  btn.addEventListener('click', () => {
    const input = document.getElementById(btn.dataset.target);
    input.type = input.type === 'password' ? 'text' : 'password';
    btn.textContent = input.type === 'password' ? '👁' : '🙈';
  });
});

// ── REGISTER ──────────────────────────────────────
document.getElementById('registerBtn').addEventListener('click', handleRegister);
document.getElementById('regPassConfirm').addEventListener('keydown', e => { if (e.key === 'Enter') handleRegister(); });

function handleRegister() {
  const name         = document.getElementById('regName').value.trim();
  const email        = document.getElementById('regEmail').value.trim();
  const pass         = document.getElementById('regPass').value.trim();
  const passConfirm  = document.getElementById('regPassConfirm').value.trim();
  const err          = document.getElementById('regError');
  const success      = document.getElementById('regSuccess');
  err.textContent     = '';
  success.textContent = '';

  // Validations
  if (!name)                    { err.textContent = 'Please enter your full name.'; return; }
  if (!email)                   { err.textContent = 'Please enter your email.'; return; }
  if (!email.includes('@'))     { err.textContent = 'Please enter a valid email.'; return; }
  if (!pass)                    { err.textContent = 'Please enter a password.'; return; }
  if (pass.length < 6)          { err.textContent = 'Password must be at least 6 characters.'; return; }
  if (pass !== passConfirm)     { err.textContent = 'Passwords do not match.'; return; }

  // Check if email already exists
  const users = JSON.parse(localStorage.getItem('nexora_users') || '[]');
  if (users.find(u => u.email === email)) { err.textContent = 'An account with this email already exists.'; return; }

  // Save new user
  const newId = users.length ? Math.max(...users.map(u => u.id)) + 1 : 1;
  users.push({
    id:     newId,
    name,
    email,
    role:   'Viewer',
    joined: new Date().toISOString().split('T')[0]
  });
  localStorage.setItem('nexora_users', JSON.stringify(users));

  // Auto login and redirect
  localStorage.setItem('nexora_session', JSON.stringify({ role: 'user', name, email }));
  success.textContent = 'Account created! Redirecting…';
  setTimeout(() => window.location.href = 'index.html', 1200);
}
