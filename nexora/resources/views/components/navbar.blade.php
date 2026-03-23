<nav class="navbar">
    <a href="{{ url('/') }}" class="nav-logo">NEX<span>ORA</span></a>
    <ul class="nav-links">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ url('/shop') }}">Shop</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
    </ul>
    <div class="nav-actions">
        <div class="nav-search">
            <i data-lucide="search" style="width:16px;height:16px;"></i>
            <input type="text" id="navSearchInput" placeholder="Search products…" style="background:transparent;border:none;outline:none;color:var(--white);font-family:var(--font-body);font-size:13px;width:150px;"/>
        </div>
        <div class="cart-btn">
            <i data-lucide="shopping-cart" style="width:20px;height:20px;"></i>
            <span class="cart-count">0</span>
        </div>
        <div id="authArea">
            @auth
                <div class="auth-user">
                    <div class="auth-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                    <span class="auth-name">{{ explode(' ', auth()->user()->name)[0] }}</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout">Logout</button>
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
