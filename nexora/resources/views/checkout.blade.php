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
                <span class="section-label">Secure Checkout</span>
                <h2 class="section-title">Finalize Order</h2>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" class="checkout-form-grid">
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
                            @for ($i = 0; $i < count($products); $i++)
                                @php $p = $products[$i]; @endphp
                                <div class="checkout-item">
                                    <span class="ci-badge">{{ $p->quantity }}</span>
                                    <div class="ci-details">
                                        <span class="ci-n">{{ $p->name }}</span>
                                        <span class="ci-p">${{ number_format($p->price) }}</span>
                                    </div>
                                </div>
                            @endfor
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
                        Pay with Card via Stripe <i data-lucide="lock" style="width:14px;margin-left:8px;"></i>
                    </button>
                    
                    <div class="secure-badges">
                        <svg fill="#fff" height="20" viewBox="0 0 60 19"><path d="M20.4 6.2c0-1-.6-1.5-1.8-1.5-1.1 0-1.7.5-1.7 1.2 0 .6.5 1 1.6 1.1l1 .1c1.3.1 2 .6 2 1.7 0 1.3-1.1 2-2.6 2-1.8 0-2.8-.8-2.8-1.9 0-.7.5-1.1 1.6-1.2l1.8-.2.2-1.9zm-5.3 4.1c0 .7-.5 1.1-1.3 1.1-.8 0-1.3-.4-1.3-1.1 0-.7.5-1.1 1.3-1.1.8 0 1.3.4 1.3 1.1zm-2.5-1.1c-.8 0-1.3.4-1.3 1.1s.5 1.1 1.3 1.1c.8 0 1.3-.4 1.3-1.1s-.5-1.1-1.3-1.1zm-10-4.1c-1.1 0-1.7.5-1.7 1.2 0 .6.5 1 1.6 1.1l1 .1c1.3.1 2 .6 2 1.7 0 1.3-1.1 2-2.6 2-1.8 0-2.8-.8-2.8-1.9 0-.7.5-1.1 1.6-1.2l1.8-.2.2-1.9h-2.1zM59.2 6c-.5-1-1.5-1.4-2.8-1.4-2.2 0-3.9 1.5-3.9 3.9s1.7 3.9 3.9 3.9c1.3 0 2.3-.4 2.8-1.4l-1.6-1c-.3.5-.8.8-1.2.8-.9 0-1.6-.6-1.6-1.7h3.4c.1-.3.1-.6.1-.8 0-2.2-1.6-3.7-3.8-3.7-2.2 0-3.8 1.5-3.8 3.7s1.6 3.7 3.8 3.7c2 0 3.4-1.3 3.8-3.1h-5.8c0 .9.7 1.5 1.6 1.5.5 0 1-.2 1.2-.7l1.6 1zM46.8 6.2c0-1-.6-1.5-1.8-1.5-1.1 0-1.7.5-1.7 1.2 0 .6.5 1 1.6 1.1l1 .1c1.3.1 2 .6 2 1.7 0 1.3-1.1 2-2.6 2-1.8 0-2.8-.8-2.8-1.9 0-.7.5-1.1 1.6-1.2l1.8-.2.2-1.9zM39.2 6c-.5-1-1.5-1.4-2.8-1.4-2.2 0-3.9 1.5-3.9 3.9s1.7 3.9 3.9 3.9c1.3 0 2.3-.4 2.8-1.4l-1.6-1c-.3.5-.8.8-1.2.8-.9 0-1.6-.6-1.6-1.7h3.4c.1-.3.1-.6.1-.8 0-2.2-1.6-3.7-3.8-3.7-2.2 0-3.8 1.5-3.8 3.7s1.6 3.7 3.8 3.7c2 0 3.4-1.3 3.8-3.1h-5.8c0 .9.7 1.5 1.6 1.5.5 0 1-.2 1.2-.7l1.6 1zM28.1 4.7h-2.1L24.5 10c-.1.4-.4.6-.8.6-.3 0-.5-.1-.6-.4l-1.7-5.5h-2.1l2.8 8.6h1.8l3.1-8.6z"/></svg>
                        <svg fill="#fff" height="20" viewBox="0 0 38 24"><path d="M35.8 21.8h-3.2c-.2 0-.4-.1-.5-.3l-4.2-11.2h-.1l-4.2 11.2c-.1.2-.3.3-.5.3h-3.2c-.2 0-.4-.1-.5-.3l-4.2-11.2h-.1l-4.2 11.2c-.1.2-.3.3-.5.3H2.2c-.2 0-.4-.1-.5-.3L0 10.6h3.2c.2 0 .4.1.5.3l2.1 5.6h.1l4.2-11.2c.1-.2.3-.3.5-.3h3.2c.2 0 .4.1.5.3l4.2 11.2h.1l4.2-11.2c.1-.2.3-.3.5-.3h3.2c.2 0 .4.1.5.3l2.1 5.6h.1l4.2-11.2c.1-.2.3-.3.5-.3h3.2c.2 0 .4.1.5.3l-1.7 11.2z"/></svg>
                        <svg fill="#fff" height="20" viewBox="0 0 38 24"><path d="M35.8 21.8h-3.2c-.2 0-.4-.1-.5-.3l-4.2-11.2h-.1l-4.2 11.2c-.1.2-.3.3-.5.3h-3.2c-.2 0-.4-.1-.5-.3l-4.2-11.2h-.1l-4.2 11.2c-.1.2-.3.3-.5.3H2.2c-.2 0-.4-.1-.5-.3L0 10.6h3.2c.2 0 .4.1.5.3l2.1 5.6h.1l4.2-11.2c.1-.2.3-.3.5-.3h3.2c.2 0 .4.1.5.3l4.2 11.2h.1l4.2-11.2c.1-.2.3-.3.5-.3h3.2c.2 0 .4.1.5.3l2.1 5.6h.1l4.2-11.2c.1-.2.3-.3.5-.3h3.2c.2 0 .4.1.5.3l-1.7 11.2z"/></svg>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
