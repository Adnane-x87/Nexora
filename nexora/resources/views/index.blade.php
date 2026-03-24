@extends('layouts.app')

@section('title', 'NEXORA — Next-Gen Electronics')

@section('content')
  <!-- HERO -->
  <section class="hero">
    <div class="hero-grid-bg"></div>
    <div class="hero-content">
      <div class="hero-tag">New arrivals {{ date('Y') }}</div>
      <h1 class="hero-title">
        <span class="line">THE FUTURE</span>
        <span class="line">IS IN YOUR</span>
        <span class="line accent-word">HANDS.</span>
      </h1>
      <p class="hero-sub">Cutting-edge electronics engineered for those who refuse to settle. Explore flagship devices before anyone else.</p>
      <div class="hero-ctas">
        <a href="{{ route('shop') }}"><button class="btn-primary">Shop Now</button></a>
        <a href="#" class="btn-ghost">View Deals <span class="arrow">→</span></a>
      </div>
      <div class="hero-stats">
        <div><div class="stat-num">12K<span>+</span></div><div class="stat-label">Products</div></div>
        <div><div class="stat-num">98<span>%</span></div><div class="stat-label">Satisfaction</div></div>
        <div><div class="stat-num">50<span>+</span></div><div class="stat-label">Top Brands</div></div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="mini-card left"><span class="icon"><i data-lucide="zap" style="width:16px;height:16px;"></i></span><div><div>Fast Delivery</div><div class="label">Ships in 24h</div></div></div>
      <div class="hero-product-card">
        <span class="product-badge">-25%</span>
        <div class="hero-img-placeholder">
          <img id="heroImg" src="{{ asset('assets/hero-product.png') }}" alt="Hero Product" onload="this.parentElement.style.background='none'" onerror="this.style.display='none'"/>
        </div>
        <div class="hero-prod-name">ProBook Ultra X1</div>
        <div class="hero-prod-sub">16" · M4 Pro · 32GB RAM</div>
        <div class="hero-prod-price">$1,499</div>
        <div class="rating-row"><span class="stars">★★★★★</span><span>(2,341 reviews)</span></div>
      </div>
      <div class="mini-card right"><span class="icon"><i data-lucide="shield-check" style="width:16px;height:16px;"></i></span><div><div>Secure Pay</div><div class="label">256-bit SSL</div></div></div>
    </div>
  </section>

  <!-- MARQUEE -->
  <div class="marquee-strip">
    <div class="marquee-track">
      <span class="marquee-item"><span class="marquee-dot">●</span> Free shipping over $99</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> New arrivals every week</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> 2-year warranty on all products</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> 30-day returns, no questions asked</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> Apple · Samsung · Sony · Lenovo · Asus</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> Free shipping over $99</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> New arrivals every week</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> 2-year warranty on all products</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> 30-day returns, no questions asked</span>
      <span class="marquee-item"><span class="marquee-dot">●</span> Apple · Samsung · Sony · Lenovo · Asus</span>
    </div>
  </div>

  <!-- CATEGORIES -->
  <section class="categories-section fade-up">
    <div class="section-header">
      <div><div class="section-label">Browse</div><h2 class="section-title">Shop by Category</h2></div>
      <a href="{{ route('shop') }}" class="section-link">All categories →</a>
    </div>
    <div class="categories-grid" id="categoriesGrid">
      @if ($topCategories && count($topCategories) > 0)
        @for ($i = 0; $i < count($topCategories); $i++)
          @php $cat = $topCategories[$i]; @endphp
          <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="category-card" style="text-decoration:none;">
            <div class="cat-icon">{{ $cat->emoji }}</div>
            <div class="cat-name" style="color:var(--white);font-weight:600;">{{ $cat->name }}</div>
            <div class="cat-count" style="color:var(--white-dim);font-size:13px;margin-top:4px;">{{ $cat->products_count }} Products</div>
          </a>
        @endfor
      @else
        <p style="color:var(--white-dim);">No categories available.</p>
      @endif
    </div>
  </section>

  <!-- FEATURED PRODUCTS -->
  <section class="products-section fade-up">
    <div class="section-header">
      <div><div class="section-label">Hand-picked</div><h2 class="section-title">Featured Products</h2></div>
      <a href="{{ route('shop') }}" class="section-link">View all →</a>
    </div>
    <div class="products-grid" id="productsGrid">
      @if ($featuredProducts && count($featuredProducts) > 0)
        @for ($i = 0; $i < count($featuredProducts); $i++)
          @php $fp = $featuredProducts[$i]; @endphp
          <div class="product-card">
            @if($fp->badge)<span class="tag {{ $fp->badge }}">{{ ucfirst($fp->badge) }}</span>@endif
            @auth
            <form action="{{ route('wishlist.toggle', $fp->id) }}" method="POST" class="wishlist-form">
              @csrf
              <button type="submit" class="wishlist-btn {{ auth()->user()->wishlistedProducts->contains($fp->id) ? 'active' : '' }}">
                  <i data-lucide="heart" style="width:16px;height:16px;"></i>
              </button>
            </form>
            @else
            <div class="wishlist-btn" onclick="window.location='{{ route('login') }}'"><i data-lucide="heart" style="width:16px;height:16px;"></i></div>
            @endauth

            <a href="{{ route('product.show', $fp->slug) }}" class="product-img-wrap" style="text-decoration:none;">
              @if($fp->image)<img src="{{ \Illuminate\Support\Facades\Storage::url($fp->image) }}" alt="" style="width:100%;height:100%;object-fit:cover;"/>@else <div style="font-size:48px;display:flex;align-items:center;justify-content:center;height:100%;">{{ $fp->category->emoji ?? '📦' }}</div> @endif
            </a>
            <div class="product-info">
              <div class="product-brand">{{ $fp->brand ?? '' }}</div>
              <a href="{{ route('product.show', $fp->slug) }}" class="product-name" style="text-decoration:none;color:var(--white);">{{ $fp->name }}</a>
              <div class="product-rating"><span class="s">★★★★★</span> 4.9</div>
              <div class="product-footer">
                <div class="price-wrap">
                  <span class="price-new">${{ number_format($fp->price) }}</span>
                  @if($fp->old_price)<span class="price-old">${{ number_format($fp->old_price) }}</span>@endif
                </div>
                @auth

                <form action="{{ route('cart.add', $fp->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="add-cart-btn" title="Add to Cart"><i data-lucide="plus" style="width:16px;height:16px;"></i></button>
                </form>
                @else
                <button class="add-cart-btn" title="Add to Cart" onclick="window.location='{{ route('login') }}'"><i data-lucide="plus" style="width:16px;height:16px;"></i></button>
                @endauth
              </div>
            </div>
          </div>
        @endfor
      @else
        <p style="color:var(--white-dim);">No featured products yet.</p>
      @endif
    </div>
  </section>

  <!-- PROMO BANNER -->
  <div class="promo-section fade-up">
    <div class="promo-banner">
      <div>
        <div class="promo-tag">⚡ Limited time offer</div>
        <div class="promo-title">UP TO 40% OFF<br>ON AUDIO GEAR</div>
        <div class="promo-sub">Ends March 31 · Free shipping included</div>
      </div>
      <button class="btn-dark">Grab the Deal →</button>
    </div>
  </div>

  <!-- FEATURES -->
  <div class="features-section fade-up">
    <div class="features-grid">
      <div class="feature-item"><div class="feature-icon"><i data-lucide="truck"></i></div><div><div class="feature-title">Fast Shipping</div><div class="feature-desc">Orders over $99 ship free, delivered in 24–48 hours.</div></div></div>
      <div class="feature-item"><div class="feature-icon"><i data-lucide="refresh-cw"></i></div><div><div class="feature-title">Easy Returns</div><div class="feature-desc">30-day hassle-free returns on every purchase.</div></div></div>
      <div class="feature-item"><div class="feature-icon"><i data-lucide="shield"></i></div><div><div class="feature-title">2-Year Warranty</div><div class="feature-desc">Every product covered against defects and damage.</div></div></div>
      <div class="feature-item"><div class="feature-icon"><i data-lucide="message-circle"></i></div><div><div class="feature-title">24/7 Support</div><div class="feature-desc">Real humans ready to help, any time of day.</div></div></div>
    </div>
  </div>
@endsection
