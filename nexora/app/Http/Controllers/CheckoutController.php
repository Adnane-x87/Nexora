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

        $total = 0;
        foreach ($cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $total += $product->price * $qty;
            }
        }

        $stripeSecret = config('services.stripe.secret');
        if (!$stripeSecret || $stripeSecret === 'sk_test_placeholder') {
            return redirect()->back()->with('error', 'Stripe API key is not configured. Please add your STRIPE_SECRET to the .env file.');
        }

        Stripe::setApiKey($stripeSecret);

        try {
            // Create PaymentIntent
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $total * 100, // Amount in cents
                'currency' => 'usd',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                // We add metadata to link the intent to our user
                'metadata' => [
                    'user_id' => auth()->id(),
                ],
            ]);

            // Create Pending Order
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

            // Create Order Items
            foreach ($cart as $id => $qty) {
                $product = Product::find($id);
                if ($product) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'price' => $product->price,
                    ]);
                }
            }

            // Save essential data to session for the next step
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
        // The actual order status update is handled by the webhook.
        // This is purely the success page UI.

        $paymentIntentId = $request->query('payment_intent');
        if (!$paymentIntentId) {
            return redirect()->route('home');
        }

        $order = Order::where('payment_intent_id', $paymentIntentId)->first();

        if (!$order) {
            return redirect()->route('home');
        }

        // Clear the cart now that checkout is mathematically successful from user's perspective
        session()->forget('cart');
        session()->forget(['checkout_client_secret', 'checkout_order_id']);

        return view('checkout-success', compact('order'));
    }

    public function cancel()
    {
        return redirect()->route('checkout.index')->with('error', 'Payment was cancelled. You can try again.');
    }
}
