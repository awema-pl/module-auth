<?php

namespace AwemaPL\Auth\Tests\Feature;

use AwemaPL\Auth\Tests\TestCase;
use AwemaPL\Auth\Tests\Stubs\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPasswordTest extends TestCase
{
    /** @test */
    public function it_returns_view_on_password_request()
    {
        $this->get('password/reset')
            ->assertViewIs('awemapl-auth::auth.passwords.email');
    }

    /** @test */
    public function it_returns_view_on_password_reset()
    {
        $this->get('password/reset/token')
            ->assertViewIs('awemapl-auth::auth.passwords.reset');
    }

    /** @test */
    public function it_sends_password_reset_email()
    {
        $user = factory(User::class)->create();

        Notification::fake();

        $this->post('password/email', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordNotification::class);
    }
}