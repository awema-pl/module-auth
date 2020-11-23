<?php

namespace AwemaPL\Auth\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\RedirectsUsers;
use AwemaPL\Auth\Controllers\Traits\RedirectsTo;
use AwemaPL\Auth\Sections\Users\Repositories\Contracts\UserRepository;
use AwemaPL\Auth\Services\Contracts\SocialProvidersManager;
use AwemaPL\Auth\Controllers\Traits\AuthenticatesUsersWith2FA;

class SocialLoginController extends Controller
{
    use RedirectsUsers, RedirectsTo, AuthenticatesUsersWith2FA;

    /**
     * User repository
     *
     * @var \AwemaPL\Auth\Sections\Users\Repositories\Contracts\UserRepository
     */
    protected $users;

    /**
     * Socialite's providers manager
     *
     * @var \AwemaPL\Auth\Services\Contracts\SocialProvidersManager
     */
    protected $provider;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UserRepository $users, 
        SocialProvidersManager $provider
    )
    {
        $this->users = $users;

        $this->provider = $provider;
    }

    /**
     * Redirect to OAuth provider authentication page
     *
     * @param \Illuminate\Http\Request $request
     * @param string $service
     * @return void
     */
    public function redirect(Request $request, $service)
    {
        return $this->provider
            ->buildProvider($service)
            ->redirect();
    }

    /**
     * Obtain the user information from OAuth provider
     *
     * @param \Illuminate\Http\Request $request
     * @param string $service
     * @return void
     */
    public function callback(Request $request, $service)
    {     
        $serviceUser = $this->provider
            ->buildProvider($service)
            ->user();

        $user = $this->users
            ->getUserBySocial($serviceUser, $service);

        if (! $user) {
            $user = $this->createUser($serviceUser);
        }
        
        if (! $user->hasSocial($service)) {
            $this->createUsersSocial($user, $serviceUser, $service);
        }

        Auth::login($user);

        return $this->authenticated($request, $user)
                ?: redirect()->intended($this->redirectPath());
    }

    /**
     * Create new user using service credentials
     *
     * @param \Laravel\Socialite\Two\User $serviceUser
     * @return \App\User
     */
    protected function createUser($serviceUser)
    {
        return $this->users->store([
            'password' => bcrypt(str_random(40)),
            'name' => $serviceUser->getName() ?: $serviceUser->getNickname(),
            'email' => $serviceUser->getEmail()
        ]);
    }

    /**
     * Create new user's social record using service credentials
     *
     * @param \App\User $user
     * @param \Laravel\Socialite\Two\User $serviceUser
     * @param string $service
     * @return \AwemaPL\Auth\Models\UserSocial
     */
    protected function createUsersSocial($user, $serviceUser, $service)
    {
        return $user->social()->create([
            'social_id' => $serviceUser->getId(),
            'service' => $service
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($this->isTwoFactorEnabled($user)) {
            return $this->handleTwoFactorAuthentication($request, $user);
        }
    }
}
