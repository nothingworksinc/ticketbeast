<?php

namespace Tests\Feature\Backstage;

use App\User;
use App\Concert;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Http\Testing\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddConcertTest extends TestCase
{
    use DatabaseMigrations;

    private function validParams($overrides = [])
    {
        return array_merge([
            'title' => 'No Warning',
            'subtitle' => 'with Cruel Hand and Backtrack',
            'additional_information' => "You must be 19 years of age to attend this concert.",
            'date' => '2017-11-18',
            'time' => '8:00pm',
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Fake St.',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '12345',
            'ticket_price' => '32.50',
            'ticket_quantity' => '75',
        ], $overrides);
    }

    /** @test */
    function promoters_can_view_the_add_concert_form()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/backstage/concerts/new');

        $response->assertStatus(200);
    }

    /** @test */
    function guests_cannot_view_the_add_concert_form()
    {
        $response = $this->get('/backstage/concerts/new');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function adding_a_valid_concert()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/backstage/concerts', [
            'title' => 'No Warning',
            'subtitle' => 'with Cruel Hand and Backtrack',
            'additional_information' => "You must be 19 years of age to attend this concert.",
            'date' => '2017-11-18',
            'time' => '8:00pm',
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Fake St.',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '12345',
            'ticket_price' => '32.50',
            'ticket_quantity' => '75',
        ]);

        tap(Concert::first(), function ($concert) use ($response, $user) {
            $response->assertRedirect('/backstage/concerts');

            $this->assertTrue($concert->user->is($user));

            $this->assertFalse($concert->isPublished());

            $this->assertEquals('No Warning', $concert->title);
            $this->assertEquals('with Cruel Hand and Backtrack', $concert->subtitle);
            $this->assertEquals("You must be 19 years of age to attend this concert.", $concert->additional_information);
            $this->assertEquals(Carbon::parse('2017-11-18 8:00pm'), $concert->date);
            $this->assertEquals('The Mosh Pit', $concert->venue);
            $this->assertEquals('123 Fake St.', $concert->venue_address);
            $this->assertEquals('Laraville', $concert->city);
            $this->assertEquals('ON', $concert->state);
            $this->assertEquals('12345', $concert->zip);
            $this->assertEquals(3250, $concert->ticket_price);
            $this->assertEquals(75, $concert->ticket_quantity);
            $this->assertEquals(0, $concert->ticketsRemaining());
        });
    }

    /** @test */
    function guests_cannot_add_new_concerts()
    {
        $response = $this->post('/backstage/concerts', $this->validParams());

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function title_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'title' => '',
        ]));

        $response->assertStatus(302);
        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('title');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function subtitle_is_optional()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/backstage/concerts', $this->validParams([
            'subtitle' => '',
        ]));

        tap(Concert::first(), function ($concert) use ($response, $user) {
            $response->assertRedirect('/backstage/concerts');

            $this->assertTrue($concert->user->is($user));

            $this->assertNull($concert->subtitle);
        });
    }

    /** @test */
    function additional_information_is_optional()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/backstage/concerts', $this->validParams([
            'additional_information' => "",
        ]));

        tap(Concert::first(), function ($concert) use ($response, $user) {
            $response->assertRedirect('/backstage/concerts');

            $this->assertTrue($concert->user->is($user));

            $this->assertNull($concert->additional_information);
        });
    }

    /** @test */
    function date_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'date' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function date_must_be_a_valid_date()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'date' => 'not a date',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('date');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function time_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'time' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('time');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function time_must_be_a_valid_time()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'time' => 'not-a-time',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('time');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function venue_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'venue' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('venue');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function venue_address_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'venue_address' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('venue_address');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function city_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'city' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('city');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function state_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'state' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('state');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function zip_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'zip' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('zip');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_price_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_price' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_price');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_price_must_be_numeric()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_price' => 'not a price',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_price');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_price_must_be_at_least_5()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_price' => '4.99',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_price');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_quantity_is_required()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_quantity' => '',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_quantity');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_quantity_must_be_numeric()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_quantity' => 'not a number',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_quantity');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function ticket_quantity_must_be_at_least_1()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'ticket_quantity' => '0',
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('ticket_quantity');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function poster_image_is_uploaded_if_included()
    {
        $this->disableExceptionHandling();

        Storage::fake('s3');
        $user = factory(User::class)->create();
        $file = File::image('concert-poster.png', 850, 1100);

        $response = $this->actingAs($user)->post('/backstage/concerts', $this->validParams([
            'poster_image' => $file,
        ]));

        tap(Concert::first(), function ($concert) use ($file) {
            $this->assertNotNull($concert->poster_image_path);
            Storage::disk('s3')->assertExists($concert->poster_image_path);
            $this->assertFileEquals(
                $file->getPathname(),
                Storage::disk('s3')->path($concert->poster_image_path)
            );
        });
    }

    /** @test */
    function poster_image_must_be_an_image()
    {
        Storage::fake('s3');
        $user = factory(User::class)->create();
        $file = File::create('not-a-poster.pdf');

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'poster_image' => $file,
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('poster_image');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function poster_image_must_be_at_least_400px_wide()
    {
        Storage::fake('s3');
        $user = factory(User::class)->create();
        $file = File::image('poster.png', 399, 516);

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'poster_image' => $file,
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('poster_image');
        $this->assertEquals(0, Concert::count());
    }

    /** @test */
    function poster_image_must_have_letter_aspect_ratio()
    {
        Storage::fake('s3');
        $user = factory(User::class)->create();
        $file = File::image('poster.png', 851, 1100);

        $response = $this->actingAs($user)->from('/backstage/concerts/new')->post('/backstage/concerts', $this->validParams([
            'poster_image' => $file,
        ]));

        $response->assertRedirect('/backstage/concerts/new');
        $response->assertSessionHasErrors('poster_image');
        $this->assertEquals(0, Concert::count());
    }
}
