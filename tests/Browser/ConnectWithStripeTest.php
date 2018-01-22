<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ConnectWithStripeTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function connecting_a_stripe_account_successfully()
    {
        $user = factory(User::class)->create([
            'stripe_account_id' => null,
            'stripe_access_token' => null,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/backstage/stripe-connect/connect')
                    ->clickLink('Connect with Stripe')
                    ->assertUrlIs('https://connect.stripe.com/oauth/authorize')
                    ->assertQueryStringHas('response_type', 'code')
                    ->assertQueryStringHas('scope', 'read_write')
                    ->assertQueryStringHas('client_id', config('services.stripe.client_id'))
                    ->clickLink("Skip this account form")
                    ->assertRouteIs('backstage.concerts.index');

            tap($user->fresh(), function ($user) {
                $this->assertNotNull($user->stripe_account_id);
                $this->assertNotNull($user->stripe_access_token);

                $connectedAccount = \Stripe\Account::retrieve(null, [
                    'api_key' => $user->stripe_access_token
                ]);
                $this->assertEquals($connectedAccount->id, $user->stripe_account_id);
            });
        });
    }
}
