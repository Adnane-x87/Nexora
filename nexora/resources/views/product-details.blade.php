@extends('layouts.app')

@section('title', 'NEXORA — ' . $product->name)

@section('content')
<div class="product-details-page">
    <div class="container">
        <nav class="breadcrumb">
            <a href="{{ route('home') }}">Home</a> / 
            <a href="{{ route('shop') }}">Shop</a> / 
            <a href="{{ route('shop', ['category' => $product->category->slug ?? '']) }}">{{ $product->category->name ?? 'Uncategorized' }}</a> /
            <span>{{ $product->name }}</span>
        </nav>

        <div class="product-main-grid">
            <!-- PRODUCT INFO (Left on desktop) -->
            <div class="product-info-column">
                <div class="product-brand">{{ $product->brand }}</div>
                <h1 class="product-title">{{ $product->name }}</h1>
                
                <div class="product-meta">
                    <div class="product-rating">
                        <span class="stars">★★★★★</span>
                        <span class="count">4.9 (1,240 reviews)</span>
                    </div>
                    @if($product->badge)
                        <span class="tag {{ $product->badge }}">{{ ucfirst($product->badge) }}</span>
                    @endif
                </div>

                <div class="product-price-section">
                    <div class="price-row">
                        <span class="price-main">${{ number_format($product->price) }}</span>
                        @if($product->old_price)
                            <span class="price-old">${{ number_format($product->old_price) }}</span>
                            <span class="discount-badge">-{{ round((1 - ($product->price / $product->old_price)) * 100) }}%</span>
                        @endif
                    </div>
                </div>

                <div class="product-description">
                    <h3>Overview</h3>
                    <p>{{ $product->description }}</p>
                </div>

                <div class="product-specs">
                    <div class="spec-item">
                        <span class="spec-label">Availability</span>
                        <span class="spec-value {{ $product->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                            {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' units)' : 'Out of Stock' }}
                        </span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Category</span>
                        <span class="spec-value">{{ $product->category->name ?? 'N/A' }}</span>
                    </div>
                </div>

                <div class="product-actions">
                    @auth
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="display:flex; align-items:center; gap:20px;">
                        @csrf
                        <div class="qty-selector">
                            <button type="button" onclick="this.nextElementSibling.stepDown()">-</button>
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
                            <button type="button" onclick="this.previousElementSibling.stepUp()">+</button>
                        </div>
                        <button type="submit" class="btn-primary add-to-cart">Add to Cart</button>
                    </form>
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-wishlist {{ auth()->user()->wishlistedProducts->contains($product->id) ? 'active' : '' }}">
                            <i data-lucide="heart"></i>
                        </button>
                    </form>
                    @else
                    <p class="login-prompt">Please <a href="{{ route('login') }}">login</a> to add to cart or wishlist.</p>
                    @endauth
                </div>
                
                <div class="trust-badges">
                    <div class="trust-item"><i data-lucide="truck"></i><span>Free Worldwide Shipping</span></div>
                    <div class="trust-item"><i data-lucide="shield-check"></i><span>2 Year Official Warranty</span></div>
                    <div class="trust-item"><i data-lucide="refresh-cw"></i><span>30-Day Hassle-Free Returns</span></div>
                </div>
            </div>

            <!-- PRODUCT VISUAL (Right on desktop) -->
            <div class="product-visual-column">
                <div class="main-image-wrap">
                    @if($product->image)
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" alt="{{ $product->name }}" class="main-image">
                    @else
                        <div class="image-placeholder">
                            <span style="font-size:120px;">{{ $product->category->emoji ?? '📦' }}</span>
                        </div>
                    @endif
                </div>
                <div class="image-thumbnails">
                    <div class="thumb active"><img src="{{ \Illuminate\Support\Facades\Storage::url($product->image) }}" alt=""></div>
                    <!-- Mock thumbnails for UI -->
                    <div class="thumb"></div>
                    <div class="thumb"></div>
                </div>
            </div>
        </div>

        <!-- RELATED PRODUCTS -->
        @if($relatedProducts->count() > 0)
        <section class="related-products">
            <h2 class="section-title">You Might Also Like</h2>
            <div class="products-grid">
                @if ($relatedProducts && count($relatedProducts) > 0)
                    @for ($i = 0; $i < count($relatedProducts); $i++)
                        @php $rp = $relatedProducts[$i]; @endphp
                        <div class="product-card">
                            @if($rp->badge)<span class="tag {{ $rp->badge }}">{{ ucfirst($rp->badge) }}</span>@endif
                            <a href="{{ route('product.show', $rp->slug) }}" class="product-img-wrap" style="text-decoration:none;">
                                @if($rp->image)<img src="{{ \Illuminate\Support\Facades\Storage::url($rp->image) }}" alt=""/>@else <div style="font-size:48px;display:flex;align-items:center;justify-content:center;height:100%;">{{ $rp->category->emoji ?? '📦' }}</div> @endif
                            </a>
                            <div class="product-info">
                                <div class="product-brand">{{ $rp->brand }}</div>
                                <a href="{{ route('product.show', $rp->slug) }}" class="product-name" style="text-decoration:none;color:var(--white);">{{ $rp->name }}</a>
                                <div class="product-footer">
                                    <span class="price-new">${{ number_format($rp->price) }}</span>
                                    @auth
                                    <form action="{{ route('wishlist.toggle', $rp->id) }}" method="POST" style="margin-left:auto;">
                                        @csrf
                                        <button type="submit" class="wishlist-btn-small {{ auth()->user()->wishlistedProducts->contains($rp->id) ? 'active' : '' }}">
                                            <i data-lucide="heart" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endfor
                @endif
            </div>
        </section>
        @endif
    </div>
</div>

<style>
.product-details-page { padding: 120px 0 80px; }
.container { padding: 0 48px; }
.breadcrumb { font-size: 13px; color: var(--white-dim); margin-bottom: 40px; }
.breadcrumb a { color: var(--white-dim); text-decoration: none; transition: color 0.2s; }
.breadcrumb a:hover { color: var(--accent); }
.breadcrumb span { color: var(--white); }

.product-main-grid { display: grid; grid-template-columns: 1fr 1.2fr; gap: 100px; align-items: start; }

.product-info-column { padding-top: 10px; }
.product-brand { color: var(--accent); font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; font-size: 14px; margin-bottom: 16px; }
.product-title { font-family: var(--font-display); font-size: clamp(32px, 5vw, 56px); font-weight: 800; line-height: 1.1; margin-bottom: 24px; }

.product-meta { display: flex; align-items: center; gap: 24px; margin-bottom: 40px; }
.product-rating .stars { color: #ffca2c; margin-right: 8px; }
.product-rating .count { font-size: 14px; color: var(--white-dim); }

.price-row { display: flex; align-items: baseline; gap: 20px; margin-bottom: 40px; }
.price-main { font-size: 36px; font-weight: 800; color: var(--white); }
.price-old { font-size: 22px; color: var(--white-dim); text-decoration: line-through; }
.discount-badge { background: var(--accent); color: #000; padding: 5px 10px; border-radius: 4px; font-weight: 700; font-size: 14px; }

.product-description { margin-bottom: 50px; }
.product-description h3 { font-size: 16px; font-weight: 700; color: var(--white); margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.05em; }
.product-description p { color: var(--white-dim); line-height: 1.8; font-size: 16px; }

.product-specs { display: flex; flex-direction: column; gap: 16px; margin-bottom: 50px; border-top: 1px solid var(--border); padding-top: 40px; }
.spec-item { display: flex; align-items: center; gap: 30px; }
.spec-label { color: var(--white-dim); font-size: 14px; width: 120px; }
.spec-value { color: var(--white); font-weight: 600; font-size: 14px; }
.spec-value.in-stock { color: #4ade80; }
.spec-value.out-of-stock { color: #f87171; }

.product-actions { display: flex; align-items: center; gap: 24px; margin-bottom: 50px; }
.qty-selector { display: flex; align-items: center; background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); padding: 6px; }
.qty-selector button { background: transparent; border: none; color: var(--white); width: 34px; height: 34px; cursor: pointer; font-size: 20px; transition: color 0.2s; }
.qty-selector button:hover { color: var(--accent); }
.qty-selector input { background: transparent; border: none; color: var(--white); width: 44px; text-align: center; outline: none; -moz-appearance: textfield; font-weight: 600; }
.qty-selector input::-webkit-outer-spin-button, .qty-selector input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

.btn-primary.add-to-cart { padding: 16px 48px; font-weight: 700; font-size: 14px; }
.btn-wishlist { background: var(--bg2); border: 1px solid var(--border); color: var(--white-dim); width: 54px; height: 54px; border-radius: var(--radius); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; }
.btn-wishlist:hover { border-color: var(--white); color: var(--white); }
.btn-wishlist.active { background: #ff4d4d20; border-color: #ff4d4d; color: #ff4d4d; }
.btn-wishlist.active i { fill: #ff4d4d; }

.trust-badges { display: flex; flex-direction: column; gap: 18px; border-top: 1px solid var(--border); padding-top: 40px; }
.trust-item { display: flex; align-items: center; gap: 14px; color: var(--white-dim); font-size: 14px; }
.trust-item i { width: 20px; height: 20px; color: var(--accent); }

.main-image-wrap { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); aspect-ratio: 1; display: flex; align-items: center; justify-content: center; overflow: hidden; margin-bottom: 20px; }
.main-image { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.main-image:hover { transform: scale(1.05); }
.image-placeholder { display: flex; align-items: center; justify-content: center; height: 100%; width: 100%; }

.image-thumbnails { display: flex; gap: 15px; }
.thumb { width: 80px; height: 80px; background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius); cursor: pointer; overflow: hidden; opacity: 0.6; transition: all 0.2s; }
.thumb img { width: 100%; height: 100%; object-fit: cover; }
.thumb:hover, .thumb.active { opacity: 1; border-color: var(--accent); }

.related-products { margin-top: 100px; padding-top: 60px; border-top: 1px solid var(--border); }
.related-products .section-title { margin-bottom: 40px; font-size: 28px; }

.wishlist-btn-small { background: transparent; border: none; color: var(--white-dim); cursor: pointer; padding: 5px; transition: all 0.2s; }
.wishlist-btn-small:hover { color: #ff4d4d; }
.wishlist-btn-small.active { color: #ff4d4d; }
.wishlist-btn-small.active i { fill: #ff4d4d; }

@media (max-width: 992px) {
    .product-main-grid { grid-template-columns: 1fr; gap: 40px; }
    .product-visual-column { order: -1; }
}
</style>
@endsection
