<?php

namespace AwemaPL\Auth\Tests\Unit\Services;

use AwemaPL\Auth\Tests\TestCase;
use AwemaPL\Auth\Tests\Stubs\User;
use AwemaPL\Auth\Models\UserSocial;
use Illuminate\Auth\Events\Registered;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use AwemaPL\Auth\Listeners\EventSubscriber;

class EventSubscriberTest extends TestCase
{
    use MockeryPHPUnitIntegration;
    
    /** @test */
    public function it_sends_verification_email_if_user_didnt_verify_email()
    {
        $user = $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('hasVerifiedEmail')
            ->once()
            ->andReturn(false);
            $mock->shouldReceive('sendEmailVerificationNotification')
            ->once()
            ->andReturn(true);
        });

        $event = new Registered($user);

        $subscriber = new EventSubscriber();

        $subscriber->handleRegistered($event);
    }
    
    /** @test */
    public function it_doesnt_send_verification_email_if_user_already_verified_it()
    {
        $this->app['config']->set('app.debug', env('APP_DEBUG', true));

        $user = $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('hasVerifiedEmail')
            ->once()
            ->andReturn(true);
        });

        $event = new Registered($user);

        $subscriber = new EventSubscriber();

        $subscriber->handleRegistered($event);
    }
    
    /** @test */
    public function it_doesnt_send_verification_email_if_its_disabled()
    {
        $this->app['config']->set('awemapl-auth.enabled', []);

        $user = $this->mock(User::class, function ($mock) {
            $mock->shouldNotReceive('hasVerifiedEmail');
            $mock->shouldNotReceive('sendEmailVerificationNotification');
        });

        $event = new Registered($user);

        $subscriber = new EventSubscriber();

        $subscriber->handleRegistered($event);
    }
}