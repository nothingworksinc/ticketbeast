<?php

use App\Concert;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $gateway = new \App\Billing\FakePaymentGateway;

        $user = factory(App\User::class)->create([
            'email' => "adam@example.com",
            'password' => bcrypt('secret'),
        ]);

        $concert = \ConcertFactory::createPublished([
            'user_id' => $user->id,
            'title' => "The Red Chord",
            'subtitle' => "with Animosity and Lethargy",
            'additional_information' => "This concert is 19+.",
            'venue' => "The Mosh Pit",
            'venue_address' => "123 Example Lane",
            'city' => "Laraville",
            'state' => "ON",
            'zip' => "17916",
            'date' => Carbon::today()->addMonths(3)->hour(20),
            'ticket_price' => 3250,
            'ticket_quantity' => 250,
        ]);

        foreach(range(1, 50) as $i) {
            Carbon::setTestNow(Carbon::instance($faker->dateTimeBetween('-2 months')));

            $concert->reserveTickets(rand(1, 4), $faker->safeEmail)
                    ->complete($gateway, $gateway->getValidTestToken($faker->creditCardNumber));
        }

        Carbon::setTestNow();

        factory(App\Concert::class)->create([
            'user_id' => $user->id,
            'title' => "Slayer",
            'subtitle' => "with Forbidden and Testament",
            'additional_information' => null,
            'venue' => "The Rock Pile",
            'venue_address' => "55 Sample Blvd",
            'city' => "Laraville",
            'state' => "ON",
            'zip' => "19276",
            'date' => Carbon::today()->addMonths(6)->hour(19),
            'ticket_price' => 5500,
            'ticket_quantity' => 10,
        ]);
    }
}
