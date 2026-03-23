<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-logo">NEX<span>ORA</span></a>
    <ul class="nav-links">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('/shop') }}">Shop</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
    </ul>
    <div class="nav-actions">
        <form action="{{ route('shop') }}" method="GET" class="nav-search">
            <i data-lucide="search" style="width:16px;height:16px;"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products…" style="background:transparent;border:none;outline:none;color:var(--white);font-family:var(--font-body);font-size:13px;width:150px;"/>
        </form>
        @auth
        <a href="{{ route('cart.index') }}" class="cart-btn" style="text-decoration:none; color:var(--white);">
            <i data-lucide="shopping-cart" style="width:20px;height:20px;"></i>
            <span class="cart-count">{{ count(session('cart', [])) }}</span>
        </a>
        @endauth
        <div id="authArea">
            @auth
                <div class="auth-user" style="display:flex; align-items:center; gap:10px;">
                    @if(in_array(auth()->user()->role, ['Admin', 'Editor']))
                        <a href="{{ route('admin.dashboard') }}" style="color:var(--accent); font-size:13px; text-decoration:none; font-weight:600; margin-right:10px;">Dashboard</a>
                    @endif
                    <a href="{{ route('profile') }}" class="auth-user-link" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
                        <div class="auth-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                        <span class="auth-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout" style="background:transparent;border:1px solid var(--border);color:var(--white);padding:4px 10px;border-radius:4px;font-size:12px;cursor:pointer;">Logout</button>
                    </form>
                </div>
            @else
                <div class="auth-guest">
                    <a href="{{ route('login') }}" class="btn-accent-sm">Sign In</a>
                </div>
            @endauth
        </div>
    </div>
</nav>
