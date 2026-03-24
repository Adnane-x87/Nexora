@extends('layouts.app')

@section('title', 'NEXORA — My Profile')

@section('content')
<div class="profile-page">
    <div class="container">
        <div class="profile-header fade-up visible">
            <div class="profile-info-grid">
                <div class="profile-avatar-large">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="profile-text">
                    <h1 class="profile-name">{{ $user->name }}</h1>
                    <p class="profile-email">{{ $user->email }}</p>
                    <div class="profile-badges">
                        <span class="p-badge">{{ $user->role }}</span>
                        <span class="p-badge">Member since {{ $user->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <section class="wishlist-section">
            <div class="section-header">
                <div>
                    <span class="section-label">Saved Items</span>
                    <h2 class="section-title">My Wishlist</h2>
                </div>
                <span class="results-count">{{ $wishlist->count() }} Items</span>
            </div>

            @if($wishlist->count() > 0)
                <div class="products-grid">
                @if ($wishlist && count($wishlist) > 0)
                    @for ($i = 0; $i < count($wishlist); $i++)
                        @php $p = $wishlist[$i]; @endphp
                        <div class="product-card">
                            @if($p->badge)<span class="tag {{ $p->badge }}">{{ ucfirst($p->badge) }}</span>@endif
                            
                            <form action="{{ route('wishlist.toggle', $p->id) }}" method="POST" class="wishlist-form">
                                @csrf
                                <button type="submit" class="wishlist-btn active">
                                    <i data-lucide="heart" style="width:16px;height:16px;"></i>
                                </button>
                            </form>

                            <a href="{{ route('product.show', $p->slug) }}" class="product-img-wrap" style="text-decoration:none;">
                                @if($p->image)<img src="{{ \Illuminate\Support\Facades\Storage::url($p->image) }}" alt="" style="width:100%;height:100%;object-fit:cover;"/>@else <div style="font-size:48px;display:flex;align-items:center;justify-content:center;height:100%;">{{ $p->category->emoji ?? '📦' }}</div> @endif
                            </a>
                            <div class="product-info">
                                <div class="product-brand">{{ $p->brand ?? '' }}</div>
                                <a href="{{ route('product.show', $p->slug) }}" class="product-name" style="text-decoration:none;color:var(--white);">{{ $p->name }}</a>
                                <div class="product-rating"><span class="s">★★★★★</span> 4.9</div>
                                <div class="product-footer">
                                    <div class="price-wrap">
                                        <span class="price-new">${{ number_format($p->price) }}</span>
                                        @if($p->old_price)
                                            <span class="price-old">${{ number_format($p->old_price) }}</span>
                                        @endif
                                    </div>
                                    <button class="add-cart-btn"><i data-lucide="plus"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-wishlist">
                    <div class="e-icon"><i data-lucide="heart-off"></i></div>
                    <h3 class="e-title">Your wishlist is empty</h3>
                    <p>Explore our products and save your favorites here.</p>
                    <a href="{{ route('shop') }}" class="btn-primary" style="margin-top:20px;display:inline-block;">Start Shopping</a>
                </div>
            @endif
        </section>

        <section class="orders-section" style="margin-top: 100px;">
            <div class="section-header">
                <div>
                    <span class="section-label">Purchase History</span>
                    <h2 class="section-title">My Orders</h2>
                </div>
                <span class="results-count">{{ $orders->count() }} Orders</span>
            </div>

            @if($orders->count() > 0)
                <div class="orders-list" style="display: flex; flex-direction: column; gap: 20px;">
                    @if ($orders && count($orders) > 0)
                        @for ($i = 0; $i < count($orders); $i++)
                            @php $o = $orders[$i]; @endphp
                            <div class="order-card" style="background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 30px;">
                                <div class="order-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid var(--border);">
                                    <div>
                                        <div style="font-size: 14px; color: var(--white-dim); margin-bottom: 4px;">Order ID</div>
                                        <div style="font-weight: 700; color: var(--accent);">#{{ $o->id }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; color: var(--white-dim); margin-bottom: 4px;">Date</div>
                                        <div style="color: var(--white);">{{ $o->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; color: var(--white-dim); margin-bottom: 4px;">Total</div>
                                        <div style="font-weight: 700; color: var(--white);">${{ number_format($o->total_price) }}</div>
                                    </div>
                                    <div>
                                        <div style="font-size: 14px; color: var(--white-dim); margin-bottom: 4px;">Status</div>
                                        <span class="status-badge {{ $o->status }}" style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase;">{{ $o->status }}</span>
                                    </div>
                                </div>
                                <div class="order-items" style="display: grid; gap: 15px;">
                                    @if ($o->items && count($o->items) > 0)
                                        @for ($j = 0; $j < count($o->items); $j++)
                                            @php $item = $o->items[$j]; @endphp
                                            <div class="order-item" style="display: flex; align-items: center; gap: 15px;">
                                                <div style="width: 50px; height: 50px; background: var(--bg3); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid var(--border);">
                                                    @if($item->product->image)
                                                        <img src="{{ \Illuminate\Support\Facades\Storage::url($item->product->image) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                                    @else
                                                        <span>📦</span>
                                                    @endif
                                                </div>
                                                <div style="flex: 1;">
                                                    <div style="color: var(--white); font-weight: 500; font-size: 14px;">{{ $item->product->name }}</div>
                                                    <div style="color: var(--white-dim); font-size: 12px;">Qty: {{ $item->quantity }} × ${{ number_format($item->price) }}</div>
                                                </div>
                                            </div>
                                        @endfor
                                    @endif
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
            @else
                <div class="empty-orders" style="text-align: center; padding: 60px 20px; background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); border-style: dashed;">
                    <div class="e-icon" style="font-size: 48px; color: var(--white-faint); margin-bottom: 15px;"><i data-lucide="shopping-bag" style="width:48px;height:48px;"></i></div>
                    <h3 class="e-title" style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">No orders yet</h3>
                    <p style="color: var(--white-dim);">You haven't placed any orders yet. Once you do, they'll appear here.</p>
                </div>
            @endif
        </section>

        <style>
        .status-badge.pending { background: #fbbf2420; color: #fbbf24; }
        .status-badge.paid { background: #4ade8020; color: #4ade80; }
        .status-badge.shipped { background: #3b82f620; color: #3b82f6; }
        .status-badge.delivered { background: #10b98120; color: #10b981; }
        .status-badge.cancelled { background: #f8717120; color: #f87171; }
        </style>
    </div>
</div>

<style>
.profile-page { padding: 140px 0 100px; }
.profile-header { 
    background: var(--bg2); 
    border: 1px solid var(--border); 
    border-radius: var(--radius-lg); 
    padding: 60px; 
    margin-bottom: 80px;
    position: relative;
    overflow: hidden;
}
.profile-header::before {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 300px; height: 300px;
    background: radial-gradient(circle, var(--accent-glow) 0%, transparent 70%);
    opacity: 0.5;
    pointer-events: none;
}

.profile-info-grid { display: flex; align-items: center; gap: 40px; position: relative; z-index: 1; }
.profile-avatar-large {
    width: 120px; height: 120px;
    background: var(--accent);
    color: #000;
    font-family: var(--font-display);
    font-size: 48px;
    font-weight: 800;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 30px var(--accent-glow);
}

.profile-name { font-family: var(--font-display); font-size: 36px; font-weight: 800; margin-bottom: 8px; }
.profile-email { color: var(--white-dim); font-size: 16px; margin-bottom: 20px; }
.profile-badges { display: flex; gap: 12px; }
.p-badge { 
    background: var(--white-faint); 
    border: 1px solid var(--border); 
    padding: 6px 14px; 
    border-radius: 20px; 
    font-size: 12px; 
    font-weight: 500; 
    color: var(--white-dim);
}

.wishlist-section { position: relative; }
.results-count { font-size: 14px; color: var(--white-dim); font-weight: 500; }

.empty-wishlist { 
    text-align: center; 
    padding: 100px 20px; 
    background: var(--bg2); 
    border: 1px solid var(--border); 
    border-radius: var(--radius-lg);
    border-style: dashed;
}
.empty-wishlist .e-icon { font-size: 64px; color: var(--white-faint); margin-bottom: 24px; }
.empty-wishlist .e-icon i { width: 64px; height: 64px; }
.empty-wishlist .e-title { font-family: var(--font-display); font-size: 24px; font-weight: 700; margin-bottom: 12px; }
.empty-wishlist p { color: var(--white-dim); max-width: 400px; margin: 0 auto; }

@media (max-width: 768px) {
    .profile-info-grid { flex-direction: column; text-align: center; gap: 24px; }
    .profile-header { padding: 40px 20px; }
}
</style>
@endsection
