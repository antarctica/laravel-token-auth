<?php

namespace Antarctica\LaravelTokenAuth;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class TokenAuthServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Load package resources
        $this->package('antarctica/laravel-token-auth', null, __DIR__.'/../../../..');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // Load package config to allow use of values
        Config::package('antarctica/laravel-token-auth', __DIR__.'/../../config');

        // Register package dependencies' service providers and aliases (so the user doesn't have to in app/config/app.php)
        $loader = AliasLoader::getInstance();

        $this->app->register('Tymon\JWTAuth\JWTAuthServiceProvider');
        $loader->alias('JWTAuth', 'Tymon\JWTAuth\Facades\JWTAuth');

        // Register package interfaces with their corresponding implementations
        $this->app->bind(
            'Antarctica\LaravelTokenAuth\Service\Token\TokenServiceInterface',
            'Antarctica\LaravelTokenAuth\Service\Token\TokenServiceJwtAuth'
        );
        $this->app->bind(
            'Antarctica\LaravelTokenAuth\Service\TokenUser\TokenUserServiceInterface',
            'Antarctica\LaravelTokenAuth\Service\TokenUser\TokenUserService'
        );
        $this->app->bind(
            'Antarctica\LaravelTokenAuth\Repository\User\UserRepositoryInterface',
            Config::get('laravel-token-auth::user_repository')
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }
}