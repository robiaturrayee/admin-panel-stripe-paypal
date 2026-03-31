<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Product;
class StripeController extends Controller
{
    public function pay(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'inr',
                    'product_data' => [
                        'name' => $request->product_name,
                    ],
                    'unit_amount' => $request->amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/stripe/success'),
            'cancel_url' => url('/stripe/cancel'),
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        return "test Payment Successful ✅";
    }

    public function cancel()
    {
        return "Payment Cancelled ❌";
    }
}