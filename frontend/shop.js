// ── STATE ─────────────────────────────────────────
let activeCategory = null;
let activeBadges   = [];
let sortBy         = 'default';
let searchQuery    = '';

// ── LOAD DATA ─────────────────────────────────────
function getProducts()   { return JSON.parse(localStorage.getItem('nexora_products')   || '[]'); }
function getCategories() { return JSON.parse(localStorage.getItem('nexora_categories') || '[]'); }

// ── READ URL PARAM (from homepage category click) ──
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get("category")) activeCategory = urlParams.get("category");
if (urlParams.get("search")) searchQuery = urlParams.get("search").toLowerCase();

// ── RENDER CATEGORY FILTERS ───────────────────────
function renderCatFilters() {
  const products = getProducts();
  const cats     = getCategories();
  const list     = document.getElementById('catFilterList');

  const allCount = products.length;
  list.innerHTML = `
    <div class="cat-filter-item ${!activeCategory ? 'active' : ''}" data-cat="all">
      <span>🗂️ All Products</span>
      <span class="cat-filter-count">${allCount}</span>
    </div>`
    + cats.map(c => {
      const count = products.filter(p => p.category === c.name).length;
      return `
        <div class="cat-filter-item ${activeCategory === c.name ? 'active' : ''}" data-cat="${c.name}">
          <span><span class="cat-emoji">${c.emoji}</span>${c.name}</span>
          <span class="cat-filter-count">${count}</span>
        </div>`;
    }).join('');

  list.querySelectorAll('.cat-filter-item').forEach(item => {
    item.addEventListener('click', () => {
      activeCategory = item.dataset.cat === 'all' ? null : item.dataset.cat;
      renderCatFilters();
      renderShopGrid();
      renderActiveFilters();
    });
  });
}

// ── RENDER ACTIVE FILTER TAGS ─────────────────────
function renderActiveFilters() {
  const area = document.getElementById('activeFilters');
  const tags = [];
  if (activeCategory) tags.push(`<div class="filter-tag" data-remove="cat">${activeCategory} <span class="remove">✕</span></div>`);
  activeBadges.forEach(b => tags.push(`<div class="filter-tag" data-remove="badge-${b}">${b} <span class="remove">✕</span></div>`));
  area.innerHTML = tags.join('');
  area.querySelectorAll('.filter-tag').forEach(tag => {
    tag.addEventListener('click', () => {
      const r = tag.dataset.remove;
      if (r === 'cat') { activeCategory = null; renderCatFilters(); }
      else if (r.startsWith('badge-')) {
        const b = r.replace('badge-', '');
        activeBadges = activeBadges.filter(x => x !== b);
        document.querySelectorAll('.badge-check').forEach(cb => { if (cb.value === b) cb.checked = false; });
      }
      renderActiveFilters();
      renderShopGrid();
    });
  });
}

// ── RENDER GRID ───────────────────────────────────
function renderShopGrid() {
  const grid = document.getElementById('shopGrid');
  const countEl = document.getElementById('resultsCount');
  let products = getProducts();

  // filter
  if (activeCategory) products = products.filter(p => p.category === activeCategory);
  if (activeBadges.length) products = products.filter(p => activeBadges.includes(p.badge));
  if (searchQuery) products = products.filter(p =>
    p.name.toLowerCase().includes(searchQuery) ||
    (p.brand||'').toLowerCase().includes(searchQuery) ||
    p.category.toLowerCase().includes(searchQuery)
  );

  // sort
  if (sortBy === 'price-asc')  products.sort((a,b) => a.price - b.price);
  if (sortBy === 'price-desc') products.sort((a,b) => b.price - a.price);
  if (sortBy === 'name-asc')   products.sort((a,b) => a.name.localeCompare(b.name));

  countEl.textContent = `${products.length} product${products.length !== 1 ? 's' : ''}`;

  if (!products.length) {
    grid.innerHTML = `<div class="shop-empty"><div class="e-icon">🔍</div><div class="e-title">No products found</div><p>Try a different filter or search term.</p></div>`;
    return;
  }

  const cats = getCategories();
  function catEmoji(cat) { return cats.find(c => c.name === cat)?.emoji || '📦'; }

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

  // re-attach cart & wishlist
  grid.querySelectorAll('.add-cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      this.textContent = '✓'; this.style.background = 'var(--accent)'; this.style.borderColor = 'var(--accent)'; this.style.color = '#000';
      setTimeout(() => { this.textContent = '+'; this.style.background = ''; this.style.borderColor = ''; this.style.color = ''; }, 1000);
      const c = document.querySelector('.cart-count'); c.textContent = parseInt(c.textContent) + 1;
    });
  });
  grid.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.stopPropagation();
      const liked = this.textContent === '♥';
      this.textContent = liked ? '♡' : '♥';
      this.style.background = liked ? '' : 'var(--red)'; this.style.borderColor = liked ? '' : 'var(--red)';
    });
  });
}

// ── BADGE FILTERS ─────────────────────────────────
document.querySelectorAll('.badge-check').forEach(cb => {
  cb.addEventListener('change', () => {
    activeBadges = [...document.querySelectorAll('.badge-check:checked')].map(c => c.value);
    renderActiveFilters();
    renderShopGrid();
  });
});

// ── SORT ──────────────────────────────────────────
document.getElementById('sortSelect').addEventListener('change', e => {
  sortBy = e.target.value;
  renderShopGrid();
});

// ── SEARCH ────────────────────────────────────────
document.getElementById('navSearch').addEventListener('input', e => {
  searchQuery = e.target.value.toLowerCase().trim();
  renderShopGrid();
});

// ── UPDATE PAGE TITLE if category set ─────────────
if (activeCategory) {
  document.querySelector('.shop-title').textContent = activeCategory;
}

// ── INIT ──────────────────────────────────────────
renderCatFilters();
renderActiveFilters();
renderShopGrid();
