<?php

namespace AwemaPL\Auth;

use App\Http\Kernel;
use AwemaPL\Auth\Auth;
use AwemaPL\Auth\Middlewares\EnsureEmailIsVerified;
use AwemaPL\Auth\Middlewares\Installation;
use AwemaPL\Auth\Middlewares\Language;
use AwemaPL\Auth\Models\Traits\SendsEmailVerification;
use AwemaPL\Auth\Models\Traits\SendsPasswordReset;
use AwemaPL\Auth\Sections\Tokens\Repositories\Contracts\TokenRepository;
use AwemaPL\Auth\Sections\Tokens\Repositories\EloquentTokenRepository;
use AwemaPL\Subscription\Contracts\Subscription as SubscriptionContract;
use GuzzleHttp\Client;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use AwemaPL\Auth\Services\AuthyTwoFactor;
use AwemaPL\Auth\Listeners\EventSubscriber;
use AwemaPL\Auth\Services\Contracts\TwoFactor;
use AwemaPL\Auth\Contracts\Auth as AuthContract;
use AwemaPL\Auth\Services\SocialiteProvidersManager;
use AwemaPL\Auth\Sections\Users\Repositories\EloquentUserRepository;
use AwemaPL\Auth\Sections\Users\Repositories\Contracts\UserRepository;
use AwemaPL\Auth\Services\Contracts\SocialProvidersManager;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'auth');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'awemapl-auth');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->publishes([
            __DIR__ . '/../config/awemapl-auth.php' => config_path('awemapl-auth.php'),
        ], 'config');
        // $this->bootMigrationsPublishing();
        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/awemapl-auth'),
        ], 'views');
        $this->bootMiddleware();
        app('awema-auth')->addWidgets();
        Event::subscribe(EventSubscriber::class);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/awemapl-auth.php', 'awemapl-auth');
        $this->mergeConfigFrom(__DIR__ . '/../config/auth-menu.php', 'auth-menu');
        $this->app->bind(AuthContract::class, Auth::class);
        $this->app->singleton('awema-auth', AuthContract::class);
        app('awema-auth')->registerUserHasApiTokens();
        app('awema-auth')->menuMerge();
        app('awema-auth')->mergePermissions();
        $this->registerRepositories();
        $this->registerServices();
        $this->registerHelpers();
        SendsPasswordReset::setPasswordResetNotification();
        SendsEmailVerification::setSendEmailVerificationNotification();
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
        $this->app->bind(TokenRepository::class, EloquentTokenRepository::class);
    }

    /**
     * Register and bind package services
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->bind(SocialProvidersManager::class, SocialiteProvidersManager::class);
        $this->app->singleton(TwoFactor::class, function () {
            return new AuthyTwoFactor(new Client());
        });
    }

    /**
     * Prepare migrations for publication
     *
     * @return void
     */
    protected function bootMigrationsPublishing()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/create_countries_table.php.stub'
            => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_countries_table.php'),
            __DIR__ . '/../database/migrations/create_two_factor_table.php.stub'
            => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_two_factor_table.php'),
            __DIR__ . '/../database/migrations/create_users_social_table.php.stub'
            => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_users_social_table.php'),
        ], 'migrations');
    }

    /**
     * Register helpers file
     */
    public function registerHelpers()
    {
        if (file_exists($file = __DIR__ . '/helpers.php')) {
            require $file;
        }
    }

    /**
     * Installation
     */
    protected function installation()
    {
        $auth = app(Auth::class);
        if ($auth->isInstallationUserEnabled() && !$auth->isExistUserInDatabase()) {
            $auth->redirectToInstallationUser();
        }
    }

    /**
     * Boot middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootMiddleware()
    {
        $this->bootGlobalMiddleware();
        $this->bootRouteMiddleware();
        $this->bootGroupMiddleware();
    }

    /**
     * Boot route middleware
     */
    private function bootRouteMiddleware()
    {
        $router = app('router');
        $router->aliasMiddleware('verified', verified::class);
    }

    /**
     * Boot group middleware
     */
    private function bootGroupMiddleware()
    {
        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->appendMiddlewareToGroup('web', EnsureEmailIsVerified::class);
        $kernel->prependMiddlewareToGroup('api', EnsureFrontendRequestsAreStateful::class);
    }

    /**
     * Boot global middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootGlobalMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->pushMiddleware(Installation::class);
    }


}
