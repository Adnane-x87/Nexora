<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');
        $total = 0;

        foreach ($cart as $id => $qty) {
            $product = $products->get($id);
            if ($product) {
                $product->quantity = $qty;
                $total += (int) round($product->price * 100) * $qty;
            }
        }

        $displayTotal = $total / 100; // cents → dollars for view
        return view('checkout', compact('products', 'displayTotal'))->with('total', $displayTotal);
    }

    public function process(Request $request)
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

        // Bulk-load products (avoid N+1)
        $productIds = array_keys($cart);
        $cartProducts = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $totalCents = 0;
        foreach ($cart as $id => $qty) {
            $product = $cartProducts->get($id);
            if ($product) {
                $totalCents += (int) round($product->price * 100) * $qty;
            }
        }
        $total = $totalCents / 100; // dollars for display/storage

        $stripeSecret = config('services.stripe.secret');
        if (!$stripeSecret || $stripeSecret === 'sk_test_placeholder') {
            return redirect()->back()->with('error', 'Stripe API key is not configured. Please add your STRIPE_SECRET to the .env file.');
        }

        Stripe::setApiKey($stripeSecret);

        try {
            // Create PaymentIntent with amount in cents (integer)
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $totalCents, // integer cents — no float math
                'currency' => 'usd',
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => ['user_id' => auth()->id()],
            ]);

            // Wrap order + items in a transaction to prevent orphaned records
            $order = DB::transaction(function () use ($cart, $cartProducts, $total, $paymentIntent, $request) {
                $order = Order::create([
                    'user_id' => auth()->id(),
                    'status' => 'pending',
                    'total_price' => $total,
                    'payment_intent_id' => $paymentIntent->id,
                    'full_name' => $request->full_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'city' => $request->city,
                    'postal_code' => $request->postal_code,
                ]);

                foreach ($cart as $id => $qty) {
                    $product = $cartProducts->get($id);
                    if ($product) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $product->id,
                            'quantity' => $qty,
                            'price' => $product->price,
                        ]);
                    }
                }

                return $order;
            });

            session()->put('checkout_client_secret', $paymentIntent->client_secret);
            session()->put('checkout_order_id', $order->id);

            return redirect()->route('checkout.payment');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error initializing payment: ' . $e->getMessage());
        }
    }

    public function payment()
    {
        $clientSecret = session()->get('checkout_client_secret');
        $orderId = session()->get('checkout_order_id');

        if (!$clientSecret || !$orderId) {
            return redirect()->route('checkout.index')->with('error', 'Please submit your billing details first.');
        }

        $order = Order::findOrFail($orderId);

        return view('checkout-payment', compact('clientSecret', 'order'));
    }

    public function success(Request $request)
    {
        $paymentIntentId = $request->query('payment_intent');
        if (!$paymentIntentId) {
            return redirect()->route('home');
        }

        $order = Order::where('payment_intent_id', $paymentIntentId)->first();

        if (!$order) {
            return redirect()->route('home');
        }

        // Only clear cart if payment was actually confirmed (webhook may already have fired)
        if ($order->status === 'paid') {
            session()->forget('cart');
            session()->forget(['checkout_client_secret', 'checkout_order_id']);
            return view('checkout-success', compact('order'));
        }

        // Payment not yet confirmed — show a pending page
        return view('checkout-success', compact('order'));
    }

    public function cancel()
    {
        return redirect()->route('checkout.index')->with('error', 'Payment was cancelled. You can try again.');
    }
}
