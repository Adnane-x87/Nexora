@extends('layouts.admin')

@section('title', 'NEXORA — Dashboard')

@section('content')
    @if(session('success'))
    <div style="background: rgba(46,213,115,0.15); color: #2ed573; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 14px;">
        {{ session('success') }}
    </div>
    @endif
    @if($errors->any())
    <div style="background: rgba(255,71,87,0.15); color: #ff4757; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 14px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- STATISTICS -->
    <div class="page {{ session('activeTab', 'statistics') == 'statistics' ? 'active' : '' }}" id="page-statistics">
      <header class="topbar">
        <div class="topbar-left"><h1 class="page-title">Statistics</h1><span class="page-sub">Your store at a glance</span></div>
      </header>
      <div class="stats-row" id="statsRow">
        <div class="stat-card"><div class="stat-icon"><i data-lucide="package" style="width:24px;height:24px;"></i></div><div><div class="stat-val">{{ $products->count() }}</div><div class="stat-lbl">Products</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="tag" style="width:24px;height:24px;"></i></div><div><div class="stat-val">{{ $categories->count() }}</div><div class="stat-lbl">Categories</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="users" style="width:24px;height:24px;"></i></div><div><div class="stat-val">{{ $users->count() }}</div><div class="stat-lbl">Users</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="bar-chart-3" style="width:24px;height:24px;"></i></div><div><div class="stat-val">{{ $products->sum('stock') }}</div><div class="stat-lbl">Total Stock</div></div></div>
      </div>
      <div class="two-col">
        <div class="card">
            <div class="card-header"><div class="card-title">Products by Category</div></div>
            <div id="categoryBars">
                @php $maxCat = $categories->max('products_count') ?: 1; @endphp
                @foreach($categories as $c)
                <div class="cat-bar-item">
                    <div class="cat-bar-label">{{ $c->emoji }} {{ $c->name }}</div>
                    <div class="cat-bar-track"><div class="cat-bar-fill" style="width:{{ round(($c->products_count / $maxCat) * 100) }}%"></div></div>
                    <div class="cat-bar-count">{{ $c->products_count }}</div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="card">
            <div class="card-header"><div class="card-title">Recent Products</div></div>
            <div id="recentProducts">
                @php $recent = $products->take(5); @endphp
                @forelse($recent as $rp)
                <div class="recent-item">
                    <div class="recent-thumb">
                        @if($rp->image) <img src="{{ $rp->image }}" alt=""/> @else {{ $rp->category->emoji ?? '📦' }} @endif
                    </div>
                    <div class="recent-name">{{ $rp->name }}</div>
                    <div class="recent-price">${{ number_format($rp->price) }}</div>
                </div>
                @empty
                <p style="color:var(--white-dim);font-size:13px;">No products yet.</p>
                @endforelse
            </div>
        </div>
      </div>
      <div class="three-col" style="margin-top:20px;">
        <div class="card badge-stat-card"><div class="badge-stat-icon" style="background:rgba(232,255,71,0.15);color:#e8ff47;"><i data-lucide="star" style="width:20px;height:20px;"></i></div><div><div class="badge-stat-val" id="bsNew">{{ $products->where('badge', 'new')->count() }}</div><div class="badge-stat-lbl">New Products</div></div></div>
        <div class="card badge-stat-card"><div class="badge-stat-icon" style="background:rgba(255,71,87,0.15);color:#ff4757;"><i data-lucide="percent" style="width:20px;height:20px;"></i></div><div><div class="badge-stat-val" id="bsSale">{{ $products->where('badge', 'sale')->count() }}</div><div class="badge-stat-lbl">On Sale</div></div></div>
        <div class="card badge-stat-card"><div class="badge-stat-icon" style="background:rgba(255,107,53,0.15);color:#ff6b35;"><i data-lucide="flame" style="width:20px;height:20px;"></i></div><div><div class="badge-stat-val" id="bsHot">{{ $products->where('badge', 'hot')->count() }}</div><div class="badge-stat-lbl">Hot Items</div></div></div>
      </div>
    </div>

    <!-- PRODUCTS -->
    <div class="page {{ session('activeTab') == 'products' ? 'active' : '' }}" id="page-products">
      <header class="topbar">
        <div class="topbar-left"><h1 class="page-title">Products</h1><span class="page-sub">Manage your inventory</span></div>
        <div class="topbar-right">
          <button class="btn-add" onclick="openProductModal()">+ Add Product</button>
        </div>
      </header>
      <div class="prod-stats-row">
        <div class="stat-card"><div class="stat-icon"><i data-lucide="package" style="width:24px;height:24px;"></i></div><div><div class="stat-val" id="statTotal">{{ $products->count() }}</div><div class="stat-lbl">Total</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="gamepad-2" style="width:24px;height:24px;"></i></div><div><div class="stat-val" id="statGaming">{{ $products->where('category.name', 'Gaming')->count() }}</div><div class="stat-lbl">Gaming</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="laptop" style="width:24px;height:24px;"></i></div><div><div class="stat-val" id="statTech">{{ $products->whereIn('category.name', ['Laptops', 'Monitors'])->count() }}</div><div class="stat-lbl">Tech</div></div></div>
        <div class="stat-card"><div class="stat-icon"><i data-lucide="tag" style="width:24px;height:24px;"></i></div><div><div class="stat-val" id="statSale">{{ $products->where('badge', 'sale')->count() }}</div><div class="stat-lbl">On Sale</div></div></div>
      </div>
      <div class="table-wrap">
        <table class="products-table">
          <thead><tr><th>Product</th><th>Category</th><th>Price</th><th>Old Price</th><th>Badge</th><th>Stock</th><th>Actions</th></tr></thead>
          <tbody id="productsTableBody">
            @forelse($products as $p)
            <tr>
              <td><div class="prod-cell"><div class="prod-thumb">
                @if($p->image) <img src="{{ $p->image }}" alt=""/> @else {{ $p->category->emoji ?? '📦' }} @endif
              </div><div><div class="prod-name">{{ $p->name }}</div><div class="prod-brand">{{ $p->brand ?? '—' }}</div></div></div></td>
              <td><span class="cat-pill">{{ $p->category->name ?? 'None' }}</span></td>
              <td><span class="price-cell">${{ number_format($p->price) }}</span></td>
              <td>{!! $p->old_price ? '<span class="old-price-cell">$'.number_format($p->old_price).'</span>' : '—' !!}</td>
              <td>{!! $p->badge ? '<span class="badge badge-'.$p->badge.'">'.ucfirst($p->badge).'</span>' : '<span class="badge-none">—</span>' !!}</td>
              <td><span class="stock-cell {{ $p->stock < 25 ? 'stock-low' : 'stock-ok' }}">{{ $p->stock }}</span></td>
              <td><div class="actions-cell">
                <button type="button" class="action-btn edit" onclick="openProductModal({{ $p }})"><i data-lucide="edit-3" style="width:14px;height:14px;"></i></button>
                <button type="button" class="action-btn del" onclick="openDel('products', {{ $p->id }}, '{{ addslashes($p->name) }}')"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button>
              </div></td>
            </tr>
            @empty
            <div class="empty-state" id="prodEmptyState"><div class="empty-icon"><i data-lucide="inbox" style="width:48px;height:48px;"></i></div><div class="empty-title">No products found</div><div class="empty-sub">Add your first product.</div></div>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <!-- CATEGORIES -->
    <div class="page {{ session('activeTab') == 'categories' ? 'active' : '' }}" id="page-categories">
      <header class="topbar">
        <div class="topbar-left"><h1 class="page-title">Categories</h1><span class="page-sub">Manage product categories</span></div>
        <div class="topbar-right"><button class="btn-add" onclick="openCatModal()">+ Add Category</button></div>
      </header>
      <div class="categories-grid-manage" id="categoriesGrid">
        @foreach($categories as $c)
        <div class="cat-manage-card">
            <div class="cat-manage-actions">
                <button type="button" class="action-btn edit" onclick="openCatModal({{ $c }})"><i data-lucide="edit-3" style="width:14px;height:14px;"></i></button>
                <button type="button" class="action-btn del" onclick="openDel('categories', {{ $c->id }}, '{{ addslashes($c->name) }}')"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button>
            </div>
            <div class="cat-manage-icon">{{ $c->emoji }}</div>
            <div class="cat-manage-name">{{ $c->name }}</div>
            <div class="cat-manage-count">{{ $c->products_count }} products</div>
        </div>
        @endforeach
      </div>
    </div>

    <!-- USERS -->
    <div class="page {{ session('activeTab') == 'users' ? 'active' : '' }}" id="page-users">
      <header class="topbar">
        <div class="topbar-left"><h1 class="page-title">Users</h1><span class="page-sub">Manage store users & admins</span></div>
        <div class="topbar-right"><button class="btn-add" onclick="openUserModal()">+ Add User</button></div>
      </header>
      <div class="table-wrap">
        <table class="products-table">
          <thead><tr><th>User</th><th>Email</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
          <tbody id="usersTableBody">
            @forelse($users as $u)
            <tr>
              <td><div class="prod-cell"><div class="admin-avatar" style="width:36px;height:36px;font-size:13px;">{{ substr($u->name, 0, 1) }}</div><div class="prod-name">{{ $u->name }}</div></div></td>
              <td style="color:var(--white-dim)">{{ $u->email }}</td>
              <td><span class="role-badge role-{{ strtolower($u->role) }}">{{ $u->role }}</span></td>
              <td style="color:var(--white-dim)">{{ $u->created_at->format('Y-m-d') }}</td>
              <td><div class="actions-cell">
                <button type="button" class="action-btn edit" onclick="openUserModal({{ $u }})"><i data-lucide="edit-3" style="width:14px;height:14px;"></i></button>
                @if(auth()->id() !== $u->id)
                <button type="button" class="action-btn del" onclick="openDel('users', {{ $u->id }}, '{{ addslashes($u->name) }}')"><i data-lucide="trash-2" style="width:14px;height:14px;"></i></button>
                @endif
              </div></td>
            </tr>
            @empty
            <div class="empty-state" id="usersEmptyState"><div class="empty-icon"><i data-lucide="users" style="width:48px;height:48px;"></i></div><div class="empty-title">No users yet</div></div>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  <!-- PRODUCT MODAL -->
  <div class="modal-overlay" id="modalOverlay">
    <div class="modal">
      <div class="modal-header"><h2 class="modal-title" id="modalTitle">Add Product</h2><button type="button" class="modal-close" onclick="closeOverlay('modalOverlay')"><i data-lucide="x" style="width:20px;height:20px;"></i></button></div>
      <form id="productForm" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div id="prodMethod"></div>
        <div class="modal-body">
            <div class="img-upload-area">
            <input type="file" id="imgFileInput" name="image" accept="image/*" hidden/>
            <div class="img-preview" id="imgPreview" onclick="document.getElementById('imgFileInput').click()">
                <div id="uploadPlaceholder"><div style="margin-bottom:8px;color:var(--accent);"><i data-lucide="image-plus" style="width:32px;height:32px;"></i></div><div style="font-size:11px;color:var(--white-dim);">Click to upload image</div></div>
                <img id="previewImg" src="" alt="" style="display:none; width: 100%; height: 100%; object-fit: cover; border-radius: 8px;"/>
            </div>
            <button type="button" class="btn-upload" onclick="document.getElementById('imgFileInput').click()">Choose Image</button>
            </div>
            <div class="form-grid">
            <div class="form-group full"><label class="form-label">Product Name *</label><input type="text" name="name" class="form-input" id="fieldName" placeholder="e.g. PlayStation 5 Pro" required/></div>
            <div class="form-group"><label class="form-label">Category *</label><select name="category_id" class="form-input" id="fieldCategory" required>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}">{{ $c->emoji }} {{ $c->name }}</option>
                @endforeach
            </select></div>
            <div class="form-group"><label class="form-label">Brand</label><input type="text" name="brand" class="form-input" id="fieldBrand" placeholder="e.g. Sony"/></div>
            <div class="form-group"><label class="form-label">Price ($) *</label><input type="number" step="0.01" name="price" class="form-input" id="fieldPrice" placeholder="499" required/></div>
            <div class="form-group"><label class="form-label">Old Price ($)</label><input type="number" step="0.01" name="old_price" class="form-input" id="fieldOldPrice" placeholder="599"/></div>
            <div class="form-group"><label class="form-label">Badge</label><select name="badge" class="form-input" id="fieldBadge"><option value="">None</option><option value="new">New</option><option value="sale">Sale</option><option value="hot">Hot</option></select></div>
            <div class="form-group"><label class="form-label">Stock *</label><input type="number" name="stock" class="form-input" id="fieldStock" placeholder="100" value="0" required/></div>
            <div class="form-group full"><label class="form-label">Description</label><textarea name="description" class="form-input form-textarea" id="fieldDesc" placeholder="Short description…"></textarea></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeOverlay('modalOverlay')">Cancel</button><button type="submit" class="btn-save">Save Product</button></div>
      </form>
    </div>
  </div>

  <!-- CATEGORY MODAL -->
  <div class="modal-overlay" id="catModalOverlay">
    <div class="modal modal-small">
      <div class="modal-header"><h2 class="modal-title" id="catModalTitle">Add Category</h2><button type="button" class="modal-close" onclick="closeOverlay('catModalOverlay')"><i data-lucide="x" style="width:20px;height:20px;"></i></button></div>
      <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
        @csrf
        <div id="catMethod"></div>
        <div class="modal-body">
            <div class="form-group full"><label class="form-label">Category Name *</label><input type="text" name="name" class="form-input" id="catFieldName" placeholder="e.g. Smartphones" required/></div>
            <div class="form-group full"><label class="form-label">Emoji Icon</label><input type="text" name="emoji" class="form-input" id="catFieldEmoji" placeholder="e.g. 📱" maxlength="10"/></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeOverlay('catModalOverlay')">Cancel</button><button type="submit" class="btn-save">Save Category</button></div>
      </form>
    </div>
  </div>

  <!-- USER MODAL -->
  <div class="modal-overlay" id="userModalOverlay">
    <div class="modal modal-small">
      <div class="modal-header"><h2 class="modal-title" id="userModalTitle">Add User</h2><button type="button" class="modal-close" onclick="closeOverlay('userModalOverlay')"><i data-lucide="x" style="width:20px;height:20px;"></i></button></div>
      <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div id="userMethod"></div>
        <div class="modal-body">
            <div class="form-group full"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-input" id="userFieldName" placeholder="John Doe" required/></div>
            <div class="form-group full"><label class="form-label">Email *</label><input type="email" name="email" class="form-input" id="userFieldEmail" placeholder="john@example.com" required/></div>
            <div class="form-group full"><label class="form-label">Role</label><select name="role" class="form-input" id="userFieldRole"><option value="Admin">Admin</option><option value="Editor">Editor</option><option value="Viewer">Viewer</option></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeOverlay('userModalOverlay')">Cancel</button><button type="submit" class="btn-save">Save User</button></div>
      </form>
    </div>
  </div>

  <!-- DELETE MODAL -->
  <div class="modal-overlay" id="deleteOverlay">
    <div class="modal modal-small">
      <div class="modal-header"><h2 class="modal-title">Confirm Delete</h2><button type="button" class="modal-close" onclick="closeOverlay('deleteOverlay')"><i data-lucide="x" style="width:20px;height:20px;"></i></button></div>
      <form id="deleteForm" method="POST" action="">
        @csrf
        @method('DELETE')
        <div class="modal-body">
            <div class="delete-icon"><i data-lucide="alert-triangle" style="width:48px;height:48px;color:var(--red);"></i></div>
            <div class="delete-text">Are you sure you want to delete <span id="deleteItemName" style="color:var(--white);font-weight:600;"></span>? This action cannot be undone.</div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-cancel" onclick="closeOverlay('deleteOverlay')">Cancel</button><button type="submit" class="btn-save btn-del-confirm">Delete</button></div>
      </form>
    </div>
  </div>

  <script>
    function openOverlay(id) { document.getElementById(id).classList.add('open'); }
    function closeOverlay(id) { document.getElementById(id).classList.remove('open'); }

    // Navigation fallback for server side rendered tabs handling
    document.querySelectorAll('.nav-item[data-page]').forEach(item => {
        item.addEventListener('click', e => {
            e.preventDefault();
            document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.nav-item[data-page]').forEach(n => n.classList.remove('active'));
            document.getElementById('page-' + item.dataset.page)?.classList.add('active');
            item.classList.add('active');
        });
    });

    // Make Image Preview work
    document.getElementById('imgFileInput').addEventListener('change', e => {
        const file = e.target.files[0]; if (!file) return;
        const reader = new FileReader();
        reader.onload = ev => { 
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('previewImg').style.display = 'block'; 
            document.getElementById('uploadPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    function openProductModal(prod = null) {
        let form = document.getElementById('productForm');
        let mtd = document.getElementById('prodMethod');
        document.getElementById('previewImg').style.display = 'none';
        document.getElementById('uploadPlaceholder').style.display = 'block';
        if (prod) {
            document.getElementById('modalTitle').textContent = 'Edit Product';
            form.action = `/admin/products/${prod.id}`;
            mtd.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('fieldName').value = prod.name;
            document.getElementById('fieldCategory').value = prod.category_id;
            document.getElementById('fieldBrand').value = prod.brand || '';
            document.getElementById('fieldPrice').value = prod.price;
            document.getElementById('fieldOldPrice').value = prod.old_price || '';
            document.getElementById('fieldBadge').value = prod.badge || '';
            document.getElementById('fieldStock').value = prod.stock;
            document.getElementById('fieldDesc').value = prod.description || '';
            if (prod.image) {
                document.getElementById('previewImg').src = prod.image;
                document.getElementById('previewImg').style.display = 'block';
                document.getElementById('uploadPlaceholder').style.display = 'none';
            }
        } else {
            document.getElementById('modalTitle').textContent = 'Add Product';
            form.action = "{{ route('admin.products.store') }}";
            mtd.innerHTML = '';
            form.reset();
        }
        openOverlay('modalOverlay');
    }

    function openCatModal(cat = null) {
        let form = document.getElementById('categoryForm');
        let mtd = document.getElementById('catMethod');
        if (cat) {
            document.getElementById('catModalTitle').textContent = 'Edit Category';
            form.action = `/admin/categories/${cat.id}`;
            mtd.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('catFieldName').value = cat.name;
            document.getElementById('catFieldEmoji').value = cat.emoji || '';
        } else {
            document.getElementById('catModalTitle').textContent = 'Add Category';
            form.action = "{{ route('admin.categories.store') }}";
            mtd.innerHTML = '';
            form.reset();
        }
        openOverlay('catModalOverlay');
    }

    function openUserModal(user = null) {
        let form = document.getElementById('userForm');
        let mtd = document.getElementById('userMethod');
        if (user) {
            document.getElementById('userModalTitle').textContent = 'Edit User';
            form.action = `/admin/users/${user.id}`;
            mtd.innerHTML = '<input type="hidden" name="_method" value="PUT">';
            document.getElementById('userFieldName').value = user.name;
            document.getElementById('userFieldEmail').value = user.email;
            document.getElementById('userFieldRole').value = user.role;
        } else {
            document.getElementById('userModalTitle').textContent = 'Add User';
            form.action = "{{ route('admin.users.store') }}";
            mtd.innerHTML = '';
            form.reset();
        }
        openOverlay('userModalOverlay');
    }

    function openDel(type, id, name) {
        document.getElementById('deleteItemName').textContent = name;
        document.getElementById('deleteForm').action = `/admin/${type}/${id}`;
        openOverlay('deleteOverlay');
    }

    // Set active nav based on active tab
    setTimeout(() => {
        let activeTab = "{{ session('activeTab', 'statistics') }}";
        if (document.getElementById('page-' + activeTab)) {
            document.querySelectorAll('.nav-item[data-page]').forEach(n => n.classList.remove('active'));
            let nav = document.querySelector(`.nav-item[data-page="${activeTab}"]`);
            if(nav) nav.classList.add('active');
        }
    }, 100);
  </script>
@endsection
