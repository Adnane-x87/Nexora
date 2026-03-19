// ─── STORAGE ─────────────────────────────────────
const STORAGE_KEY = 'nexora_products';

function loadProducts() {
  const raw = localStorage.getItem(STORAGE_KEY);
  return raw ? JSON.parse(raw) : [];
}

// ─── CATEGORY EMOJI FALLBACK ──────────────────────
function categoryEmoji(cat) {
  const map = {
    Gaming: '🎮', Laptops: '💻', Smartphones: '📱',
    Audio: '🎧', Monitors: '🖥️', Accessories: '🕹️'
  };
  return map[cat] || '📦';
}

// ─── RENDER PRODUCTS GRID ────────────────────────
function renderProducts() {
  const grid = document.getElementById('productsGrid');
  if (!grid) return;

  const products = loadProducts();

  if (products.length === 0) {
    grid.innerHTML = `
      <div style="grid-column:1/-1; text-align:center; padding:60px 20px; color:rgba(245,245,245,0.3);">
        <div style="font-size:48px;margin-bottom:12px;">📦</div>
        <div style="font-family:'Syne',sans-serif;font-size:18px;font-weight:700;">No products yet</div>
        <div style="font-size:13px;margin-top:6px;">Add products from the <a href="dashboard.html" style="color:#e8ff47;">Dashboard</a></div>
      </div>`;
    return;
  }

  grid.innerHTML = products.map(p => `
    <div class="product-card">
      ${p.badge ? `<span class="tag ${p.badge}">${p.badge}</span>` : ''}
      <div class="wishlist-btn">&#9825;</div>
      <div class="product-img-wrap">
        ${p.image
          ? `<img src="${p.image}" alt="${p.name}" style="width:100%;height:100%;object-fit:cover;"/>`
          : categoryEmoji(p.category)
        }
      </div>
      <div class="product-info">
        <div class="product-brand">${p.brand || ''}</div>
        <div class="product-name">${p.name}</div>
        <div class="product-rating">
          <span class="s">&#9733;&#9733;&#9733;&#9733;&#9733;</span> 4.9
        </div>
        <div class="product-footer">
          <div class="price-wrap">
            <span class="price-new">$${Number(p.price).toLocaleString()}</span>
            ${p.oldPrice ? `<span class="price-old">$${Number(p.oldPrice).toLocaleString()}</span>` : ''}
          </div>
          <button class="add-cart-btn">+</button>
        </div>
      </div>
    </div>
  `).join('');

  attachCartListeners();
  attachWishlistListeners();
}

// ─── SCROLL REVEAL ──────────────────────────────
const observer = new IntersectionObserver((entries) => {
  entries.forEach(e => {
    if (e.isIntersecting) {
      e.target.classList.add('visible');
      observer.unobserve(e.target);
    }
  });
}, { threshold: 0.12 });

document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));

// ─── ADD TO CART ─────────────────────────────────
function attachCartListeners() {
  document.querySelectorAll('.add-cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      this.textContent = '✓';
      this.style.background = 'var(--accent)';
      this.style.borderColor = 'var(--accent)';
      setTimeout(() => {
        this.textContent = '+';
        this.style.background = '';
        this.style.borderColor = '';
      }, 1000);
      const count = document.querySelector('.cart-count');
      count.textContent = parseInt(count.textContent) + 1;
    });
  });
}

// ─── WISHLIST TOGGLE ─────────────────────────────
function attachWishlistListeners() {
  document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      this.textContent = this.textContent === '♡' ? '♥' : '♡';
      this.style.background = this.textContent === '♥' ? 'var(--red)' : '';
      this.style.borderColor = this.textContent === '♥' ? 'var(--red)' : '';
    });
  });
}

// ─── INIT ─────────────────────────────────────────
renderProducts();