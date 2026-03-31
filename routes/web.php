<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StripeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:admin'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);

    Route::resource('products', ProductController::class);
});

// Route::get('/shop', [ShopController::class, 'index']);
Route::get('/shop/product/{id}', [ShopController::class, 'view']);
// Route::post('/pay', [PaymentController::class, 'pay']);
Route::post('/stripe/pay', [StripeController::class, 'pay']);
Route::get('/stripe/success', [StripeController::class, 'success']);
Route::get('/stripe/cancel', [StripeController::class, 'cancel']);


// Product listing
Route::get('/shop', [ShopController::class, 'index']);

// Product detail
Route::get('/product/{id}', [ShopController::class, 'view']);


/*
|--------------------------------------------------------------------------
| PAYMENT MAIN ROUTE
|--------------------------------------------------------------------------
*/

// One route handles all gateways
Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');


/*
|--------------------------------------------------------------------------
| STRIPE ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/stripe/success', [PaymentController::class, 'stripeSuccess'])->name('stripe.success');
Route::get('/stripe/cancel', [PaymentController::class, 'stripeCancel'])->name('stripe.cancel');


/*
|--------------------------------------------------------------------------
| PAYPAL ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/paypal/success', [PaymentController::class, 'paypalSuccess'])->name('paypal.success');
Route::get('/paypal/cancel', [PaymentController::class, 'paypalCancel'])->name('paypal.cancel');


/*
|--------------------------------------------------------------------------
| RAZORPAY ROUTES
|--------------------------------------------------------------------------
*/

// Razorpay success (after JS handler)
Route::post('/razorpay/success', [PaymentController::class, 'razorpaySuccess'])->name('razorpay.success');


/*
|--------------------------------------------------------------------------
| PAYU ROUTES
|--------------------------------------------------------------------------
*/

// PayU success & failure
Route::post('/payu/success', [PaymentController::class, 'payuSuccess'])->name('payu.success');
Route::post('/payu/failure', [PaymentController::class, 'payuFailure'])->name('payu.failure');


/*
|--------------------------------------------------------------------------
| COMMON (OPTIONAL)
|--------------------------------------------------------------------------
*/

Route::get('/success', function () {
    return "Payment Success ✅";
});

Route::get('/cancel', function () {
    return "Payment Cancelled ❌";
});

require __DIR__.'/auth.php';
