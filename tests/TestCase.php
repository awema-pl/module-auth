<?php

namespace AwemaPL\Auth\Tests;

use AwemaPL\Auth\Facades\Auth;
use AwemaPL\Auth\Tests\Stubs\User;
use AwemaPL\Auth\AuthServiceProvider;
use AwemaPL\Auth\Tests\Stubs\TwoFactor;
use Illuminate\Database\Schema\Blueprint;
use AwemaPL\Auth\Services\Contracts\TwoFactor as TwoFactorContract;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        Auth::routes();

        $this->loadLaravelMigrations(['--database' => 'testing']);

        $this->assignRouteActionMiddlewares();

        $this->withFactories(__DIR__ . '/../database/factories');

        $this->app->singleton(TwoFactorContract::class, TwoFactor::class);
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', env('APP_DEBUG', true));

        $app['config']->set('auth.providers.users.model', User::class);

        $this->setUpDatabase($app);

        $app->register( \Laravel\Socialite\SocialiteServiceProvider::class);
    }

    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AuthServiceProvider::class
        ];
    }

    /**
     * Load package alias
     * @param  \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'AwemaAuth' => Auth::class,
        ];
    }

    protected function assignRouteActionMiddlewares()
    {
        $actions = [
            'AwemaPL\Auth\Controllers\LoginController@showLoginForm',
            'AwemaPL\Auth\Controllers\LoginController@login',
            'AwemaPL\Auth\Controllers\LoginController@logout',
            'AwemaPL\Auth\Controllers\RegisterController@showRegistrationForm',
            'AwemaPL\Auth\Controllers\TwoFactorLoginController@index',
            'AwemaPL\Auth\Controllers\SocialLoginController@redirect',
            'AwemaPL\Auth\Controllers\SocialLoginController@callback',
            'AwemaPL\Auth\Controllers\TwoFactorController@index',
            'AwemaPL\Auth\Controllers\ForgotPasswordController@showLinkRequestForm',
            'AwemaPL\Auth\Controllers\ResetPasswordController@showResetForm',
            'AwemaPL\Auth\Controllers\VerificationController@show',
        ];

        $middlwares = ['web'];

        foreach ($actions as $action) {
            app('router')->getRoutes()->getByAction($action)
                ->middleware($middlwares);
        }
    }

    protected function setUpDatabase($app)
    {
        $builder = $app['db']->connection()->getSchemaBuilder();

        $builder->create('two_factor', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('identifier')->nullable();
            $table->string('phone');
            $table->string('dial_code');
            $table->boolean('verified')->default(false);
            $table->timestamps();
        });

        $builder->create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('code', 2);
            $table->string('dial_code');
        });

        $builder->create('users_social', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->string('social_id');
            $table->string('service');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}