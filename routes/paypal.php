<?php

use App\Http\Controllers\PayPalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Paypal Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'v1/'], function () {
    Route::get('handle-payment', [PayPalController::class, 'handlePayment'])->name('make.payment');
    Route::get('cancel-payment', [PayPalController::class, 'paymentCancel'])->name('cancel.payment');
    Route::get('payment-success', [PayPalController::class, 'paymentSuccess'])->name('success.payment');
});
