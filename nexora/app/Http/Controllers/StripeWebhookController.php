<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('stripe-signature');
        $endpointSecret = config('services.stripe.webhook_secret'); // We'll need to add this to .env

        if (!$endpointSecret) {
            Log::error('Stripe webhook error: missing endpoint secret.');
            return response()->json(['error' => 'Webhook secret not configured'], 400);
        }

        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            Log::error('Stripe webhook error: Invalid payload.');
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            Log::error('Stripe webhook error: Invalid signature.');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object; 
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object; 
                $this->handlePaymentIntentFailed($paymentIntent);
                break;
            default:
                Log::info('Received unknown event type ' . $event->type);
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->status = 'paid';
            $order->save();
            Log::info("Order {$order->id} paid successfully via Webhook.");
        } else {
            Log::warning("Payment succeeded but order not found for Intent ID: {$paymentIntent->id}");
        }
    }

    private function handlePaymentIntentFailed($paymentIntent)
    {
        $order = Order::where('payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->status = 'cancelled'; // or 'failed' based on your ENUM
            $order->save();
            Log::info("Order {$order->id} payment failed via Webhook.");
        }
    }
}
