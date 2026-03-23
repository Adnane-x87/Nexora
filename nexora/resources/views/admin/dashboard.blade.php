@extends('layouts.admin')

@section('title', 'NEXORA — Dashboard')

@section('content')
    <!-- STATISTICS -->
    <div class="page" id="page-statistics">
      <header class="topbar">
        <div class="topbar-left"><h1 class="page-title">Statistics</h1><span class="page-sub">Your store at a glance</span></div>
      </header>
      <div class="stats-row" id="statsRow"></div>
      <div class="two-col">
        <div class="card"><div class="card-header"><div class="card-title">Products by Category</div></div><div id="categoryBars"></div></div>
        <div class="card"><div class="card-header"><div class="card-title">Recent Products</div></div><div id="recentProducts"></div></div>
      </div>
      <div class="three-col" style="margin-top:20px;">
        <div class="card badge-stat-card"><div class="badge-stat-icon" style="background:rgba(232,255,71,0.15);color:#e8ff47;"><i data-lucide="star" style="width:20px;height:20px;"></i></div><div><div class="badge-stat-val" id="bsNew">0</div><div class="badge-stat-lbl">New Products</div></div></div>
        <div class="card badge-stat-card"><div class="badge-stat-icon" style="background:rgba(255,71,87,0.15);color:#ff4757;"><i data-lucide="percent" style="width:20px;height:20px;"></i></div><div><div class="badge-stat-val" id="bsSale">0</div><div class="badge-stat-lbl">On Sale</div></div></div>
        <div class="card badge-stat-card"><div class="badge-stat-icon" style="background:rgba(255,107,53,0.15);color:#ff6b35;"><i data-lucide="flame" style="width:20px;height:20px;"></i></div><div><div class="badge-stat-val" id="bsHot">0</div><div class="badge-stat-lbl">Hot Items</div></div></div>
      </div>
    </div>

    <!-- PRODUCTS -->
    <div class="page" id="page-products">
      <header class="topbar">
        <div class="topbar-left"><h1 class="page-title">Products</h1><span class="page-sub">Manage your inventory</span></div>
        <div class="topbar-right">
          <div class="search-wrap"><span class="search-icon"><i data-lucide="search" style="width:16px;height:16px;"></i></span><input type="text" class="search-input" id="searchInput" placeholder="Search products…"/></div>
          <button class="btn-add" id="openModalBtn">+ Add Product</button>
        </div>
      </header>
      <div class="prod-stats-row">
        <div class="stat-card"><div class="stat-icon"><i data-lucide="package" style="width:24px;height:24px;"></i></div><div><div class="stat-val" id="statTotal">0</div><div class="stat-lbl">Total</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="gamepad-2" style="width:24px;height:24px;"></i></div><div><div class="stat-val" id="statGaming">0</div><div class="stat-lbl">Gaming</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="laptop" style="width:24px;height:24px;"></i></div><div><div class="stat-val" id="statTech">0</div><div class="stat-lbl">Tech</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="tag" style="width:24px;height:24px;"></i></div><div><div class="stat-val" id="statSale">0</div><div class="stat-lbl">On Sale</div></div></div>
      </div>
      <div class="filters-row" id="productFilters"></div>
      <div class="table-wrap">
        <table class="products-table">
          <thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Old Price</th><th>Badge</th><th>Stock</th><th>Actions</th></tr></thead>
          <tbody id="productsTableBody"></tbody>
        </table>
        <div class="empty-state" id="prodEmptyState" style="display:none;"><div class="empty-icon"><i data-lucide="inbox" style="width:48px;height:48px;"></i></div><div class="empty-title">No products found</div><div class="empty-sub">Add your first product or change the filter.</div></div>
      </div>
    </div>

    <!-- CATEGORIES -->
    <div class="page" id="page-categories">
      <header class="topbar">
        <div class="topbar-left"><h1 class="page-title">Categories</h1><span class="page-sub">Manage product categories</span></div>
        <div class="topbar-right"><button class="btn-add" id="openCatModalBtn">+ Add Category</button></div>
      </header>
      <div class="categories-grid-manage" id="categoriesGrid"></div>
    </div>

    <!-- USERS -->
    <div class="page" id="page-users">
      <header class="topbar">
        <div class="topbar-left"><h1 class="page-title">Users</h1><span class="page-sub">Manage store users & admins</span></div>
        <div class="topbar-right"><button class="btn-add" id="openUserModalBtn">+ Add User</button></div>
      </header>
      <div class="table-wrap">
        <table class="products-table">
          <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
          <tbody id="usersTableBody"></tbody>
        </table>
        <div class="empty-state" id="usersEmptyState" style="display:none;"><div class="empty-icon"><i data-lucide="users" style="width:48px;height:48px;"></i></div><div class="empty-title">No users yet</div><div class="empty-sub">Add your first user.</div></div>
      </div>
    </div>

  <!-- PRODUCT MODAL -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal">
      <div class="modal-header"><h2 class="modal-title" id="modalTitle">Add Product</h2><button class="modal-close" id="closeModalBtn"><i data-lucide="x" style="width:20px;height:20px;"></i></button></div>
      <div class="modal-body">
        <div class="img-upload-area">
          <input type="file" id="imgFileInput" accept="image/*" hidden/>
          <div class="img-preview" id="imgPreview">
            <div id="uploadPlaceholder"><div style="margin-bottom:8px;color:var(--accent);"><i data-lucide="image-plus" style="width:32px;height:32px;"></i></div><div style="font-size:11px;color:var(--white-dim);">Click to upload image</div></div>
            <img id="previewImg" src="" alt="" style="display:none;"/>
          </div>
          <button type="button" class="btn-upload" id="triggerUpload">Choose Image</button>
        </div>
        <div class="form-grid">
          <div class="form-group full"><label class="form-label">Product Name *</label><input type="text" class="form-input" id="fieldName" placeholder="e.g. PlayStation 5 Pro"/></div>
          <div class="form-group"><label class="form-label">Category *</label><select class="form-input" id="fieldCategory"></select></div>
          <div class="form-group"><label class="form-label">Brand</label><input type="text" class="form-input" id="fieldBrand" placeholder="e.g. Sony"/></div>
          <div class="form-group"><label class="form-label">Price ($) *</label><input type="number" class="form-input" id="fieldPrice" placeholder="499"/></div>
          <div class="form-group"><label class="form-label">Old Price ($)</label><input type="number" class="form-input" id="fieldOldPrice" placeholder="599"/></div>
          <div class="form-group"><label class="form-label">Badge</label><select class="form-input" id="fieldBadge"><option value="">None</option><option value="new">New</option><option value="sale">Sale</option><option value="hot">Hot</option></select></div>
          <div class="form-group"><label class="form-label">Stock</label><input type="number" class="form-input" id="fieldStock" placeholder="100"/></div>
          <div class="form-group full"><label class="form-label">Description</label><textarea class="form-input form-textarea" id="fieldDesc" placeholder="Short description…"></textarea></div>
        </div>
        <div class="form-error" id="formError"></div>
      </div>
      <div class="modal-footer"><button class="btn-cancel" id="cancelBtn">Cancel</button><button class="btn-save" id="saveBtn">Save Product</button></div>
    </div>
  </div>

  <!-- CATEGORY MODAL -->
  <div class="modal-overlay" id="catModalOverlay">
    <div class="modal modal-small">
      <div class="modal-header"><h2 class="modal-title" id="catModalTitle">Add Category</h2><button class="modal-close" id="closeCatModalBtn"><i data-lucide="x" style="width:20px;height:20px;"></i></button></div>
      <div class="modal-body">
        <div class="form-group full"><label class="form-label">Category Name *</label><input type="text" class="form-input" id="catFieldName" placeholder="e.g. Smartphones"/></div>
        <div class="form-group full"><label class="form-label">Emoji Icon</label><input type="text" class="form-input" id="catFieldEmoji" placeholder="e.g. 📱" maxlength="4"/></div>
        <div class="form-error" id="catFormError"></div>
      </div>
      <div class="modal-footer"><button class="btn-cancel" id="cancelCatBtn">Cancel</button><button class="btn-save" id="saveCatBtn">Save Category</button></div>
    </div>
  </div>

  <!-- USER MODAL -->
  <div class="modal-overlay" id="userModalOverlay">
    <div class="modal modal-small">
      <div class="modal-header"><h2 class="modal-title" id="userModalTitle">Add User</h2><button class="modal-close" id="closeUserModalBtn"><i data-lucide="x" style="width:20px;height:20px;"></i></button></div>
      <div class="modal-body">
        <div class="form-group full"><label class="form-label">Full Name *</label><input type="text" class="form-input" id="userFieldName" placeholder="John Doe"/></div>
        <div class="form-group full"><label class="form-label">Email *</label><input type="email" class="form-input" id="userFieldEmail" placeholder="john@example.com"/></div>
        <div class="form-group full"><label class="form-label">Role</label><select class="form-input" id="userFieldRole"><option value="Admin">Admin</option><option value="Editor">Editor</option><option value="Viewer">Viewer</option></select></div>
        <div class="form-error" id="userFormError"></div>
      </div>
      <div class="modal-footer"><button class="btn-cancel" id="cancelUserBtn">Cancel</button><button class="btn-save" id="saveUserBtn">Save User</button></div>
    </div>
  </div>

  <!-- DELETE MODAL -->
  <div class="modal-overlay" id="deleteOverlay">
    <div class="modal modal-small">
      <div class="modal-header"><h2 class="modal-title">Confirm Delete</h2><button class="modal-close" id="closeDeleteBtn"><i data-lucide="x" style="width:20px;height:20px;"></i></button></div>
      <div class="modal-body">
        <div class="delete-icon"><i data-lucide="alert-triangle" style="width:48px;height:48px;color:var(--red);"></i></div>
        <div class="delete-text">Are you sure you want to delete <span id="deleteItemName" style="color:var(--white);font-weight:600;"></span>? This action cannot be undone.</div>
      </div>
      <div class="modal-footer"><button class="btn-cancel" id="cancelDeleteBtn">Cancel</button><button class="btn-save btn-del-confirm" id="confirmDeleteBtn">Delete</button></div>
    </div>
  </div>
@endsection
