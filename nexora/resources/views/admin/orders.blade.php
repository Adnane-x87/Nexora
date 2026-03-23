@extends('layouts.app')

@section('title', 'Admin — Orders')

@section('content')
<div class="admin-page">
    <div class="container">
        <div class="section-header">
            <div>
                <span class="section-label">Management</span>
                <h2 class="section-title">Customer Orders</h2>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="btn-text">Back to Dashboard</a>
        </div>

        <div class="admin-card">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $o)
                    <tr>
                        <td>#{{ $o->id }}</td>
                        <td>
                            <div class="cust-info">
                                <strong>{{ $o->full_name }}</strong>
                                <span>{{ $o->email }}</span>
                            </div>
                        </td>
                        <td>${{ number_format($o->total_price) }}</td>
                        <td>
                            <span class="status-badge {{ $o->status }}">{{ strtoupper($o->status) }}</span>
                        </td>
                        <td>{{ $o->created_at->format('M d, Y') }}</td>
                        <td>
                            <form action="{{ route('admin.orders.status', $o->id) }}" method="POST" class="status-form">
                                @csrf
                                <select name="status" onchange="this.form.submit()">
                                    <option value="pending" {{ $o->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $o->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="shipped" {{ $o->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ $o->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $o->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="pagination-wrap">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.admin-page { padding: 140px 0 80px; }
.admin-card { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 30px; overflow-x: auto; }
.admin-table { width: 100%; border-collapse: collapse; }
.admin-table th { text-align: left; padding: 15px; border-bottom: 2px solid var(--border); color: var(--white-dim); font-size: 13px; text-transform: uppercase; letter-spacing: 0.1em; }
.admin-table td { padding: 20px 15px; border-bottom: 1px solid var(--border); vertical-align: middle; }

.cust-info strong { display: block; color: var(--white); }
.cust-info span { font-size: 12px; color: var(--white-faint); }

.status-badge { padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 800; }
.status-badge.pending { background: #fbbf2420; color: #fbbf24; }
.status-badge.paid { background: #4ade8020; color: #4ade80; }
.status-badge.shipped { background: #3b82f620; color: #3b82f6; }
.status-badge.delivered { background: #10b98120; color: #10b981; }
.status-badge.cancelled { background: #f8717120; color: #f87171; }

.status-form select { background: var(--bg3); border: 1px solid var(--border); color: var(--white); padding: 6px 10px; border-radius: 4px; outline: none; font-size: 13px; cursor: pointer; }
.status-form select:focus { border-color: var(--accent); }

.pagination-wrap { margin-top: 30px; display: flex; justify-content: center; }
</style>
@endsection
