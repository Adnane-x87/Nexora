<aside class="sidebar">
    <div class="sidebar-logo">NEX<span>ORA</span></div>
    <div class="sidebar-label">Overview</div>
    <nav class="sidebar-nav">
        <a href="#" class="nav-item {{ $activePage === 'statistics' ? 'active' : '' }}" data-page="statistics"><span class="nav-icon"><i data-lucide="bar-chart-3" style="width:18px;height:18px;"></i></span> Statistics</a>
    </nav>
    <div class="sidebar-label">Manage</div>
    <nav class="sidebar-nav">
        <a href="#" class="nav-item {{ $activePage === 'products' ? 'active' : '' }}" data-page="products"><span class="nav-icon"><i data-lucide="package" style="width:18px;height:18px;"></i></span> Products</a>
        <a href="#" class="nav-item {{ $activePage === 'categories' ? 'active' : '' }}" data-page="categories"><span class="nav-icon"><i data-lucide="tag" style="width:18px;height:18px;"></i></span> Categories</a>
        <a href="#" class="nav-item {{ $activePage === 'orders' ? 'active' : '' }}" data-page="orders"><span class="nav-icon"><i data-lucide="shopping-bag" style="width:18px;height:18px;"></i></span> Orders</a>
        <a href="#" class="nav-item {{ $activePage === 'users' ? 'active' : '' }}" data-page="users"><span class="nav-icon"><i data-lucide="users" style="width:18px;height:18px;"></i></span> Users</a>
    </nav>
    <div class="sidebar-label">Store</div>
    <nav class="sidebar-nav">
        <a href="{{ url('/') }}" class="nav-item"><span class="nav-icon"><i data-lucide="globe" style="width:18px;height:18px;"></i></span> View Store</a>
        <form method="POST" action="{{ route('logout') }}" id="logoutForm" style="display: none;">
            @csrf
        </form>
        <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logoutForm').submit();"><span class="nav-icon"><i data-lucide="log-out" style="width:18px;height:18px;"></i></span> Logout</a>
    </nav>
    <div class="sidebar-bottom">
        <div class="admin-card">
            <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div class="admin-name">{{ auth()->user()->name }}</div>
                <div class="admin-role">Admin</div>
            </div>
        </div>
    </div>
</aside>
