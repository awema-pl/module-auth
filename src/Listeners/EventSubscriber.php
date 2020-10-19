<?php

namespace AwemaPL\Auth\Listeners;

use AwemaPL\Auth\Facades\Auth as AwemaAuth;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class EventSubscriber
{
    public function subscribe($events)
    {
        $events->listen(
            'Illuminate\Auth\Events\Registered',
            static::class.'@handleRegistered'
        );
    }

    public function handleRegistered($event)
    {
        if (AwemaAuth::isEmailVerificationEnabled() 
            && ! $event->user->hasVerifiedEmail()) {
                
            $event->user->sendEmailVerificationNotification();
        }
    }
}