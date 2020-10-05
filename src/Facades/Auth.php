<?php

namespace AwemaPL\Auth\Facades;

use AwemaPL\Auth\Contracts\Auth as AuthContract;
use Illuminate\Support\Facades\Facade;

class Auth extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return AuthContract::class;
    }
}
