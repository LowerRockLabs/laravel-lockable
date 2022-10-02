# Laravel Lockable provides traits to allow for models to be locked

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lowerrocklabs/laravel-lockable.svg?style=flat-square)](https://packagist.org/packages/lowerrocklabs/laravel-lockable)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/lowerrocklabs/laravel-lockable/run-tests?label=tests)](https://github.com/lowerrocklabs/laravel-lockable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/lowerrocklabs/laravel-lockable/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/lowerrocklabs/laravel-lockable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/lowerrocklabs/laravel-lockable.svg?style=flat-square)](https://packagist.org/packages/lowerrocklabs/laravel-lockable)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-lockable.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-lockable)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

## Installation

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

This is the contents of the published config file:

```php
return [
 'duration' => '3600',
];
```


## Usage

In the Model(s) that you wish to be lockable, add the IsLockable Trait

```php
use LowerRockLabs\Lockable\Traits\IsLockable;
```

and then set the Trait

```php
use IsLockable;
```

You can override the default Lock Duration (in seconds) from the configuration on a per-model basis by setting the following in your Model, for example
```php
public $modelLockDuration = "600";
```

Then use the acquireLock function to attempt to acquire a lock on the model, it will return false if there is an existing lock.
```php
acquireLock()
```

You can override the existing lock by calling
```php
releaseLock()
```

Locks will clear when the Duration has expired, and an attempt is made to access the Model.

**Events**
Two Events will be fired during the Lock process, that can be used to fire Notifications or Logs if desired
```php
LowerRockLabs\Lockable\Events\ModelWasLocked;
```
and
```php
LowerRockLabs\Lockable\Events\ModelWasUnLocked;
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
