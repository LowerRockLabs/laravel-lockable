# Laravel Lockable provides traits to allow for models to be locked

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lowerrocklabs/laravel-lockable.svg?style=flat-square)](https://packagist.org/packages/lowerrocklabs/laravel-lockable)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/lowerrocklabs/laravel-lockable/run-tests?label=tests)](https://github.com/lowerrocklabs/laravel-lockable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/lowerrocklabs/laravel-lockable/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/lowerrocklabs/laravel-lockable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
<a href="https://codeclimate.com/github/LowerRockLabs/laravel-lockable/maintainability"><img src="https://api.codeclimate.com/v1/badges/de42e3f05d0cb1629c8d/maintainability" /></a>
<a href="https://codeclimate.com/github/LowerRockLabs/laravel-lockable/test_coverage"><img src="https://api.codeclimate.com/v1/badges/de42e3f05d0cb1629c8d/test_coverage" /></a>
[![Total Downloads](https://img.shields.io/packagist/dt/lowerrocklabs/laravel-lockable.svg?style=flat-square)](https://packagist.org/packages/lowerrocklabs/laravel-lockable)

This model allows for on-demand locking of models.  You can integrate this with your permissions methodology of choice, or leave it stand-alone.  This package allows you to determine whether a particular instance of a model is Locked or Not.  Or it will independently prevent updating of a model instance.

## Installation
> **Requires [PHP 7.3+ or 8.0+] and [Laravel 8.x or 9.x] (https://laravel.com/docs/8.x/releases or https://laravel.com/docs/9.x/releases)**

You can install the package via composer:

```bash
composer require lowerrocklabs/laravel-lockable
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-lockable-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-lockable-config"
```

This is the contents of the published config file, which controls the global lock duration, this is customisable on a per-model basis (see below).

```php
return [
    // Name of the Table containing the locks
    'locks_table' => 'model_locks',
    
     // Name of the Table containing the lock watchers
    'lock_watchers_table' => 'model_lock_watchers',
    
    // Enable retrieval of lock status on retrieving a model
    'get_locked_on_retrieve' => true,
    
    // Prevent updating if a model is locked by another user
    'prevent_updating' => true,

    // Time in Seconds For Lock To Persist
    'duration' => '3600',
];
```


## Usage

#### Model Configuration
In the Model(s) that you wish to be lockable, add the IsLockable Trait

```php
use LowerRockLabs\Lockable\Traits\IsLockable;
```

and then set the Trait

```php
use IsLockable;
```

#### In Your Component/Controller
Then use the acquireLock function to attempt to acquire a lock on the model, it will return false if there is an existing lock.
```php
acquireLock()
```

You can override the existing lock by calling the releaseLock() function before acquireLock(), if you are using a permissions-based approach, it is suggested that you restrict this to approriate users.
```php
releaseLock()
```

You can tell if a lock exists as follows, this will acquire the lock if one does not exist.
```php
isLocked()
```

You can send a Broadcast to the user holding the lock with the following
```php
requestLock()
```

#### Lock Duration
You can override the default Lock Duration (in seconds) which is taken from the configuration on a per-model basis by setting the following in your Model, for example, the following would limit the duration of a lock to 600 seconds.

```php
public $modelLockDuration = "600";
```

Locks will clear when the Duration has expired, and an attempt is made to access the Model, or you can call the commands below, or add them to a schedule (as you see fit).

#### Commands

**To Flush Expired Locks**
```php 
php artisan locks:flushexpired
```

**To Flush All Locks**
```php 
php artisan locks:flushall
```


#### Events
Two Events will be fired during the Lock process, that can be used to fire Notifications or Logs if desired
**On Model Locking**
```php
LowerRockLabs\Lockable\Events\ModelWasLocked;
```
and
**On Model UnLocking**
```php
LowerRockLabs\Lockable\Events\ModelWasUnLocked;
```

An additional event will be fired when a user requests the release of the lock
and
**On Model UnLocking**
```php
LowerRockLabs\Lockable\Events\ModelUnlockRequested;
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Joe](https://github.com/LowerRockLabs)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
