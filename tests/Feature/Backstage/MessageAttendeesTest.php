<?php

namespace Tests\Feature\Backstage;

use App\User;
use ConcertFactory;
use Tests\TestCase;
use App\AttendeeMessage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MessageAttendeesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_promoter_can_view_the_message_form_for_their_own_concert()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create();
        $concert = ConcertFactory::createPublished([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("/backstage/concerts/{$concert->id}/messages/new");

        $response->assertStatus(200);
        $response->assertViewIs('backstage.concert-messages.new');
        $this->assertTrue($response->data('concert')->is($concert));
    }

    /** @test */
    function a_promoter_cannot_view_the_message_form_for_another_concert()
    {
        $user = factory(User::class)->create();
        $concert = ConcertFactory::createPublished([
            'user_id' => factory(User::class)->create(),
        ]);

        $response = $this->actingAs($user)->get("/backstage/concerts/{$concert->id}/messages/new");

        $response->assertStatus(404);
    }

    /** @test */
    function a_guest_cannot_view_the_message_form_for_any_concert()
    {
        $concert = ConcertFactory::createPublished();

        $response = $this->get("/backstage/concerts/{$concert->id}/messages/new");

        $response->assertRedirect('/login');
    }

    /** @test */
    function a_promoter_can_send_a_new_message()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create();
        $concert = ConcertFactory::createPublished([
            'user_id' => $user->id,
        ]);

        $response = $this->actingAs($user)->post("/backstage/concerts/{$concert->id}/messages", [
            'subject' => 'My subject',
            'message' => 'My message',
        ]);

        $response->assertRedirect("/backstage/concerts/{$concert->id}/messages/new");
        $response->assertSessionHas('flash');

        $message = AttendeeMessage::first();
        $this->assertEquals($concert->id, $message->concert_id);
        $this->assertEquals('My subject', $message->subject);
        $this->assertEquals('My message', $message->message);
    }

    /** @test */
    function a_promoter_cannot_send_a_new_message_for_other_concerts()
    {
        $user = factory(User::class)->create();
        $otherUser = factory(User::class)->create();
        $concert = ConcertFactory::createPublished([
            'user_id' => $otherUser->id,
        ]);

        $response = $this->actingAs($user)->post("/backstage/concerts/{$concert->id}/messages", [
            'subject' => 'My subject',
            'message' => 'My message',
        ]);

        $response->assertStatus(404);
        $this->assertEquals(0, AttendeeMessage::count());
    }

    /** @test */
    function a_guest_cannot_send_a_new_message_for_any_concerts()
    {
        $concert = ConcertFactory::createPublished();

        $response = $this->post("/backstage/concerts/{$concert->id}/messages", [
            'subject' => 'My subject',
            'message' => 'My message',
        ]);

        $response->assertRedirect('/login');
        $this->assertEquals(0, AttendeeMessage::count());
    }

    /** @test */
    function subject_is_required()
    {
        $user = factory(User::class)->create();
        $concert = ConcertFactory::createPublished([
            'user_id' => $user->id,
        ]);

        $response = $this->from("/backstage/concerts/{$concert->id}/messages/new")
            ->actingAs($user)
            ->post("/backstage/concerts/{$concert->id}/messages", [
                'subject' => '',
                'message' => 'My message',
            ]);

        $response->assertRedirect("/backstage/concerts/{$concert->id}/messages/new");

        $response->assertSessionHasErrors('subject');
        $this->assertEquals(0, AttendeeMessage::count());
    }

    /** @test */
    function message_is_required()
    {
        $user = factory(User::class)->create();
        $concert = ConcertFactory::createPublished([
            'user_id' => $user->id,
        ]);

        $response = $this->from("/backstage/concerts/{$concert->id}/messages/new")
            ->actingAs($user)
            ->post("/backstage/concerts/{$concert->id}/messages", [
                'subject' => 'My subject',
                'message' => '',
            ]);

        $response->assertRedirect("/backstage/concerts/{$concert->id}/messages/new");

        $response->assertSessionHasErrors('message');
        $this->assertEquals(0, AttendeeMessage::count());
    }
}
