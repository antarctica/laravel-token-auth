# Laravel Token Auth

Enables use of API tokens as a form of stateless authentication within Laravel.

This package is designed not to rely on any particular Token or Token Blacklist implementation, however by default the [tymondesigns/jwt-auth](https://github.com/tymondesigns/jwt-auth) package is used to provide a JWT based Token implementation and the `antarctica/laravel-token-blacklist` package is used to provided a default Token Blacklist implementation.

It is possible to provide your own implementations for managing tokens and/or blacklisting them. See the _custom implementations_ section for details.

## Installing

Require this package in your `composer.json` file:

```json
{
    "require": {
        "antarctica/laravel-token-auth": "0.1.*"
    }
}
```

Run `composer update`.

Register the service provider in the `providers` array of your `app/config/app.php` file:

```php
'providers' => array(
	Antarctica\LaravelTokenAuth\LaravelTokenAuthServiceProvider,
)
```

This package uses a Repository through which users can be retrieved. There is NO default implementation for this repository included in this package. You MUST therefore provide an implementation that implements the provided interface through this package's config file.

To publish the config file run:

```shell
php artisan config:publish antarctica/laravel-token-auth
```
    
Then edit the `user_repository` key.

## Usage

To support both standard session based and token based authentication this package provides an `auth.combined` filter.

To enable this filter add the following to your `app/filters.php` file:

```php
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
```

To use the filter on a route:

```php
Route::get('/secret', array('before' => 'auth.combined', function()
{
    	return Response::json(['message' => 'Yay you get to know the secret']);
}));
```

## Contributing

This project welcomes contributions, see `CONTRIBUTING` for our general policy.

## Developing

To aid development and keep your local computer clean, a VM (managed by Vagrant) is used to create an isolated environment with all necessary tools/libraries available.

### Requirements

* Mac OS X
* Ansible `brew install ansible`
* [VMware Fusion](http://vmware.com/fusion)
* [Vagrant](http://vagrantup.com) `brew cask install vmware-fusion vagrant`
* [Host manager](https://github.com/smdahlen/vagrant-hostmanager) and [Vagrant VMware](http://www.vagrantup.com/vmware) plugins `vagrant plugin install vagrant-hostmanager && vagrant plugin install vagrant-vmware-fusion`
* You have a private key `id_rsa` and public key `id_rsa.pub` in `~/.ssh/`
* You have an entry like [1] in your `~/.ssh/config`

[1] SSH config entry

```shell
Host bslweb-*
    ForwardAgent yes
    User app
    IdentityFile ~/.ssh/id_rsa
    Port 22
```

### Provisioning development VM

VMs are managed using Vagrant and configured by Ansible.

```shell
$ git clone ssh://git@stash.ceh.ac.uk:7999/basweb/laravel-token-auth.git
$ cp ~/.ssh/id_rsa.pub laravel-token-auth/provisioning/public_keys/
$ cd laravel-token-auth
$ ./armadillo_standin.sh

$ vagrant up

$ ssh bslweb-laravel-token-auth-dev-node1
$ cd /app

$ composer install

$ logout
```

### Committing changes

The [Git flow](https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow) workflow is used to manage development of this package.

Discrete changes should be made within *feature* branches, created from and merged back into *develop* (where small one-line changes may be made directly).

When ready to release a set of features/changes create a *release* branch from *develop*, update documentation as required and merge into *master* with a tagged, [semantic version](http://semver.org/) (e.g. `v1.2.3`).

After releases the *master* branch should be merged with *develop* to restart the process. High impact bugs can be addressed in *hotfix* branches, created from and merged into *master* directly (and then into *develop*).

### Issue tracking

Issues, bugs, improvements, questions, suggestions and other tasks related to this package are managed through the BAS Web & Applications Team Jira project ([BASWEB](https://jira.ceh.ac.uk/browse/BASWEB)).

### Clean up

To remove the development VM:

```shell
vagrant halt
vagrant destroy
```

The `laravel-token-auth` directory can then be safely deleted as normal.

## License

Copyright 2015 NERC BAS. Licensed under the MIT license, see `LICENSE` for details.
