<?php

use App\Billing\Alternate\StripePaymentGateway;

class MockStripePaymentGatewayTest extends TestCase
{
    /** @test */
    function charges_with_a_valid_payment_token_are_successful()
    {
        $stripeClient = Mockery::spy(\Stripe\ApiClient::class);
        $paymentGateway = new StripePaymentGateway($stripeClient);

        $paymentGateway->charge(2500, 'valid-token');

        $stripeClient->shouldHaveReceived('createCharge')->with([
            'amount' => 2500,
            'source' => 'valid-token',
            'currency' => 'usd',
        ])->once();
    }
}
