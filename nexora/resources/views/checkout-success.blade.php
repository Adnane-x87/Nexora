@extends('layouts.app')

@section('title', 'NEXORA — Payment Successful')

@section('content')
<div class="success-page">
    <div class="container">
        <div class="success-card fade-up visible">
            <div class="s-icon"><i data-lucide="check-circle" style="width:80px;height:80px;"></i></div>
            <h1 class="s-title">Payment Successful!</h1>
            <p class="s-text">Thank you for your purchase. Your order <strong>#{{ $order->id }}</strong> has been confirmed and is being processed.</p>
            
            <div class="order-summary-box">
                <div class="o-row">
                    <span>Amount Paid</span>
                    <span class="o-val">${{ number_format($order->total_price) }}</span>
                </div>
                <div class="o-row">
                    <span>Order Date</span>
                    <span class="o-val">{{ $order->created_at->format('M d, Y') }}</span>
                </div>
                <div class="o-row">
                    <span>Status</span>
                    <span class="o-badge">{{ strtoupper($order->status) }}</span>
                </div>
            </div>

            <div class="s-actions">
                <a href="{{ route('profile') }}" class="btn-primary">View My Orders</a>
                <a href="{{ route('shop') }}" class="btn-text">Return to Shop</a>
            </div>
        </div>
    </div>
</div>

<style>
.success-page { padding: 180px 0 100px; }
.success-card { 
    max-width: 600px; 
    margin: 0 auto; 
    background: var(--bg2); 
    border: 1px solid var(--border); 
    border-radius: var(--radius-lg); 
    padding: 60px; 
    text-align: center;
    box-shadow: 0 20px 50px rgba(0,0,0,0.5);
}
.s-icon { color: #4ade80; margin-bottom: 30px; }
.s-title { font-family: var(--font-display); font-size: 36px; font-weight: 800; margin-bottom: 15px; }
.s-text { color: var(--white-dim); font-size: 16px; margin-bottom: 40px; }

.order-summary-box { background: var(--bg3); border-radius: var(--radius); padding: 25px; margin-bottom: 40px; }
.o-row { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px; }
.o-row:last-child { margin-bottom: 0; }
.o-val { color: var(--white); font-weight: 700; }
.o-badge { background: #4ade8020; color: #4ade80; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 800; }

.s-actions { display: flex; flex-direction: column; gap: 15px; }
.s-actions .btn-primary { padding: 16px; }
</style>
@endsection
