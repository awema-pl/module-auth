<?php

namespace AwemaPL\Auth\Tests\Stubs;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use AwemaPL\Auth\Models\Traits\SendsEmailVerification;
use AwemaPL\Auth\Models\Traits\HasSocialAuthentication;
use Illuminate\Foundation\Auth\User as Authenticatable;
use AwemaPL\Auth\Models\Traits\HasTwoFactorAuthentication;

class User extends Authenticatable
{
    use Notifiable, HasSocialAuthentication, HasTwoFactorAuthentication, SendsEmailVerification;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
