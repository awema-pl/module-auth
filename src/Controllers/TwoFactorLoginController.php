<?php

namespace AwemaPL\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RedirectsUsers;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Auth\Requests\TwoFactorVerifyRequest;

class TwoFactorLoginController extends Controller
{
    use RedirectsUsers, RedirectsTo;
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    
    /**
     * Show the application's 2FA's token verification form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('awemapl-auth::twofactor.verify');
    }

    /**
     * Verify two factor token
     *
     * @param \AwemaPL\Auth\Requests\TwoFactorVerifyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verify(TwoFactorVerifyRequest $request)
    {
        Auth::loginUsingId($request->user()->id, session('two_factor')->remember);

        session()->forget('two_factor');

        return $this->authenticated($request)
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request)
    {
        if ($request->ajax()) {
            return $this->ajaxRedirectTo($request);
        }
    }
}
