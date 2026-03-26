@extends('layouts.app')

@section('title', 'NEXORA — Final Checkout')

@push('styles')
    @vite(['resources/css/checkout.css'])
@endpush

@section('content')
<div class="checkout-page">
    <div class="container">
        <div class="section-header">
            <div>
        <div class="checkout-alerts">
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        </div>

        <form action="{{ route('checkout.process') }}" method="POST" class="checkout-form-grid">
            @csrf
            <div class="checkout-billing-info">
                <div class="c-card">
                    <h3 class="c-card-title"><i data-lucide="user" style="margin-right:10px;"></i> Billing Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="full_name" value="{{ auth()->user()->name }}" required placeholder="e.g. John Doe"/>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" name="email" value="{{ auth()->user()->email }}" required placeholder="e.g. john@example.com"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" name="phone" required placeholder="e.g. +1 234 567 890"/>
                    </div>
                    
                    <h3 class="c-card-title" style="margin-top:40px;"><i data-lucide="map-pin" style="margin-right:10px;"></i> Shipping Address</h3>
                    <div class="form-group">
                        <label>Street Address</label>
                        <input type="text" name="address" required placeholder="e.g. 123 Neon Street"/>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" required placeholder="e.g. Metropolis"/>
                        </div>
                        <div class="form-group">
                            <label>Postal Code</label>
                            <input type="text" name="postal_code" required placeholder="e.g. 10001"/>
                        </div>
                    </div>
                </div>
            </div>

            <div class="checkout-order-summary">
                <div class="summary-card sticky-card">
                    <h3 class="summary-title">Order Summary</h3>
                    <div class="checkout-items">
                        @if ($products && count($products) > 0)
                            @foreach ($products as $p)
                                <div class="checkout-item">
                                    <span class="ci-badge">{{ $p->quantity }}</span>
                                    <div class="ci-details">
                                        <span class="ci-n">{{ $p->name }}</span>
                                        <span class="ci-p">${{ number_format($p->price) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="summary-divider"></div>
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
                        <span>Total Due</span>
                        <span>${{ number_format($total) }}</span>
                    </div>
                    
                    <button type="submit" class="btn-primary checkout-btn">
                        <span>Proceed to Payment</span>
                        <i data-lucide="arrow-right" style="width:16px;height:16px;"></i>
                    </button>
                    
                    <div class="secure-badges">
                        <div class="badge-item"><i data-lucide="shield-check"></i> <span>SSL SECURE</span></div>
                        <div class="badge-item"><i data-lucide="credit-card"></i> <span>STRIPE</span></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
