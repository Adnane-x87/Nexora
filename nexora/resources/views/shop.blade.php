@extends('layouts.app')

@section('title', 'NEXORA — Shop')

@push('styles')
    @vite(['resources/css/shop.css'])
@endpush

@section('content')
  <!-- PAGE HEADER -->
  <div class="shop-header">
    <div class="shop-header-content">
      <div class="section-label">Store</div>
      <h1 class="shop-title">All Products</h1>
    </div>
  </div>

  <!-- SHOP LAYOUT -->
  <div class="shop-layout">

    <!-- SIDEBAR FILTERS -->
    <aside class="shop-sidebar">
      <form action="{{ route('shop') }}" method="GET" id="filterForm">
      <div class="sidebar-block">
        <div class="sidebar-block-title">Categories</div>
        <div id="catFilterList" style="display:flex;flex-direction:column;gap:10px;">
            <label class="check-item" style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="radio" name="category" value="" {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()"/> <span style="font-size:14px;color:var(--white);">All Products</span></label>
            @foreach($categories as $c)
            <label class="check-item" style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="radio" name="category" value="{{ $c->slug }}" {{ request('category') == $c->slug ? 'checked' : '' }} onchange="this.form.submit()"/> <span style="font-size:14px;color:var(--white);">{{ $c->emoji }} {{ $c->name }} ({{ $c->products_count }})</span></label>
            @endforeach
        </div>
      </div>
      <div class="sidebar-block">
        <div class="sidebar-block-title">Badge</div>
        <div class="badge-filters">
          <label class="check-item"><input type="radio" name="badge" value="" {{ !request('badge') ? 'checked' : '' }} onchange="this.form.submit()"/> <span class="tag" style="font-size:10px;">All</span></label>
          <label class="check-item"><input type="radio" name="badge" value="new" {{ request('badge') == 'new' ? 'checked' : '' }} onchange="this.form.submit()"/> <span class="tag new" style="font-size:10px;">New</span></label>
          <label class="check-item"><input type="radio" name="badge" value="sale" {{ request('badge') == 'sale' ? 'checked' : '' }} onchange="this.form.submit()"/> <span class="tag sale" style="font-size:10px;">Sale</span></label>
          <label class="check-item"><input type="radio" name="badge" value="hot" {{ request('badge') == 'hot' ? 'checked' : '' }} onchange="this.form.submit()"/> <span class="tag hot" style="font-size:10px;">Hot</span></label>
        </div>
      </div>
      <div class="sidebar-block">
        <div class="sidebar-block-title">Sort By</div>
        <select class="sort-select" name="sort" id="sortSelect" onchange="this.form.submit()">
          <option value="default" {{ request('sort') == 'default' ? 'selected' : '' }}>Default</option>
          <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High</option>
          <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low</option>
          <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Name: A–Z</option>
        </select>
        @if(request('search'))
        <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
      </div>
      </form>
    </aside>

    <!-- PRODUCTS AREA -->
    <div class="shop-main">
      <div class="shop-toolbar">
        <div class="active-filters" id="activeFilters" style="display:flex;gap:10px;">
            @if(request('search'))<span class="filter-tag" style="padding:4px 8px;border-radius:4px;background:var(--accent);color:#000;font-size:12px;">Search: {{ request('search') }}</span>@endif
            @if(request('category'))<span class="filter-tag" style="padding:4px 8px;border-radius:4px;background:var(--border);color:var(--white);font-size:12px;">Category: {{ request('category') }}</span>@endif
            @if(request('badge'))<span class="filter-tag" style="padding:4px 8px;border-radius:4px;background:var(--border);color:var(--white);font-size:12px;">Badge: {{ request('badge') }}</span>@endif
            @if(request('category') || request('badge') || request('search'))
                <a href="{{ route('shop') }}" style="color:var(--white-dim);font-size:12px;text-decoration:none;">Clear Filters</a>
            @endif
        </div>
        <div class="results-count" id="resultsCount">{{ $products->count() }} product{{ $products->count() !== 1 ? 's' : '' }}</div>
      </div>
      <div class="products-grid" id="shopGrid">
          @forelse($products as $p)
            <div class="product-card">
              @if($p->badge)<span class="tag {{ $p->badge }}">{{ ucfirst($p->badge) }}</span>@endif
              <div class="wishlist-btn"><i data-lucide="heart" style="width:16px;height:16px;"></i></div>
              <div class="product-img-wrap">
                @if($p->image)<img src="{{ $p->image }}" alt="" style="width:100%;height:100%;object-fit:cover;"/>@else <div style="font-size:48px;display:flex;align-items:center;justify-content:center;height:100%;">{{ $p->category->emoji ?? '📦' }}</div> @endif
              </div>
              <div class="product-info">
                <div class="product-brand">{{ $p->brand ?? '' }}</div>
                <div class="product-name">{{ $p->name }}</div>
                <div class="product-rating"><span class="s">★★★★★</span> 4.9</div>
                <div class="product-footer">
                  <div class="price-wrap">
                    <span class="price-new">${{ number_format($p->price) }}</span>
                    @if($p->old_price)<span class="price-old">${{ number_format($p->old_price) }}</span>@endif
                  </div>
                  <button class="add-cart-btn"><i data-lucide="plus" style="width:16px;height:16px;"></i></button>
                </div>
              </div>
            </div>
          @empty
            <div class="shop-empty" style="grid-column: 1 / -1;text-align:center;padding:40px;"><div class="e-icon" style="color:var(--white-dim);margin-bottom:10px;"><i data-lucide="search-x" style="width:48px;height:48px;"></i></div><div class="e-title" style="font-size:18px;font-weight:600;color:var(--white);">No products found</div><p style="color:var(--white-dim);font-size:14px;margin-top:5px;">Try a different filter or search term.</p></div>
          @endforelse
      </div>
    </div>

  </div>
@endsection

@push('scripts')
    @vite(['resources/js/shop.js'])
@endpush
