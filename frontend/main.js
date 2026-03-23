const STORAGE_KEY = 'nexora_products';
const CATS_KEY    = 'nexora_categories';

function loadProducts()   { return JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]'); }
function loadCategories() { return JSON.parse(localStorage.getItem(CATS_KEY)    || '[]'); }
function catEmoji(cat)    { return loadCategories().find(c => c.name === cat)?.emoji || '📦'; }

/* ── AUTH AREA ────────────────────────────────── */
function renderAuth() {
  const area = document.getElementById('authArea');
  if (!area) return;
  const session = JSON.parse(localStorage.getItem('nexora_session') || 'null');

  if (!session) {
    area.innerHTML = `
      <div class="auth-guest">
        <a href="login.html" class="btn-accent-sm">Login</a>
      </div>`;

  } else if (session.role === 'admin') {
    area.innerHTML = `
      <div class="auth-user">
        <div class="auth-avatar">${session.name.charAt(0).toUpperCase()}</div>
        <span class="auth-name">Hi, ${session.name}</span>
        <a href="dashboard.html" class="btn-accent-sm" style="font-size:12px;padding:8px 14px;">⊞ Dashboard</a>
        <button class="btn-logout" id="logoutBtn">Logout</button>
      </div>`;
    document.getElementById('logoutBtn').addEventListener('click', () => {
      localStorage.removeItem('nexora_session');
      window.location.reload();
    });

  } else {
    area.innerHTML = `
      <div class="auth-user">
        <div class="auth-avatar">${session.name.charAt(0).toUpperCase()}</div>
        <span class="auth-name">Hi, ${session.name}</span>
        <button class="btn-logout" id="logoutBtn">Logout</button>
      </div>`;
    document.getElementById('logoutBtn').addEventListener('click', () => {
      localStorage.removeItem('nexora_session');
      window.location.reload();
    });
  }
}

/* ── NAVBAR SEARCH ────────────────────────────── */
function initNavSearch() {
  const input = document.getElementById('navSearchInput');
  if (!input) return;
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && input.value.trim()) {
      window.location.href = 'shop.html?search=' + encodeURIComponent(input.value.trim());
    }
  });
}

/* ── RENDER CATEGORIES ────────────────────────── */
function renderCategories() {
  const grid = document.getElementById('categoriesGrid');
  if (!grid) return;
  const cats     = loadCategories();
  const products = loadProducts();
  if (!cats.length) { grid.innerHTML = '<p style="color:var(--white-dim);font-size:13px;">No categories yet.</p>'; return; }
  grid.innerHTML = cats.map(c => {
    const count = products.filter(p => p.category === c.name).length;
    return `<div class="category-card" data-cat="${c.name}">
      <div class="cat-icon">${c.emoji}</div>
      <div class="cat-name">${c.name}</div>
      <div class="cat-count">${count} products</div>
    </div>`;
  }).join('');

  grid.querySelectorAll('.category-card').forEach(card => {
    card.addEventListener('click', () => {
      window.location.href = 'shop.html?category=' + encodeURIComponent(card.dataset.cat);
    });
  });
}

/* ── RENDER PRODUCTS ──────────────────────────── */
function renderProducts() {
  const grid = document.getElementById('productsGrid');
  if (!grid) return;
  const products = loadProducts();
  if (!products.length) {
    grid.innerHTML = `<div class="empty-products"><div class="e-icon">📦</div><div class="e-title">No products yet</div><p>Add products from the <a href="login.html">Dashboard</a></p></div>`;
    return;
  }
  grid.innerHTML = products.map(p => `
    <div class="product-card">
      ${p.badge ? `<span class="tag ${p.badge}">${p.badge}</span>` : ''}
      <div class="wishlist-btn">♡</div>
      <div class="product-img-wrap">
        ${p.image ? `<img src="${p.image}" alt="${p.name}"/>` : catEmoji(p.category)}
      </div>
      <div class="product-info">
        <div class="product-brand">${p.brand || ''}</div>
        <div class="product-name">${p.name}</div>
        <div class="product-rating"><span class="s">★★★★★</span> 4.9</div>
        <div class="product-footer">
          <div class="price-wrap">
            <span class="price-new">$${Number(p.price).toLocaleString()}</span>
            ${p.oldPrice ? `<span class="price-old">$${Number(p.oldPrice).toLocaleString()}</span>` : ''}
          </div>
          <button class="add-cart-btn">+</button>
        </div>
      </div>
    </div>`).join('');
  attachListeners();
}

/* ── CART & WISHLIST ──────────────────────────── */
function attachListeners() {
  document.querySelectorAll('.add-cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      this.textContent = '✓'; this.style.background = 'var(--accent)'; this.style.borderColor = 'var(--accent)'; this.style.color = '#000';
      setTimeout(() => { this.textContent = '+'; this.style.background = ''; this.style.borderColor = ''; this.style.color = ''; }, 1000);
      const c = document.querySelector('.cart-count');
      if (c) c.textContent = parseInt(c.textContent) + 1;
    });
  });
  document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      const liked = this.textContent === '♥';
      this.textContent = liked ? '♡' : '♥';
      this.style.background = liked ? '' : 'var(--red)';
      this.style.borderColor = liked ? '' : 'var(--red)';
    });
  });
}

/* ── SCROLL REVEAL ────────────────────────────── */
const observer = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

/* ── INIT ─────────────────────────────────────── */
renderAuth();
initNavSearch();
renderCategories();
renderProducts();
