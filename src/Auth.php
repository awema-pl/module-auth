<?php

namespace AwemaPL\Auth;

use Illuminate\Routing\Router;
use AwemaPL\Auth\Contracts\Auth as AuthContract;
use AwemaPL\Auth\Middlewares\SocialAuthentication;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;
use Exception;

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

        // Installation user Routes...
        if ($this->isInstallationUserEnabled() && !$this->isExistUserInDatabase()){
            $this->installationUserRoutes();
        }

        if ($this->isActiveUserRoutes()) {
            $this->userRoutes();
        }

        if ($this->isActiveTokenRoutes()) {
            $this->tokenRoutes();
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
     * Check if installtion user eneabled in config
     *
     * @return boolean
     */
    public function isInstallationUserEnabled()
    {
        return in_array('user', config('awemapl-auth.installation.sections'));
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

    /**
     * Add installation user routes
     */
    public function installationUserRoutes()
    {
        $this->router->get(
            'installation/auth/user',
            '\AwemaPL\Auth\Controllers\Installation\UserController@showRegisterForm'
        )->name('auth.installation.user.register');
    }

    /**
     * Check is exist user in database
     *
     * @return bool
     */
    public function isExistUserInDatabase()
    {
        try {
            $class= config('auth.providers.users.model');
            return !!$class::count();
        } catch (Exception $e){}
        return false;
    }

    /**
     * Register user has API tokens
     *
     * @throws \ReflectionException
     */
    public function registerUserHasApiTokens()
    {
        $userClass = config('auth.providers.users.model');
        if (!method_exists($userClass, 'tokens')) {
            $reflector = new \ReflectionClass($userClass);
            $path= $reflector->getFileName();
            $content = File::get($path);
            if (!Str::contains($content, 'use \Laravel\Sanctum\HasApiTokens;')){
                $content = Str::replaceFirst('{', '{' . PHP_EOL . PHP_EOL . "\t" . 'use \Laravel\Sanctum\HasApiTokens;', $content);
                File::put($path, $content);
            }
        }
    }

    /**
     * Add permissions for module permission
     */
    public function mergePermissions()
    {
        if ($this->canMergePermissions()){
            $subscriptionPermissions = config('awemapl-auth.permissions');
            $tempPermissions = config('temp_permission.permissions', []);
            $permissions = array_merge_recursive($tempPermissions, $subscriptionPermissions);
            config(['temp_permission.permissions' => $permissions]);
        }
    }

    /**
     * Can merge permissions
     *
     * @return boolean
     */
    private function canMergePermissions()
    {
        return !!config('awemapl-auth.merge_permissions');
    }

    /**
     * Is active user routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveUserRoutes()
    {
        return config('awemapl-auth.routes.user.active');
    }

    /**
     * Is active token routes
     *
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    private function isActiveTokenRoutes()
    {
        return config('awemapl-auth.routes.token.active');
    }


    /**
     * User routes
     */
    protected function userRoutes()
    {

        $prefix = config('awemapl-auth.routes.user.prefix');
        $namePrefix = config('awemapl-auth.routes.user.name_prefix');
        $middleware = config('awemapl-auth.routes.user.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Auth\Sections\Users\Http\Controllers\UserController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Auth\Sections\Users\Http\Controllers\UserController@store')
                ->name('store');
            $this->router
                ->post('/switch', '\AwemaPL\Auth\Sections\Users\Http\Controllers\UserController@switch')
                ->name('switch');
            $this->router
                ->get('/users', '\AwemaPL\Auth\Sections\Users\Http\Controllers\UserController@scope')
                ->name('scope');
            $this->router
                ->patch('{id?}', '\AwemaPL\Auth\Sections\Users\Http\Controllers\UserController@update')
                ->name('update');
            $this->router
                ->patch('/change-password/{id?}', '\AwemaPL\Auth\Sections\Users\Http\Controllers\UserController@changePassword')
                ->name('change_password');
            $this->router
                ->delete('{id?}', '\AwemaPL\Auth\Sections\Users\Http\Controllers\UserController@delete')
                ->name('delete');
        });
    }

    /**
     * Token routes
     */
    protected function tokenRoutes()
    {

        $prefix = config('awemapl-auth.routes.token.prefix');
        $namePrefix = config('awemapl-auth.routes.token.name_prefix');
        $middleware = config('awemapl-auth.routes.token.middleware');
        $this->router->prefix($prefix)->name($namePrefix)->middleware($middleware)->group(function () {
            $this->router
                ->get('/', '\AwemaPL\Auth\Sections\Tokens\Http\Controllers\TokenController@index')
                ->name('index');
            $this->router
                ->post('/', '\AwemaPL\Auth\Sections\Tokens\Http\Controllers\TokenController@store')
                ->name('store');
            $this->router
                ->get('/tokens', '\AwemaPL\Auth\Sections\Tokens\Http\Controllers\TokenController@scope')
                ->name('scope');
            $this->router
                ->patch('change/{id?}', '\AwemaPL\Auth\Sections\Tokens\Http\Controllers\TokenController@change')
                ->name('change');
            $this->router
                ->delete('{id?}', '\AwemaPL\Auth\Sections\Tokens\Http\Controllers\TokenController@delete')
                ->name('delete');
        });
    }


    /**
     * Menu merge in navigation
     */
    public function menuMerge()
    {
        if ($this->canMergeMenu()) {
            $chromatorNav = config('auth-menu.navs', []);
            $navTemp = config('temp_navigation.navs', []);
            $nav = array_merge_recursive($navTemp, $chromatorNav);
            config(['temp_navigation.navs' => $nav]);
        }
    }

    /**
     * Can merge menu
     *
     * @return boolean
     */
    private function canMergeMenu()
    {
        return !!config('auth-menu.merge_to_navigation');
    }

}
