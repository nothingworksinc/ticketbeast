<?php

namespace Tests\Feature\Backstage;

use App\User;
use App\Concert;
use Tests\TestCase;
use PHPUnit\Framework\Assert;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ViewConcertListTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_cannot_view_a_promoters_concert_list()
    {
        $response = $this->get('/backstage/concerts');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    function promoters_can_view_a_list_of_their_concerts()
    {
        $this->disableExceptionHandling();
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $concertA = factory(Concert::class)->create(['user_id' => $user->id]);
        $concertB = factory(Concert::class)->create(['user_id' => $user->id]);
        $concertC = factory(Concert::class)->create(['user_id' => $otherUser->id]);
        $concertD = factory(Concert::class)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/backstage/concerts');

        $response->assertStatus(200);
        $this->assertTrue($response->original->getData()['concerts']->contains($concertA));
        $this->assertTrue($response->original->getData()['concerts']->contains($concertB));
        $this->assertTrue($response->original->getData()['concerts']->contains($concertD));
        $this->assertFalse($response->original->getData()['concerts']->contains($concertC));
    }
}
