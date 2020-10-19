<?php

namespace AwemaPL\Auth;

use Illuminate\Routing\Router;
use AwemaPL\Auth\Contracts\Auth as AuthContract;
use AwemaPL\Auth\Middlewares\SocialAuthentication;

class Auth implements AuthContract
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes.
     *
     * @return void
     */
    public function routes(array $params = [])
    {
        // Authentication Routes...
        $this->loginRoutes();

        // Registration Routes...
        if ($params['register'] ?? true) {
            $this->registrationRoutes();
        }

        // Reset password Routes...
        $this->resetPasswordRoutes();

        // Socialite authentication Routes...
        if ($this->isSocialEnabled()) {
            $this->socialiteRoutes();
        }

        // Two factor authentication Routes...
        if ($this->isTwoFactorEnabled()) {
            $this->twoFactorRoutes();
        }

        // Email verification Routes...
        if ($this->isEmailVerificationEnabled()) {
            $this->emailVerificationRoutes();
        }
    }

    /**
     * Check if socialite authentication eneabled in config
     *
     * @return boolean
     */
    public function isSocialEnabled()
    {
        return in_array('social', config('awemapl-auth.enabled'));
    }

    /**
     * Check if two factor authentication eneabled in config
     *
     * @return boolean
     */
    public function isTwoFactorEnabled()
    {
        return in_array('two_factor', config('awemapl-auth.enabled'));
    }

    /**
     * Check if two factor authentication eneabled in config
     *
     * @return boolean
     */
    public function isEmailVerificationEnabled()
    {
        return in_array('email_verification', config('awemapl-auth.enabled'));
    }

    /**
     * Login routes.
     *
     * @return void
     */
    protected function loginRoutes()
    {
       $this->router->middleware(['web'])->group(function(){
           $this->router->get(
               'login',
               '\AwemaPL\Auth\Controllers\LoginController@showLoginForm'
           )->name('login');

           $this->router->post(
               'login',
               '\AwemaPL\Auth\Controllers\LoginController@login'
           );

           $this->router->any(
               'logout',
               '\AwemaPL\Auth\Controllers\LoginController@logout'
           )->name('logout');
       });
    }

    /**
     * Registration routes.
     *
     * @return void
     */
    protected function registrationRoutes()
    {
        $this->router->middleware(['web'])->group(function(){
            $this->router->get(
                'register',
                '\AwemaPL\Auth\Controllers\RegisterController@showRegistrationForm'
            )->name('register');

            $this->router->post(
                'register',
                '\AwemaPL\Auth\Controllers\RegisterController@register'
            );
        });
    }

    /**
     * Reset password routes.
     *
     * @return void
     */
    protected function resetPasswordRoutes()
    {
        $this->router->get(
            'password/reset',
            '\AwemaPL\Auth\Controllers\ForgotPasswordController@showLinkRequestForm'
        )->name('password.request');

        $this->router->post(
            'password/email',
            '\AwemaPL\Auth\Controllers\ForgotPasswordController@sendResetLinkEmail'
        )->name('password.email');

        $this->router->get(
            'password/reset/{token}',
            '\AwemaPL\Auth\Controllers\ResetPasswordController@showResetForm'
        )->name('password.reset');

        $this->router->post(
            'password/reset',
            '\AwemaPL\Auth\Controllers\ResetPasswordController@reset'
        )->name('password.update');
    }

    /**
     * Socialite routes.
     *
     * @return void
     */
    protected function socialiteRoutes()
    {
        $this->router->middleware(['guest', SocialAuthentication::class])
            ->group(function () {

                $this->router->get(
                    'login/{service}',
                    '\AwemaPL\Auth\Controllers\SocialLoginController@redirect'
                )->name('login.social');

                $this->router->get(
                    'login/{service}/callback',
                    '\AwemaPL\Auth\Controllers\SocialLoginController@callback'
                );
            });
    }

    /**
     * Two factor routes.
     *
     * @return void
     */
    protected function twoFactorRoutes()
    {
        // Setting up and verifying 2FA routes
        $this->router->middleware(['auth'])
            ->group(function () {

                $this->router->get(
                    'twofactor',
                    '\AwemaPL\Auth\Controllers\TwoFactorController@index'
                )->name('twofactor.index');

                $this->router->post(
                    'twofactor',
                    '\AwemaPL\Auth\Controllers\TwoFactorController@store'
                )->name('twofactor.store');

                $this->router->post(
                    'twofactor/verify',
                    '\AwemaPL\Auth\Controllers\TwoFactorController@verify'
                )->name('twofactor.verify');

                $this->router->delete(
                    'twofactor',
                    '\AwemaPL\Auth\Controllers\TwoFactorController@destroy'
                )->name('twofactor.destroy');
            });

        // 2FA login routes
        $this->router->middleware(['guest'])
            ->group(function () {

                $this->router->get(
                    'login/twofactor/verify',
                    '\AwemaPL\Auth\Controllers\TwoFactorLoginController@index'
                )->name('login.twofactor.index');

                $this->router->post(
                    'login/twofactor/verify',
                    '\AwemaPL\Auth\Controllers\TwoFactorLoginController@verify'
                )->name('login.twofactor.verify');
            });
    }

    /**
     * Email verification routes.
     *
     * @return void
     */
    protected function emailVerificationRoutes()
    {
        $this->router->get(
            'email/verify', 
            '\AwemaPL\Auth\Controllers\VerificationController@show'
        )->name('verification.notice');

        $this->router->get(
            'email/verify/{id}/{hash}',
            '\AwemaPL\Auth\Controllers\VerificationController@verify'
        )->name('verification.verify');

        $this->router->post(
            'email/resend', 
            '\AwemaPL\Auth\Controllers\VerificationController@resend'
        )->name('verification.resend')->middleware(['web', 'throttle:1,0.2']);
    }
}
