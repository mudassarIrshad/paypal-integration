<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    protected $paypal;

    public function __construct(PayPalClient $paypal)
    {
        $this->paypal = $paypal;
    }

    public function pay()
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
            'invoice_description' => "Order #{$data['invoice_id']} Invoice",
            'return_url' => route('paypal.status'),
            'cancel_url' => route('paypal.status'),
            'total' => 10,
        ];

        $response = $this->paypal->setExpressCheckout($data);
        return redirect($response['paypal_link']);
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
