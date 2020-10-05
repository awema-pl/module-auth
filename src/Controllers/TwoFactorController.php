<?php

namespace AwemaPL\Auth\Controllers;

use Illuminate\Http\Request;
use AwemaPL\Auth\Models\Country;
use AwemaPL\Auth\Services\Contracts\TwoFactor;
use Illuminate\Foundation\Auth\RedirectsUsers;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Auth\Requests\TwoFactorStoreRequest;
use AwemaPL\Auth\Requests\TwoFactorVerifyRequest;

class TwoFactorController extends Controller
{
    use RedirectsUsers, RedirectsTo;

    /**
     * Where to redirect users.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Show the application's two factor setup form.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->isTwoFactorPending()) {
            $qrCode = app()->make(TwoFactor::class)->qrCode(auth()->user());
        }
        
        // $countries = Country::all();
        
        return view('awemapl-auth::twofactor.index', compact('countries', 'qrCode'));
    }

    /**
     * Store new user's two factor record
     *
     * @param \AwemaPL\Auth\Requests\TwoFactorStoreRequest $request
     * @param \AwemaPL\Auth\Services\Contracts\TwoFactor $twoFactor
     * @return void
     */
    public function store(TwoFactorStoreRequest $request, TwoFactor $twoFactor)
    {
        $codeAndPhone = preg_split('/\s/', $request->phone, 2);

        $user = $request->user();

        $user->twoFactor()->create([
            'phone' => $codeAndPhone[1],
            'dial_code' => $codeAndPhone[0],
        ]);

        if ($response = $twoFactor->register($user)) {
            $user->twoFactor()->update([
                'identifier' => $response->user->id
            ]);
        }
        return $this->twofactored($request) ?: back();
    }

    /**
     * Verify two factor token
     *
     * @param \AwemaPL\Auth\Requests\TwoFactorVerifyRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verify(TwoFactorVerifyRequest $request)
    {
        $request->user()->twoFactor()->update([
            'verified' => true
        ]);

        return $this->twofactored($request) ?: back();
    }

    /**
     * Remove user from two factor service and application's db
     *
     * @param \Illuminate\Http\Request $request
     * @param \AwemaPL\Auth\Services\Contracts\TwoFactor $twoFactor
     * @return void
     */
    public function destroy(Request $request, TwoFactor $twoFactor)
    {
        if ($twoFactor->remove($user = $request->user())) {

            $user->twoFactor()->delete();
        }
        return $this->twofactored($request) ?: back();
    }

    protected function twofactored(Request $request)
    {
        if ($request->ajax()) {
            return $this->ajaxRedirectTo($request);
        }
    }
}
