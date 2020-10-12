<?php

namespace AwemaPL\Auth;

use AwemaPL\Auth\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use AwemaPL\Auth\Services\AuthyTwoFactor;
use AwemaPL\Auth\Listeners\EventSubscriber;
use AwemaPL\Auth\Services\Contracts\TwoFactor;
use AwemaPL\Auth\Contracts\Auth as AuthContract;
use AwemaPL\Auth\Services\SocialiteProvidersManager;
use AwemaPL\Auth\Repositories\EloquentUserRepository;
use AwemaPL\Auth\Repositories\Contracts\UserRepository;
use AwemaPL\Auth\Services\Contracts\SocialProvidersManager;

class AuthServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views', 'awemapl-auth');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/awemapl-auth.php' => config_path('awemapl-auth.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

       // $this->bootMigrationsPublishing();

        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/awemapl-auth'),
        ], 'views');

        Event::subscribe(EventSubscriber::class);
    }


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/awemapl-auth.php', 'awemapl-auth');

        $this->app->singleton(AuthContract::class, Auth::class);

        $this->registerRepositories();

        $this->registerServices();

        $this->registerHelpers();
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(UserRepository::class, EloquentUserRepository::class);
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
            __DIR__.'/../database/migrations/create_countries_table.php.stub' 
                => database_path('migrations/'.date('Y_m_d_His', time()).'_create_countries_table.php'),
            __DIR__.'/../database/migrations/create_two_factor_table.php.stub' 
                => database_path('migrations/'.date('Y_m_d_His', time()).'_create_two_factor_table.php'),
            __DIR__.'/../database/migrations/create_users_social_table.php.stub' 
                => database_path('migrations/'.date('Y_m_d_His', time()).'_create_users_social_table.php'),
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

}
