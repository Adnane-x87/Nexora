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
      <div class="sidebar-block">
        <div class="sidebar-block-title">Categories</div>
        <div id="catFilterList"></div>
      </div>
      <div class="sidebar-block">
        <div class="sidebar-block-title">Badge</div>
        <div class="badge-filters">
          <label class="check-item"><input type="checkbox" value="new" class="badge-check"/> <span class="tag new" style="font-size:10px;">New</span></label>
          <label class="check-item"><input type="checkbox" value="sale" class="badge-check"/> <span class="tag sale" style="font-size:10px;">Sale</span></label>
          <label class="check-item"><input type="checkbox" value="hot" class="badge-check"/> <span class="tag hot" style="font-size:10px;">Hot</span></label>
        </div>
      </div>
      <div class="sidebar-block">
        <div class="sidebar-block-title">Sort By</div>
        <select class="sort-select" id="sortSelect">
          <option value="default">Default</option>
          <option value="price-asc">Price: Low to High</option>
          <option value="price-desc">Price: High to Low</option>
          <option value="name-asc">Name: A–Z</option>
        </select>
      </div>
    </aside>

    <!-- PRODUCTS AREA -->
    <div class="shop-main">
      <div class="shop-toolbar">
        <div class="active-filters" id="activeFilters"></div>
        <div class="results-count" id="resultsCount"></div>
      </div>
      <div class="products-grid" id="shopGrid"></div>
    </div>

  </div>
@endsection

@push('scripts')
    @vite(['resources/js/shop.js'])
@endpush
