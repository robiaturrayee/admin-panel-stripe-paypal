<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | MAIN PAYMENT SWITCH
    |--------------------------------------------------------------------------
    */
    public function pay(Request $request)
    {
        switch ($request->gateway) {

            case 'stripe':
                return $this->stripe($request);

            case 'paypal':
                return $this->paypal($request);

            case 'razorpay':
                return $this->razorpay($request);

            case 'payu':
                return $this->payu($request);

            default:
                return back()->with('error', 'Invalid gateway');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STRIPE PAYMENT
    |--------------------------------------------------------------------------
    */
    public function stripe($request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => $request->name,
                    ],
                    'unit_amount' => $request->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/stripe/success?session_id={CHECKOUT_SESSION_ID}'),
            'cancel_url' => url('/stripe/cancel'),
        ]);

        return redirect($session->url);
    }

    public function stripeSuccess(Request $request)
    {
        return "Stripe Payment Successful ✅";
    }

    public function stripeCancel()
    {
        return "Stripe Payment Cancelled ❌";
    }

    /*
    |--------------------------------------------------------------------------
    | PAYPAL PAYMENT
    |--------------------------------------------------------------------------
    */
    public function paypal($request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => url('/paypal/success'),
                "cancel_url" => url('/paypal/cancel'),
            ],
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "USD",
                    "value" => round($request->amount / 80, 2)
                ]
            ]]
        ]);

        if (isset($response['id'])) {
            foreach ($response['links'] as $link) {
                if ($link['rel'] == 'approve') {
                    return redirect($link['href']);
                }
            }
        }

        return back()->with('error', 'PayPal error');
    }

    public function paypalSuccess()
    {
        return "PayPal Payment Successful ✅";
    }

    public function paypalCancel()
    {
        return "PayPal Payment Cancelled ❌";
    }

    /*
    |--------------------------------------------------------------------------
    | RAZORPAY PAYMENT
    |--------------------------------------------------------------------------
    */
    public function razorpay($request)
    {
        return view('payment.razorpay', [
            'amount' => $request->amount,
            'name' => $request->name
        ]);
    }

    public function razorpaySuccess(Request $request)
    {
        return "Razorpay Payment Successful ✅";
    }

    /*
    |--------------------------------------------------------------------------
    | PAYU PAYMENT
    |--------------------------------------------------------------------------
    */
    public function payu($request)
    {
        $key = env('PAYU_KEY');
        $salt = env('PAYU_SALT');

        $txnid = uniqid();
        $amount = $request->amount;
        $productinfo = $request->name;
        $firstname = "Test User";
        $email = "test@test.com";

        $hash = hash('sha512', $key.'|'.$txnid.'|'.$amount.'|'.$productinfo.'|'.$firstname.'|'.$email.'|||||||||||'.$salt);

        return view('payment.payu', compact(
            'key','txnid','amount','productinfo','firstname','email','hash'
        ));
    }

    public function payuSuccess(Request $request)
    {
        return "PayU Payment Successful ✅";
    }

    public function payuFailure()
    {
        return "PayU Payment Failed ❌";
    }
}