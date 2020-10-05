<?php

namespace AwemaPL\Auth\Tests\Feature;

use AwemaPL\Auth\Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    /** @test */
    public function it_returns_view_on_index()
    {
        $this->get('password/reset')
            ->assertViewIs('awemapl-auth::auth.passwords.email');
    }
}