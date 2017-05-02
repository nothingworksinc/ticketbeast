<?php

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PromoterLoginTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function logging_in_with_valid_credentials()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('super-secret-password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'super-secret-password',
        ]);

        $response->assertRedirect('/backstage/concerts');
        $this->assertTrue(Auth::check());
        $this->assertTrue(Auth::user()->is($user));
    }

    /** @test */
    function logging_in_with_invalid_credentials()
    {
        $this->disableExceptionHandling();

        $user = factory(User::class)->create([
            'email' => 'jane@example.com',
            'password' => bcrypt('super-secret-password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'jane@example.com',
            'password' => 'not-the-right-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::check());
    }

    /** @test */
    function logging_in_with_an_account_that_does_not_exist()
    {
        $this->disableExceptionHandling();

        $response = $this->post('/login', [
            'email' => 'nobody@example.com',
            'password' => 'not-the-right-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertFalse(Auth::check());
    }
}
