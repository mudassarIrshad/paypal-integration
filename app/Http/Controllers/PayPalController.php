<?php

namespace App\Http\Controllers;

use App\Http\Requests\HandlePaymentRequest;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\ExpressCheckout;

class PayPalController extends Controller
{
    public function handlePayment(HandlePaymentRequest $request)
    {
        $product = [];

        $product['items'] = [
            [
                'name' => $request->products['name'],
                'price' => $request->products['price'],
                'desc' => $request->products['desc'],
                'qty' => $request->products['qty']
            ]
        ];

        $product['invoice_id'] = $request->invoice_id;

        $product['invoice_description'] = $request->invoice_description;

        $product['return_url'] = route('success.payment');

        $product['cancel_url'] = route('cancel.payment');

        $product['total'] = $request->total;

        $paypalModule = new ExpressCheckout;

        $res = $paypalModule->setExpressCheckout($product);
        $res = $paypalModule->setExpressCheckout($product, true);

        return $this->paymentSuccess($request);
        // test data
    }


    public function paymentCancel()
    {
        return response()->json('Your payment has been declined', '');
    }


    public function paymentSuccess(Request $request)
    {
        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);
        if (isset($response['ACK'])) {
            if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

                return response()->json('Payment successful');

            }
        }


        return response()->json('Error occured!');
    }
}
