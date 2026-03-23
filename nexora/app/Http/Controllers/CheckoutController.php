<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $products = [];
        $total = 0;
        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $product->quantity = $qty;
                $products[] = $product;
                $total += $product->price * $qty;
            }
        }

        return view('checkout', compact('products', 'total'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $lineItems = [];
        $total = 0;
        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product->name,
                        ],
                        'unit_amount' => $product->price * 100,
                    ],
                    'quantity' => $qty,
                ];
                $total += $product->price * $qty;
            }
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
            'metadata' => [
                'user_id' => auth()->id(),
                'billing_info' => json_encode($request->only('full_name', 'email', 'phone', 'address', 'city', 'postal_code')),
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        if (!$sessionId) return redirect()->route('home');

        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve($sessionId);

        if (!$session) return redirect()->route('home');

        $billingInfo = json_decode($session->metadata->billing_info, true);
        
        // Create Order
        $order = Order::create([
            'user_id' => auth()->id(),
            'status' => 'paid',
            'total_price' => $session->amount_total / 100,
            'session_id' => $session->id,
            'full_name' => $billingInfo['full_name'],
            'email' => $billingInfo['email'],
            'phone' => $billingInfo['phone'],
            'address' => $billingInfo['address'],
            'city' => $billingInfo['city'],
            'postal_code' => $billingInfo['postal_code'],
        ]);

        // Create Order Items
        $cart = session()->get('cart', []);
        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                ]);
                $product->decrement('stock', $qty);
            }
        }

        session()->forget('cart');

        return view('checkout-success', compact('order'));
    }

    public function cancel()
    {
        return redirect()->route('checkout.index')->with('error', 'Payment was cancelled.');
    }
}
