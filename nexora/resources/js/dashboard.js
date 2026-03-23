// ── STORAGE ───────────────────────────────────────
const KEYS = { products: 'nexora_products', categories: 'nexora_categories', users: 'nexora_users' };

const DEFAULT_CATS = [
  { id: 1, name: 'Gaming',      emoji: '🎮' },
  { id: 2, name: 'Laptops',     emoji: '💻' },
  { id: 3, name: 'Audio',       emoji: '🎧' },
  { id: 4, name: 'Monitors',    emoji: '🖥️' },
  { id: 5, name: 'Accessories', emoji: '🕹️' },
];
const DEFAULT_PRODUCTS = [
  { id: 1, name: 'PlayStation 5 Pro',   brand: 'Sony',  category: 'Gaming',   price: 699,  oldPrice: 799, badge: 'new',  stock: 45,  image: '' },
  { id: 2, name: 'Razer DeathAdder V3', brand: 'Razer', category: 'Gaming',   price: 89,   oldPrice: 109, badge: 'sale', stock: 120, image: '' },
  { id: 3, name: 'ROG Strix G16',       brand: 'Asus',  category: 'Laptops',  price: 1499, oldPrice: '',  badge: 'hot',  stock: 18,  image: '' },
  { id: 4, name: 'WH-1000XM6',          brand: 'Sony',  category: 'Audio',    price: 279,  oldPrice: 349, badge: 'sale', stock: 60,  image: '' },
  { id: 5, name: 'LG UltraWide 34"',    brand: 'LG',    category: 'Monitors', price: 679,  oldPrice: 799, badge: 'sale', stock: 22,  image: '' },
];
const DEFAULT_USERS = [
  { id: 1, name: 'Admin',       email: 'admin@nexora.com', role: 'Admin',  joined: '2026-01-01' },
  { id: 2, name: 'John Editor', email: 'john@nexora.com',  role: 'Editor', joined: '2026-02-10' },
];

function dbLoad(key, def) { const r = localStorage.getItem(key); if (!r) { dbSave(key, def); return def; } return JSON.parse(r); }
function dbSave(key, data) { localStorage.setItem(key, JSON.stringify(data)); }

let products   = dbLoad(KEYS.products,   DEFAULT_PRODUCTS);
let categories = dbLoad(KEYS.categories, DEFAULT_CATS);
let users      = dbLoad(KEYS.users,      DEFAULT_USERS);

// ── PAGE NAV ──────────────────────────────────────
function showPage(id) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-item[data-page]').forEach(n => n.classList.remove('active'));
  document.getElementById('page-' + id)?.classList.add('active');
  document.querySelector(`.nav-item[data-page="${id}"]`)?.classList.add('active');
  if (id === 'statistics')  renderStats();
  if (id === 'products')    renderProductsPage();
  if (id === 'categories')  renderCatsPage();
  if (id === 'users')       renderUsersPage();
}
document.querySelectorAll('.nav-item[data-page]').forEach(item => {
  item.addEventListener('click', e => { e.preventDefault(); showPage(item.dataset.page); });
});
// Logout is now handled by the form in sidebar.blade.php

// ── HELPERS ───────────────────────────────────────
function catEmoji(name) { return categories.find(c => c.name === name)?.emoji || '📦'; }
function closeOverlay(id) { document.getElementById(id).classList.remove('open'); }
function openOverlay(id)  { document.getElementById(id).classList.add('open'); }

// ── STATISTICS ────────────────────────────────────
function renderStats() {
  const totalStock = products.reduce((s, p) => s + (Number(p.stock) || 0), 0);
  document.getElementById('statsRow').innerHTML = `
    <div class="stat-card"><div class="stat-icon"><i data-lucide="package" style="width:24px;height:24px;"></i></div><div><div class="stat-val">${products.length}</div><div class="stat-lbl">Products</div></div></div>
    <div class="stat-card"><div class="stat-icon"><i data-lucide="tag" style="width:24px;height:24px;"></i></div><div><div class="stat-val">${categories.length}</div><div class="stat-lbl">Categories</div></div></div>
    <div class="stat-card"><div class="stat-icon"><i data-lucide="users" style="width:24px;height:24px;"></i></div><div><div class="stat-val">${users.length}</div><div class="stat-lbl">Users</div></div></div>
    <div class="stat-card"><div class="stat-icon"><i data-lucide="bar-chart-3" style="width:24px;height:24px;"></i></div><div><div class="stat-val">${totalStock}</div><div class="stat-lbl">Total Stock</div></div></div>`;
  if (window.lucide) lucide.createIcons();

  const max = Math.max(...categories.map(c => products.filter(p => p.category === c.name).length), 1);
  document.getElementById('categoryBars').innerHTML = categories.map(c => {
    const count = products.filter(p => p.category === c.name).length;
    return `<div class="cat-bar-item"><div class="cat-bar-label">${c.emoji} ${c.name}</div><div class="cat-bar-track"><div class="cat-bar-fill" style="width:${Math.round(count/max*100)}%"></div></div><div class="cat-bar-count">${count}</div></div>`;
  }).join('');

  const recent = [...products].reverse().slice(0, 5);
  document.getElementById('recentProducts').innerHTML = recent.length
    ? recent.map(p => `<div class="recent-item"><div class="recent-thumb">${p.image ? `<img src="${p.image}" alt="${p.name}"/>` : catEmoji(p.category)}</div><div class="recent-name">${p.name}</div><div class="recent-price">$${Number(p.price).toLocaleString()}</div></div>`).join('')
    : '<p style="color:var(--white-dim);font-size:13px;">No products yet.</p>';

  document.getElementById('bsNew').textContent  = products.filter(p => p.badge === 'new').length;
  document.getElementById('bsSale').textContent = products.filter(p => p.badge === 'sale').length;
  document.getElementById('bsHot').textContent  = products.filter(p => p.badge === 'hot').length;
}

// ── PRODUCTS PAGE ─────────────────────────────────
let editProdId = null, activeFilter = 'all', searchQ = '', imgB64 = '';

function renderProductsPage() { renderProdFilters(); renderProdTable(); renderProdStats(); }

function renderProdStats() {
  document.getElementById('statTotal').textContent  = products.length;
  document.getElementById('statGaming').textContent = products.filter(p => p.category === 'Gaming').length;
  document.getElementById('statTech').textContent   = products.filter(p => ['Laptops','Monitors'].includes(p.category)).length;
  document.getElementById('statSale').textContent   = products.filter(p => p.badge === 'sale').length;
}

function renderProdFilters() {
  const row = document.getElementById('productFilters');
  row.innerHTML = `<button class="filter-btn ${activeFilter==='all'?'active':''}" data-filter="all">All</button>`
    + categories.map(c => `<button class="filter-btn ${activeFilter===c.name?'active':''}" data-filter="${c.name}">${c.emoji} ${c.name}</button>`).join('');
  row.querySelectorAll('.filter-btn').forEach(btn => btn.addEventListener('click', () => { activeFilter = btn.dataset.filter; renderProdFilters(); renderProdTable(); }));
}

function renderProdTable() {
  const list = products.filter(p => (activeFilter === 'all' || p.category === activeFilter) && (!searchQ || p.name.toLowerCase().includes(searchQ) || (p.brand||'').toLowerCase().includes(searchQ)));
  const tbody = document.getElementById('productsTableBody');
  const empty = document.getElementById('prodEmptyState');
  const thead = document.querySelector('#page-products .products-table thead');
  tbody.innerHTML = '';
  if (!products.length) {
    empty.style.display = 'block';
    thead.style.display = 'none';
    empty.querySelector('.empty-icon').innerHTML = '<i data-lucide="package-search" style="width:48px;height:48px;"></i>';
    if (window.lucide) lucide.createIcons();
    return;
  }
  empty.style.display = 'none'; thead.style.display = '';
  list.forEach(p => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><div class="prod-cell"><div class="prod-thumb">${p.image?`<img src="${p.image}" alt="${p.name}"/>`:catEmoji(p.category)}</div><div><div class="prod-name">${p.name}</div><div class="prod-brand">${p.brand||'—'}</div></div></div></td>
      <td><span class="cat-pill">${p.category}</span></td>
      <td><span class="price-cell">$${Number(p.price).toLocaleString()}</span></td>
      <td>${p.oldPrice?`<span class="old-price-cell">$${Number(p.oldPrice).toLocaleString()}</span>`:'—'}</td>
      <td>${p.badge?`<span class="badge badge-${p.badge}">${p.badge}</span>`:'<span class="badge-none">—</span>'}</td>
      <td><span class="stock-cell ${Number(p.stock)<25?'stock-low':'stock-ok'}">${p.stock??'—'}</span></td>
      <td><div class="actions-cell"><button class="action-btn edit" data-id="${p.id}"><i data-lucide="edit-3" style="width:14px;height:14px;"></i></button><button class="action-btn del" data-id="${p.id}"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button></div></td>`;
    tbody.appendChild(tr);
  });
  if (window.lucide) lucide.createIcons();
  tbody.querySelectorAll('.action-btn.edit').forEach(btn => btn.addEventListener('click', () => openProdModal(products.find(p => p.id === +btn.dataset.id))));
  tbody.querySelectorAll('.action-btn.del').forEach(btn => btn.addEventListener('click', () => openDel(+btn.dataset.id, 'product')));
}

document.getElementById('searchInput').addEventListener('input', e => { searchQ = e.target.value.toLowerCase().trim(); renderProdTable(); });

// product modal
function openProdModal(prod = null) {
  document.getElementById('formError').textContent = '';
  imgB64 = ''; resetImgPreview();
  const catSel = document.getElementById('fieldCategory');
  catSel.innerHTML = '<option value="">Select category</option>' + categories.map(c => `<option value="${c.name}">${c.emoji} ${c.name}</option>`).join('');
  if (prod) {
    editProdId = prod.id;
    document.getElementById('modalTitle').textContent = 'Edit Product';
    document.getElementById('fieldName').value     = prod.name;
    catSel.value                                   = prod.category;
    document.getElementById('fieldBrand').value    = prod.brand || '';
    document.getElementById('fieldPrice').value    = prod.price;
    document.getElementById('fieldOldPrice').value = prod.oldPrice || '';
    document.getElementById('fieldBadge').value    = prod.badge || '';
    document.getElementById('fieldStock').value    = prod.stock ?? '';
    document.getElementById('fieldDesc').value     = prod.desc || '';
    if (prod.image) { imgB64 = prod.image; showImgPreview(prod.image); }
  } else {
    editProdId = null;
    document.getElementById('modalTitle').textContent = 'Add Product';
    ['fieldName','fieldBrand','fieldPrice','fieldOldPrice','fieldStock','fieldDesc'].forEach(id => document.getElementById(id).value = '');
    catSel.value = ''; document.getElementById('fieldBadge').value = '';
  }
  openOverlay('modalOverlay');
}

document.getElementById('openModalBtn').addEventListener('click', () => openProdModal());
document.getElementById('closeModalBtn').addEventListener('click', () => closeOverlay('modalOverlay'));
document.getElementById('cancelBtn').addEventListener('click', () => closeOverlay('modalOverlay'));
document.getElementById('modalOverlay').addEventListener('click', e => { if (e.target.id === 'modalOverlay') closeOverlay('modalOverlay'); });

document.getElementById('triggerUpload').addEventListener('click', () => document.getElementById('imgFileInput').click());
document.getElementById('imgPreview').addEventListener('click', () => document.getElementById('imgFileInput').click());
document.getElementById('imgFileInput').addEventListener('change', e => {
  const file = e.target.files[0]; if (!file) return;
  if (file.size > 5*1024*1024) { document.getElementById('formError').textContent = 'Image must be under 5MB.'; return; }
  const reader = new FileReader();
  reader.onload = ev => { imgB64 = ev.target.result; showImgPreview(imgB64); };
  reader.readAsDataURL(file); e.target.value = '';
});
function showImgPreview(src) { const img = document.getElementById('previewImg'); img.src = src; img.style.display = 'block'; document.getElementById('uploadPlaceholder').style.display = 'none'; }
function resetImgPreview() { const img = document.getElementById('previewImg'); img.src = ''; img.style.display = 'none'; document.getElementById('uploadPlaceholder').style.display = 'block'; }

document.getElementById('saveBtn').addEventListener('click', () => {
  const name = document.getElementById('fieldName').value.trim();
  const cat  = document.getElementById('fieldCategory').value;
  const price = document.getElementById('fieldPrice').value.trim();
  const err  = document.getElementById('formError');
  err.textContent = '';
  if (!name)  { err.textContent = 'Product name is required.'; return; }
  if (!cat)   { err.textContent = 'Please select a category.'; return; }
  if (!price || isNaN(price)) { err.textContent = 'Enter a valid price.'; return; }
  const data = { name, category: cat, brand: document.getElementById('fieldBrand').value.trim(), price: parseFloat(price), oldPrice: document.getElementById('fieldOldPrice').value ? parseFloat(document.getElementById('fieldOldPrice').value) : '', badge: document.getElementById('fieldBadge').value || '', stock: document.getElementById('fieldStock').value ? parseInt(document.getElementById('fieldStock').value) : 0, desc: document.getElementById('fieldDesc').value.trim(), image: imgB64 };
  if (editProdId !== null) { products = products.map(p => p.id === editProdId ? { ...p, ...data } : p); }
  else { const nid = products.length ? Math.max(...products.map(p => p.id)) + 1 : 1; products.push({ id: nid, ...data }); }
  dbSave(KEYS.products, products); renderProductsPage(); closeOverlay('modalOverlay');
});

// ── CATEGORIES PAGE ───────────────────────────────
let editCatId = null;
function renderCatsPage() {
  const grid = document.getElementById('categoriesGrid');
  grid.innerHTML = categories.map(c => {
    const count = products.filter(p => p.category === c.name).length;
    return `<div class="cat-manage-card"><div class="cat-manage-actions"><button class="action-btn edit" data-cid="${c.id}"><i data-lucide="edit-3" style="width:14px;height:14px;"></i></button><button class="action-btn del" data-cid="${c.id}"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button></div><div class="cat-manage-icon">${c.emoji}</div><div class="cat-manage-name">${c.name}</div><div class="cat-manage-count">${count} product${count!==1?'s':''}</div></div>`;
  }).join('');
  if (window.lucide) lucide.createIcons();
  grid.querySelectorAll('.action-btn.edit').forEach(btn => btn.addEventListener('click', () => openCatModal(categories.find(c => c.id === +btn.dataset.cid))));
  grid.querySelectorAll('.action-btn.del').forEach(btn => btn.addEventListener('click', () => openDel(+btn.dataset.cid, 'category')));
}

function openCatModal(cat = null) {
  document.getElementById('catFormError').textContent = '';
  if (cat) { editCatId = cat.id; document.getElementById('catModalTitle').textContent = 'Edit Category'; document.getElementById('catFieldName').value = cat.name; document.getElementById('catFieldEmoji').value = cat.emoji; }
  else { editCatId = null; document.getElementById('catModalTitle').textContent = 'Add Category'; document.getElementById('catFieldName').value = ''; document.getElementById('catFieldEmoji').value = ''; }
  openOverlay('catModalOverlay');
}

document.getElementById('openCatModalBtn').addEventListener('click', () => openCatModal());
document.getElementById('closeCatModalBtn').addEventListener('click', () => closeOverlay('catModalOverlay'));
document.getElementById('cancelCatBtn').addEventListener('click', () => closeOverlay('catModalOverlay'));
document.getElementById('catModalOverlay').addEventListener('click', e => { if (e.target.id === 'catModalOverlay') closeOverlay('catModalOverlay'); });
document.getElementById('saveCatBtn').addEventListener('click', () => {
  const name = document.getElementById('catFieldName').value.trim();
  const emoji = document.getElementById('catFieldEmoji').value.trim() || '📦';
  const err = document.getElementById('catFormError'); err.textContent = '';
  if (!name) { err.textContent = 'Category name is required.'; return; }
  if (editCatId !== null) { categories = categories.map(c => c.id === editCatId ? { ...c, name, emoji } : c); }
  else { const nid = categories.length ? Math.max(...categories.map(c => c.id)) + 1 : 1; categories.push({ id: nid, name, emoji }); }
  dbSave(KEYS.categories, categories); renderCatsPage(); closeOverlay('catModalOverlay');
});

// ── USERS PAGE ────────────────────────────────────
let editUserId = null;
function renderUsersPage() {
  const tbody = document.getElementById('usersTableBody');
  const empty = document.getElementById('usersEmptyState');
  const thead = document.querySelector('#page-users .products-table thead');
  tbody.innerHTML = '';
  if (!users.length) { empty.style.display = 'block'; thead.style.display = 'none'; return; }
  empty.style.display = 'none'; thead.style.display = '';
  users.forEach(u => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><div class="prod-cell"><div class="admin-avatar" style="width:36px;height:36px;font-size:13px;">${u.name.charAt(0).toUpperCase()}</div><div class="prod-name">${u.name}</div></div></td>
      <td style="color:var(--white-dim)">${u.email}</td>
      <td><span class="role-badge role-${u.role}">${u.role}</span></td>
      <td style="color:var(--white-dim)">${u.joined}</td>
      <td><div class="actions-cell"><button class="action-btn edit" data-uid="${u.id}"><i data-lucide="edit-3" style="width:14px;height:14px;"></i></button><button class="action-btn del" data-uid="${u.id}"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button></div></td>`;
    tbody.appendChild(tr);
  });
  if (window.lucide) lucide.createIcons();
  tbody.querySelectorAll('.action-btn.edit').forEach(btn => btn.addEventListener('click', () => openUserModal(users.find(u => u.id === +btn.dataset.uid))));
  tbody.querySelectorAll('.action-btn.del').forEach(btn => btn.addEventListener('click', () => openDel(+btn.dataset.uid, 'user')));
}

function openUserModal(user = null) {
  document.getElementById('userFormError').textContent = '';
  if (user) { editUserId = user.id; document.getElementById('userModalTitle').textContent = 'Edit User'; document.getElementById('userFieldName').value = user.name; document.getElementById('userFieldEmail').value = user.email; document.getElementById('userFieldRole').value = user.role; }
  else { editUserId = null; document.getElementById('userModalTitle').textContent = 'Add User'; document.getElementById('userFieldName').value = ''; document.getElementById('userFieldEmail').value = ''; document.getElementById('userFieldRole').value = 'Admin'; }
  openOverlay('userModalOverlay');
}

document.getElementById('openUserModalBtn').addEventListener('click', () => openUserModal());
document.getElementById('closeUserModalBtn').addEventListener('click', () => closeOverlay('userModalOverlay'));
document.getElementById('cancelUserBtn').addEventListener('click', () => closeOverlay('userModalOverlay'));
document.getElementById('userModalOverlay').addEventListener('click', e => { if (e.target.id === 'userModalOverlay') closeOverlay('userModalOverlay'); });
document.getElementById('saveUserBtn').addEventListener('click', () => {
  const name = document.getElementById('userFieldName').value.trim();
  const email = document.getElementById('userFieldEmail').value.trim();
  const role = document.getElementById('userFieldRole').value;
  const err = document.getElementById('userFormError'); err.textContent = '';
  if (!name)  { err.textContent = 'Name is required.'; return; }
  if (!email) { err.textContent = 'Email is required.'; return; }
  if (editUserId !== null) { users = users.map(u => u.id === editUserId ? { ...u, name, email, role } : u); }
  else { const nid = users.length ? Math.max(...users.map(u => u.id)) + 1 : 1; users.push({ id: nid, name, email, role, joined: new Date().toISOString().split('T')[0] }); }
  dbSave(KEYS.users, users); renderUsersPage(); closeOverlay('userModalOverlay');
});

// ── DELETE CONFIRM ────────────────────────────────
let delTarget = { id: null, type: null };
function openDel(id, type) {
  delTarget = { id, type };
  let name = '';
  if (type === 'product')  name = products.find(p => p.id === id)?.name || '';
  if (type === 'category') name = categories.find(c => c.id === id)?.name || '';
  if (type === 'user')     name = users.find(u => u.id === id)?.name || '';
  document.getElementById('deleteItemName').textContent = name;
  openOverlay('deleteOverlay');
}
document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
  const { id, type } = delTarget;
  if (type === 'product')  { products   = products.filter(p => p.id !== id);   dbSave(KEYS.products, products);   renderProductsPage(); }
  if (type === 'category') { categories = categories.filter(c => c.id !== id); dbSave(KEYS.categories, categories); renderCatsPage(); }
  if (type === 'user')     { users      = users.filter(u => u.id !== id);       dbSave(KEYS.users, users);         renderUsersPage(); }
  closeOverlay('deleteOverlay');
});
document.getElementById('cancelDeleteBtn').addEventListener('click', () => closeOverlay('deleteOverlay'));
document.getElementById('closeDeleteBtn').addEventListener('click',  () => closeOverlay('deleteOverlay'));
document.getElementById('deleteOverlay').addEventListener('click', e => { if (e.target.id === 'deleteOverlay') closeOverlay('deleteOverlay'); });

// ── INIT ──────────────────────────────────────────
showPage('statistics');
