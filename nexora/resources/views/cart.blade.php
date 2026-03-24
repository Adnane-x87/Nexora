@extends('layouts.app')

@section('title', 'NEXORA — Shopping Cart')

@section('content')
<div class="cart-page">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-label">Your Selection</span>
                <h2 class="section-title">Shopping Cart</h2>
            </div>
            <a href="{{ route('shop') }}" class="btn-text"><i data-lucide="arrow-left" style="width:16px;"></i> Continue Shopping</a>
        </div>

        @if(count($products) > 0)
            <div class="cart-grid">
                <div class="cart-items-wrap">
                    @if ($products && count($products) > 0)
                        @for ($i = 0; $i < count($products); $i++)
                            @php $p = $products[$i]; @endphp
                            <div class="cart-item">
                                <div class="ci-img">
                                    @if($p->image)<img src="{{ \Illuminate\Support\Facades\Storage::url($p->image) }}" alt=""/>@else <div class="ci-emoji">{{ $p->category->emoji ?? '📦' }}</div> @endif
                                </div>
                                <div class="ci-info">
                                    <span class="ci-brand">{{ $p->brand }}</span>
                                    <h3 class="ci-name"><a href="{{ route('product.show', $p->slug) }}">{{ $p->name }}</a></h3>
                                    <p class="ci-stock @if($p->stock > 0) in @else out @endif">
                                        @if($p->stock > 0) In Stock @else Out of Stock @endif
                                    </p>
                                </div>
                                <div class="ci-qty">
                                    <form action="{{ route('cart.update', $p->id) }}" method="POST" class="qty-form">
                                        @csrf
                                        <button type="submit" name="quantity" value="{{ $p->quantity - 1 }}" class="qty-btn">-</button>
                                        <input type="text" value="{{ $p->quantity }}" readonly/>
                                        <button type="submit" name="quantity" value="{{ $p->quantity + 1 }}" class="qty-btn">+</button>
                                    </form>
                                </div>
                                <div class="ci-price">
                                    <span class="price-each">${{ number_format($p->price) }} each</span>
                                    <span class="price-subtotal">${{ number_format($p->price * $p->quantity) }}</span>
                                </div>
                                <form action="{{ route('cart.remove', $p->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="ci-remove"><i data-lucide="x"></i></button>
                                </form>
                            </div>
                        @endfor
                    @endif
                </div>

                <div class="cart-summary-sidebar">
                    <div class="summary-card">
                        <h3 class="summary-title">Order Summary</h3>
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span>${{ number_format($total) }}</span>
                        </div>
                        <div class="summary-row">
                            <span>Shipping</span>
                            <span class="free">FREE</span>
                        </div>
                        <div class="summary-divider"></div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span>${{ number_format($total) }}</span>
                        </div>
                        <a href="{{ route('checkout.index') }}" class="btn-primary checkout-btn">Proceed to Checkout</a>
                        <p class="summary-note">Secure payments by Stripe & SSL encrypted.</p>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart empty-state">
                <div class="e-icon"><i data-lucide="shopping-cart"></i></div>
                <h3 class="e-title">Your cart is empty</h3>
                <p>Looks like you haven't added anything to your selection yet.</p>
                <a href="{{ route('shop') }}" class="btn-primary">Browse Shop</a>
            </div>
        @endif
    </div>
</div>

<style>
.cart-page { padding: 140px 0 100px; }
.cart-grid { display: grid; grid-template-columns: 1fr 380px; gap: 40px; align-items: start; }

.cart-item { 
    display: grid; 
    grid-template-columns: 100px 1fr 140px 150px 40px; 
    gap: 30px; 
    align-items: center;
    background: var(--bg2); 
    border: 1px solid var(--border); 
    border-radius: var(--radius); 
    padding: 24px;
    margin-bottom: 20px;
}
.ci-img { width: 100px; height: 100px; background: var(--bg3); border-radius: 8px; overflow: hidden; display: flex; align-items: center; justify-content: center; }
.ci-img img { width: 100%; height: 100%; object-fit: cover; }
.ci-emoji { font-size: 40px; }

.ci-brand { color: var(--accent); font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; display: block; margin-bottom: 5px; }
.ci-name { font-family: var(--font-display); font-size: 18px; font-weight: 700; margin-bottom: 5px; }
.ci-name a { color: var(--white); text-decoration: none; }
.ci-stock { font-size: 13px; font-weight: 600; }
.ci-stock.in { color: #4ade80; }
.ci-stock.out { color: #f87171; }

.qty-form { display: flex; align-items: center; background: var(--bg3); border-radius: 6px; padding: 4px; border: 1px solid var(--border); }
.qty-form button { background: transparent; border: none; color: var(--white); width: 30px; height: 30px; cursor: pointer; transition: color 0.2s; font-size: 18px; }
.qty-form button:hover { color: var(--accent); }
.qty-form input { background: transparent; border: none; color: var(--white); width: 40px; text-align: center; outline: none; font-weight: 700; }

.ci-price { text-align: right; }
.price-each { display: block; font-size: 13px; color: var(--white-dim); margin-bottom: 5px; }
.price-subtotal { font-size: 18px; font-weight: 800; color: var(--white); }

.ci-remove { background: transparent; border: none; color: var(--white-faint); cursor: pointer; transition: color 0.2s; }
.ci-remove:hover { color: var(--red); }

.summary-card { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 30px; }
.summary-title { font-family: var(--font-display); font-size: 20px; font-weight: 800; margin-bottom: 25px; }
.summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; color: var(--white-dim); font-size: 15px; }
.summary-row.total { color: var(--white); font-weight: 800; font-size: 20px; margin-top: 15px; }
.summary-row .free { color: #4ade80; font-weight: 700; }
.summary-divider { height: 1px; background: var(--border); margin: 20px 0; }
.checkout-btn { width: 100%; padding: 16px; margin-top: 20px; }
.summary-note { font-size: 12px; color: var(--white); text-align: center; margin-top: 20px; }

@media (max-width: 1100px) {
    .cart-grid { grid-template-columns: 1fr; }
    .cart-summary-sidebar { order: -1; }
}
@media (max-width: 768px) {
    .cart-item { grid-template-columns: 80px 1fr 1fr; gap: 20px; }
    .ci-price { grid-column: span 3; text-align: left; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); padding-top: 15px; }
    .ci-remove { position: absolute; top: 10px; right: 10px; }
    .cart-item { position: relative; }
}
</style>
@endsection
