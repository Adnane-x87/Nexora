@extends('layouts.app')

@section('title', 'NEXORA — Payment')

@push('styles')
<style>
    .payment-page { padding: 140px 0 100px; }
    .payment-container { max-width: 600px; margin: 0 auto; }
    .payment-card { background: var(--bg2); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); }
    .payment-header { text-align: center; margin-bottom: 30px; }
    .payment-title { font-family: var(--font-display); font-size: 24px; font-weight: 700; color: var(--white); margin-bottom: 10px; }
    .payment-amount { font-size: 32px; font-weight: 800; color: var(--accent); }
    #payment-form { margin-top: 30px; }
    #submit { width: 100%; margin-top: 24px; justify-content: center; padding: 16px; font-size: 16px; border-radius: var(--radius); }
    #payment-message { display: none; margin-top: 20px; padding: 15px; border-radius: var(--radius); text-align: center; }
    #payment-message.success { background: rgba(74, 222, 128, 0.1); color: #4ade80; border: 1px solid rgba(74, 222, 128, 0.2); }
    #payment-message.error { background: rgba(255, 71, 87, 0.1); color: var(--red); border: 1px solid rgba(255, 71, 87, 0.2); }
    .spinner { display: none; animation: spin 1s linear infinite; }
    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
</style>
@endpush

@section('content')
<div class="payment-page">
    <div class="container payment-container">
        <div class="payment-card">
            <div class="payment-header">
                <h2 class="payment-title">Complete Payment</h2>
                <div class="payment-amount">${{ number_format($order->total_price, 2) }}</div>
                <p style="color: var(--white-faint); font-size: 14px; margin-top: 10px;">Order #{{ $order->id }}</p>
            </div>

            <form id="payment-form">
                <div id="payment-element">
                    <!-- Stripe Elements will inject the UI here -->
                </div>
                <button id="submit" class="btn-primary">
                    <span id="button-text">Pay now</span>
                    <i data-lucide="loader-2" class="spinner" id="spinner"></i>
                </button>
                <div id="payment-message" class="hidden"></div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    const stripePublicKey = "{{ config('services.stripe.key') }}";
    const clientSecret = "{{ $clientSecret }}";
    
    if (!stripePublicKey || stripePublicKey.includes('placeholder')) {
        document.getElementById('payment-message').classList.remove('hidden');
        document.getElementById('payment-message').classList.add('error');
        document.getElementById('payment-message').innerText = 'Stripe Public Key is missing or invalid! Please update STRIPE_KEY in your .env file.';
        document.getElementById('payment-message').style.display = 'block';
    } else if (!clientSecret) {
        document.getElementById('payment-message').classList.remove('hidden');
        document.getElementById('payment-message').classList.add('error');
        document.getElementById('payment-message').innerText = 'Payment session failed to initialize. Please try checking out again.';
        document.getElementById('payment-message').style.display = 'block';
    } else {
        const stripe = Stripe(stripePublicKey);

        // The exact parameters and appearance can be configured
        const options = {
            clientSecret: clientSecret,
            appearance: {
                theme: 'night',
                labels: 'floating',
                variables: {
                    colorPrimary: '#6366f1', // Indigo to match brand
                    colorBackground: '#0a0a0a',
                    colorText: '#ffffff',
                    colorDanger: '#ff4757',
                    fontFamily: 'Inter, system-ui, sans-serif',
                    spacingUnit: '4px',
                    borderRadius: '12px',
                }
            },
        };

        const elements = stripe.elements(options);
        const paymentElement = elements.create('payment');
        paymentElement.mount('#payment-element');

        const form = document.getElementById('payment-form');
        const submitBtn = document.getElementById('submit');
        const spinner = document.getElementById('spinner');
        const buttonText = document.getElementById('button-text');
        const messageContainer = document.getElementById('payment-message');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            // UI state: loading
            submitBtn.disabled = true;
            spinner.style.display = 'inline-block';
            buttonText.style.display = 'none';
            messageContainer.style.display = 'none';

            try {
                // Trigger form validation and wallet collection
                const {error: submitError} = await elements.submit();
                if (submitError) {
                    throw submitError;
                }

                const { error } = await stripe.confirmPayment({
                    elements,
                    confirmParams: {
                        return_url: '{{ route('checkout.success') }}',
                    },
                });

                // If an error is returned during confirmation
                if (error) {
                    throw error;
                }
            } catch (err) {
                messageContainer.classList.remove('success');
                messageContainer.classList.add('error');
                messageContainer.innerText = err.message || 'An unexpected error occurred.';
                messageContainer.style.display = 'block';
                
                // Reset UI
                submitBtn.disabled = false;
                spinner.style.display = 'none';
                buttonText.style.display = 'inline-block';
            }
        });
    }
</script>
@endpush
