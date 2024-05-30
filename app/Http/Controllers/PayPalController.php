<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal;
//use Srmklive\PayPal\Services\PayPal;

class PayPalController extends Controller
{
    protected $paypal;

    public function __construct(PayPal $paypal)
    {
        $this->paypal = $paypal;
    }

    public function payment()
    {
        $data = [
            'items' => [
                [
                    'name' => 'Product Name',
                    'price' => 10,
                    'qty' => 1,
                ]
            ],
            'invoice_id' => uniqid(),
            // Define $data['invoice_description'] here
            'return_url' => route('payment.success'),
            'cancel_url' => route('payment.success'),
            'total' => 10,
        ];

// Now you can reference $data['invoice_id'] within $data['invoice_description']
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $provider = PayPal::setProvider();
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);
//        PayPal::setProvider();
//        $paypalProvider = PayPal::getProvider();
//        $paypalProvider->setApiCredentials(config('paypal'));
//        $paypalProvider->setAccessToken($paypalProvider->getAccessToken());
//        $response = $this->paypal->setExpressCheckout($data);
//        return redirect($response['paypal_link']);
    }

    public function status(Request $request)
    {
        $token = $request->get('token');
        $payerId = $request->get('PayerID');

        $response = $this->paypal->getExpressCheckoutDetails($token);

        if ($response['ACK'] === 'Success') {
            // Payment was successful, process it
            // Implement your logic here
            return 'Payment Successful';
        } else {
            // Payment failed or was canceled
            // Implement your logic here
            return 'Payment Failed';
        }
    }
}
