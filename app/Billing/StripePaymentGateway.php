<?php

namespace App\Billing;

use Stripe\Charge;

class StripePaymentGateway implements PaymentGateway
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function charge($amount, $token)
    {
        Charge::create([
            'amount' => $amount,
            'source' => $token,
            'currency' => 'usd',
        ], ['api_key' => $this->apiKey]);
    }
}

// class StripePaymentGateway implements PaymentGateway
// {
//     private $apiKey;

//     public function __construct($apiKey)
//     {
//         $this->apiKey = $apiKey;
//     }

//     public function charge($amount, $token)
//     {
//         (new \GuzzleHttp\Client)->post('https://api.stripe.com/v1/charges', [
//             'headers' => [
//                 'Authorization' => "Bearer {$this->apiKey}",
//             ],
//             'form_params' => [
//                 'amount' => $amount,
//                 'token' => $token,
//                 'currency' => 'usd',
//             ]
//         ]);
//     }
// }
