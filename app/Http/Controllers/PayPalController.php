<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalController extends Controller
{
    public $provider;

    public function __construct()
    {
        $this->provider = new ExpressCheckout;
    }

    public function payment()
    {
        $data = [];
        $data['items'] = [
            [
                'name' => 'test',
                'price' => 100,
                'desc'  => 'test description',
                'qty' => 1
            ]
        ];

        $data['invoice_id'] = 1;
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('payment.success');
        $data['cancel_url'] = route('payment.cancel');
        $data['total'] = 100;

        $response =  $this->provider->setExpressCheckout($data);

        $response =  $this->provider->setExpressCheckout($data, true);

        return redirect($response['paypal_link']);
    }

    public function status(Request $request)
    {
        $response =  $this->provider->getExpressCheckoutDetails($request->token);

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            dd('Your payment was successfully. You can create success page here.');
        }

        dd('Something is wrong.');
    }
}
