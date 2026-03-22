// ─── STORAGE ─────────────────────────────────────
const STORAGE_KEY = 'nexora_products';

const DEFAULT_PRODUCTS = [
  { id: 1, name: 'PlayStation 5 Pro', brand: 'Sony', category: 'Gaming', price: 699, oldPrice: 799, badge: 'new', stock: 45, desc: 'Next-gen gaming console with 8K support.', image: '' },
  { id: 2, name: 'Razer DeathAdder V3', brand: 'Razer', category: 'Gaming', price: 89, oldPrice: 109, badge: 'sale', stock: 120, desc: 'Pro gaming mouse with 30K DPI sensor.', image: '' },
  { id: 3, name: 'ROG Strix G16', brand: 'Asus', category: 'Laptops', price: 1499, oldPrice: '', badge: 'hot', stock: 18, desc: 'Gaming laptop with RTX 4080 GPU.', image: '' },
  { id: 4, name: 'WH-1000XM6', brand: 'Sony', category: 'Audio', price: 279, oldPrice: 349, badge: 'sale', stock: 60, desc: 'Industry-leading noise cancelling headphones.', image: '' },
  { id: 6, name: 'LG UltraWide 34"', brand: 'LG', category: 'Monitors', price: 679, oldPrice: 799, badge: 'sale', stock: 22, desc: '34-inch 4K UltraWide curved monitor.', image: '' },
];

function loadProducts() {
  const raw = localStorage.getItem(STORAGE_KEY);
  if (!raw) {
    saveProducts(DEFAULT_PRODUCTS);
    return DEFAULT_PRODUCTS;
  }
  return JSON.parse(raw);
}

function saveProducts(products) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(products));
}

let products = loadProducts();
let editingId = null;
let deleteTargetId = null;
let activeFilter = 'all';
let searchQuery = '';
let currentImageBase64 = '';

// ─── DOM REFS ─────────────────────────────────────
const tbody          = document.getElementById('productsTableBody');
const emptyState     = document.getElementById('emptyState');
const modalOverlay   = document.getElementById('modalOverlay');
const deleteOverlay  = document.getElementById('deleteOverlay');
const modalTitle     = document.getElementById('modalTitle');
const formError      = document.getElementById('formError');
const previewImg     = document.getElementById('previewImg');
const uploadPlaceholder = document.getElementById('uploadPlaceholder');
const imgFileInput   = document.getElementById('imgFileInput');

const fieldName      = document.getElementById('fieldName');
const fieldCategory  = document.getElementById('fieldCategory');
const fieldBrand     = document.getElementById('fieldBrand');
const fieldPrice     = document.getElementById('fieldPrice');
const fieldOldPrice  = document.getElementById('fieldOldPrice');
const fieldBadge     = document.getElementById('fieldBadge');
const fieldStock     = document.getElementById('fieldStock');
const fieldDesc      = document.getElementById('fieldDesc');

// ─── RENDER TABLE ────────────────────────────────
function getFiltered() {
  return products.filter(p => {
    const matchFilter = activeFilter === 'all' || p.category === activeFilter;
    const matchSearch = !searchQuery ||
      p.name.toLowerCase().includes(searchQuery) ||
      (p.brand || '').toLowerCase().includes(searchQuery) ||
      p.category.toLowerCase().includes(searchQuery);
    return matchFilter && matchSearch;
  });
}

function renderTable() {
  const list = getFiltered();
  tbody.innerHTML = '';

  if (list.length === 0) {
    emptyState.style.display = 'block';
    document.querySelector('.products-table thead').style.display = 'none';
  } else {
    emptyState.style.display = 'none';
    document.querySelector('.products-table thead').style.display = '';
    list.forEach(p => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>
          <div class="prod-cell">
            <div class="prod-thumb">
              ${p.image
                ? `<img src="${p.image}" alt="${p.name}"/>`
                : categoryEmoji(p.category)
              }
            </div>
            <div>
              <div class="prod-name">${p.name}</div>
              <div class="prod-brand">${p.brand || '—'}</div>
            </div>
          </div>
        </td>
        <td><span class="cat-pill">${p.category}</span></td>
        <td><span class="price-cell">$${Number(p.price).toLocaleString()}</span></td>
        <td>${p.oldPrice ? `<span class="old-price-cell">$${Number(p.oldPrice).toLocaleString()}</span>` : '—'}</td>
        <td>${renderBadge(p.badge)}</td>
        <td><span class="stock-cell ${Number(p.stock) < 25 ? 'stock-low' : 'stock-ok'}">${p.stock ?? '—'}</span></td>
        <td>
          <div class="actions-cell">
            <button class="action-btn edit" title="Edit" data-id="${p.id}">✏️</button>
            <button class="action-btn del"  title="Delete" data-id="${p.id}">🗑️</button>
          </div>
        </td>
      `;
      tbody.appendChild(tr);
    });
  }

  updateStats();
}

function renderBadge(badge) {
  if (!badge) return '<span class="badge-none">—</span>';
  return `<span class="badge badge-${badge}">${badge}</span>`;
}

function categoryEmoji(cat) {
  const map = { Gaming: '🎮', Laptops: '💻', Smartphones: '📱', Audio: '🎧', Monitors: '🖥️', Accessories: '🕹️' };
  return map[cat] || '📦';
}

// ─── STATS ───────────────────────────────────────
function updateStats() {
  document.getElementById('statTotal').textContent   = products.length;
  document.getElementById('statGaming').textContent  = products.filter(p => p.category === 'Gaming').length;
  document.getElementById('statTech').textContent    = products.filter(p => ['Laptops','Smartphones','Monitors'].includes(p.category)).length;
  document.getElementById('statSale').textContent    = products.filter(p => p.badge === 'sale').length;
}

// ─── FILTERS ─────────────────────────────────────
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    activeFilter = btn.dataset.filter;
    renderTable();
  });
});

// ─── SEARCH ──────────────────────────────────────
document.getElementById('searchInput').addEventListener('input', e => {
  searchQuery = e.target.value.toLowerCase().trim();
  renderTable();
});

// ─── OPEN / CLOSE MODAL ──────────────────────────
function openModal(product = null) {
  formError.textContent = '';
  currentImageBase64 = '';
  resetImagePreview();

  if (product) {
    editingId = product.id;
    modalTitle.textContent = 'Edit Product';
    fieldName.value     = product.name;
    fieldCategory.value = product.category;
    fieldBrand.value    = product.brand || '';
    fieldPrice.value    = product.price;
    fieldOldPrice.value = product.oldPrice || '';
    fieldBadge.value    = product.badge || '';
    fieldStock.value    = product.stock ?? '';
    fieldDesc.value     = product.desc || '';
    if (product.image) {
      currentImageBase64 = product.image;
      showImagePreview(product.image);
    }
  } else {
    editingId = null;
    modalTitle.textContent = 'Add Product';
    fieldName.value = fieldCategory.value = fieldBrand.value = '';
    fieldPrice.value = fieldOldPrice.value = fieldStock.value = fieldDesc.value = '';
    fieldBadge.value = '';
  }

  modalOverlay.classList.add('open');
}

function closeModal() {
  modalOverlay.classList.remove('open');
}

document.getElementById('openModalBtn').addEventListener('click', () => openModal());
document.getElementById('closeModalBtn').addEventListener('click', closeModal);
document.getElementById('cancelBtn').addEventListener('click', closeModal);
modalOverlay.addEventListener('click', e => { if (e.target === modalOverlay) closeModal(); });

// ─── IMAGE UPLOAD ─────────────────────────────────
document.getElementById('triggerUpload').addEventListener('click', () => imgFileInput.click());
document.getElementById('imgPreview').addEventListener('click', () => imgFileInput.click());

imgFileInput.addEventListener('change', e => {
  const file = e.target.files[0];
  if (!file) return;
  if (file.size > 5 * 1024 * 1024) {
    formError.textContent = 'Image must be under 5MB.';
    return;
  }
  const reader = new FileReader();
  reader.onload = ev => {
    currentImageBase64 = ev.target.result;
    showImagePreview(currentImageBase64);
    formError.textContent = '';
  };
  reader.readAsDataURL(file);
  // reset so same file can be re-selected
  imgFileInput.value = '';
});

function showImagePreview(src) {
  previewImg.src = src;
  previewImg.style.display = 'block';
  uploadPlaceholder.style.display = 'none';
}

function resetImagePreview() {
  previewImg.src = '';
  previewImg.style.display = 'none';
  uploadPlaceholder.style.display = 'block';
}

// ─── SAVE PRODUCT ────────────────────────────────
document.getElementById('saveBtn').addEventListener('click', () => {
  formError.textContent = '';

  const name     = fieldName.value.trim();
  const category = fieldCategory.value;
  const price    = fieldPrice.value.trim();

  if (!name)     { formError.textContent = 'Product name is required.'; return; }
  if (!category) { formError.textContent = 'Please select a category.'; return; }
  if (!price || isNaN(price)) { formError.textContent = 'Enter a valid price.'; return; }

  const data = {
    name,
    category,
    brand:    fieldBrand.value.trim(),
    price:    parseFloat(price),
    oldPrice: fieldOldPrice.value ? parseFloat(fieldOldPrice.value) : '',
    badge:    fieldBadge.value || '',
    stock:    fieldStock.value ? parseInt(fieldStock.value) : 0,
    desc:     fieldDesc.value.trim(),
    image:    currentImageBase64,
  };

  if (editingId !== null) {
    products = products.map(p => p.id === editingId ? { ...p, ...data } : p);
  } else {
    const newId = products.length ? Math.max(...products.map(p => p.id)) + 1 : 1;
    products.push({ id: newId, ...data });
  }

  saveProducts(products);
  renderTable();
  closeModal();
});

// ─── EDIT / DELETE VIA TABLE CLICKS ──────────────
tbody.addEventListener('click', e => {
  const editBtn = e.target.closest('.action-btn.edit');
  const delBtn  = e.target.closest('.action-btn.del');

  if (editBtn) {
    const id = parseInt(editBtn.dataset.id);
    const product = products.find(p => p.id === id);
    if (product) openModal(product);
  }

  if (delBtn) {
    const id = parseInt(delBtn.dataset.id);
    const product = products.find(p => p.id === id);
    if (product) {
      deleteTargetId = id;
      document.getElementById('deleteProductName').textContent = product.name;
      deleteOverlay.classList.add('open');
    }
  }
});

// ─── DELETE CONFIRM ───────────────────────────────
document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
  if (deleteTargetId !== null) {
    products = products.filter(p => p.id !== deleteTargetId);
    saveProducts(products);
    renderTable();
    deleteTargetId = null;
  }
  deleteOverlay.classList.remove('open');
});

document.getElementById('cancelDeleteBtn').addEventListener('click', () => deleteOverlay.classList.remove('open'));
document.getElementById('closeDeleteBtn').addEventListener('click', () => deleteOverlay.classList.remove('open'));
deleteOverlay.addEventListener('click', e => { if (e.target === deleteOverlay) deleteOverlay.classList.remove('open'); });

// ─── INIT ─────────────────────────────────────────
renderTable();