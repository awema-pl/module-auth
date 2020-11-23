<?php

namespace AwemaPL\Auth\Listeners;

use AwemaPL\Auth\Facades\Auth as AwemaAuth;
use AwemaPL\Auth\Sections\Tokens\Models\PlainToken;
use AwemaPL\Chromator\Sections\Tokens\Models\Token;
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

        $token = $event->user->createToken(config('awemapl-auth.default_name_token'));
        $plainToken = $token->plainTextToken;

        PlainToken::create([
            'token_id' =>$token->accessToken->id,
            'plain_token' => encrypt($plainToken),
        ]);
    }
}