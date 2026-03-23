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
        <button class="btn-primary">Shop Now</button>
        <a href="#" class="btn-ghost">View Deals <span class="arrow">→</span></a>
      </div>
      <div class="hero-stats">
        <div><div class="stat-num">12K<span>+</span></div><div class="stat-label">Products</div></div>
        <div><div class="stat-num">98<span>%</span></div><div class="stat-label">Satisfaction</div></div>
        <div><div class="stat-num">50<span>+</span></div><div class="stat-label">Top Brands</div></div>
      </div>
    </div>
    <div class="hero-visual">
      <div class="mini-card left"><span class="icon">⚡</span><div><div>Fast Delivery</div><div class="label">Ships in 24h</div></div></div>
      <div class="hero-product-card">
        <span class="product-badge">-25%</span>
        <div class="hero-img-placeholder"><img id="heroImg" src="{{ asset('assets/hero-product.png') }}" alt="Hero Product" onerror="this.style.display='none';this.parentElement.innerHTML='💻';"/></div>
        <div class="hero-prod-name">ProBook Ultra X1</div>
        <div class="hero-prod-sub">16" · M4 Pro · 32GB RAM</div>
        <div class="hero-prod-price">$1,499</div>
        <div class="rating-row"><span class="stars">★★★★★</span><span>(2,341 reviews)</span></div>
      </div>
      <div class="mini-card right"><span class="icon">🔒</span><div><div>Secure Pay</div><div class="label">256-bit SSL</div></div></div>
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
      <a href="#" class="section-link">All categories →</a>
    </div>
    <div class="categories-grid" id="categoriesGrid"></div>
  </section>

  <!-- FEATURED PRODUCTS -->
  <section class="products-section fade-up">
    <div class="section-header">
      <div><div class="section-label">Hand-picked</div><h2 class="section-title">Featured Products</h2></div>
      <a href="#" class="section-link">View all →</a>
    </div>
    <div class="products-grid" id="productsGrid"></div>
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
      <div class="feature-item"><div class="feature-icon">🚀</div><div><div class="feature-title">Fast Shipping</div><div class="feature-desc">Orders over $99 ship free, delivered in 24–48 hours.</div></div></div>
      <div class="feature-item"><div class="feature-icon">🔄</div><div><div class="feature-title">Easy Returns</div><div class="feature-desc">30-day hassle-free returns on every purchase.</div></div></div>
      <div class="feature-item"><div class="feature-icon">🛡️</div><div><div class="feature-title">2-Year Warranty</div><div class="feature-desc">Every product covered against defects and damage.</div></div></div>
      <div class="feature-item"><div class="feature-icon">💬</div><div><div class="feature-title">24/7 Support</div><div class="feature-desc">Real humans ready to help, any time of day.</div></div></div>
    </div>
  </div>
@endsection
