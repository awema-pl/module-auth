<?php

namespace AwemaPL\Auth\Controllers\Installation;

use Illuminate\Http\Request;
use AwemaPL\Auth\Models\Country;
use AwemaPL\Auth\Services\Contracts\TwoFactor;
use Illuminate\Foundation\Auth\RedirectsUsers;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Auth\Requests\TwoFactorStoreRequest;
use AwemaPL\Auth\Requests\TwoFactorVerifyRequest;
use AwemaPL\Auth\Controllers\Controller;

class UserController extends Controller
{
    use RedirectsUsers, RedirectsTo;

    /**
     * Where to redirect users.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Show the application's user install form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegisterForm()
    {

        return view('awemapl-auth::installation.user');
    }
}
