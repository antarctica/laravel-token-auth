# Laravel Token Auth

Enables use of API tokens as a form of stateless authentication within Laravel.

More information and proper README soon.

## Installing

Require this package in your `composer.json` file:

Note: This package depends on the `antarctica/auth-token-blacklist` package, which in term relies on this package.
Becaue of this circular dependency Composer has an issue when resolving dependencies. Until a work around is fixed,
you will need to manually specify both this package and the `antarctica/auth-token-blacklist` as shown.

    {
        "require-dev": {
            "antarctica/laravel-token-auth": "dev-develop",
            "antarctica/laravel-token-blacklist": "dev-develop"
        }
    }
    
Note: Until these packages are released publicly, or an internal package server is created you will need to help
Composer find these packages by adding the following configuration (for both packages as described previously):

    "repositories": [
        {
            "type": "vcs",
            "url": "ssh://git@stash.ceh.ac.uk:7999/~felnne/laravel-token-auth.git"
        },
        {
            "type": "vcs",
            "url": "ssh://git@stash.ceh.ac.uk:7999/~felnne/laravel-token-blacklist.git"
        }
    ],

Register the service provider in the `providers` array of your `app/config/app.php` file:

    Antarctica\LaravelTokenAuth\LaravelTokenAuthServiceProvider,

This package uses a Repository through which users can be retrieved. There is NO default implementation for this
repository included in this package. You MUST therefore provide an implementation that implements the provided
interface through this package's config file.

To publish the config file run:

    php artisan config:publish antarctica/laravel-token-auth
    
Then edit the `user_repository` key.

## Usage

To support both standard session based and token based authentication this package provides an `auth.combined`
filter. To enable this filter add the following to your `app/filters.php` file:

    /*
    |--------------------------------------------------------------------------
    | Custom Authentication Filters
    |--------------------------------------------------------------------------
    |
    | The "combined" filter is a custom filter which allows session and token
    | based authentication to be combined. This means a user can be authenticated
    | using either an active session (i.e. being logged in) or by providing a
    | token (i.e. using the Authorization header).
    |
    */

    Route::filter('auth.combined', 'Antarctica\LaravelTokenAuth\Filter\AuthFilter');

To use the filter on a route:

    Route::get('/secret', array('before' => 'auth.combined', function()
    {
        return Response::json(['message' => 'Yay you get to know the secret']);
    }));
