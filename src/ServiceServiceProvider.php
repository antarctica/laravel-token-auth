<?php

namespace Antarctica\LaravelTokenAuth;

use Illuminate\Support\ServiceProvider;

class ServiceServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind('Antarctica\LaravelTokenAuth\Service\Token\TokenServiceInterface', 'Antarctica\LaravelTokenAuth\Service\Token\TokenServiceJwtAuth');
        $this->app->bind('Antarctica\LaravelTokenAuth\Service\TokenUser\TokenUserServiceInterface', 'Antarctica\LaravelTokenAuth\Service\TokenUser\TokenUserService');
    }
}