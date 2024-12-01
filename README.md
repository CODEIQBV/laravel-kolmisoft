
# Integration between Laravel and Kolmisoft MOR

[![Latest Version on Packagist](https://img.shields.io/packagist/v/codeiqbv/laravel-kolmisoft.svg?style=flat-square)](https://packagist.org/packages/codeiqbv/laravel-kolmisoft)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/codeiqbv/laravel-kolmisoft/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/codeiqbv/laravel-kolmisoft/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/codeiqbv/laravel-kolmisoft/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/codeiqbv/laravel-kolmisoft/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/codeiqbv/laravel-kolmisoft.svg?style=flat-square)](https://packagist.org/packages/codeiqbv/laravel-kolmisoft)

This package integrates Laravel applications with Kolmisoft MOR, providing a simple and flexible way to interact with MOR's APIs.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/laravel-kolmisoft.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/laravel-kolmisoft)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

## Installation

You can install the package via composer:

```bash
composer require codeiqbv/laravel-kolmisoft
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-kolmisoft-config"
```

This is the contents of the published config file:

```php
return [
    'username' => env('KOLMISOFT_USERNAME'),
    'password' => env('KOLMISOFT_PASSWORD'),
    'api_url' => env('KOLMISOFT_API_URL'),
    'auth_key' => env('KOLMISOFT_AUTH_KEY'),
    'use_hash' => true,
];
```

## Usage

Example usage:

```php
$kolmisoft = new CODEIQBV\Kolmisoft\Api\Call();
$response = $kolmisoft->getUserCalls([
    's_user' => 123,
    'period_start' => strtotime('2024-01-01 00:00'),
    'period_end' => strtotime('2024-01-31 23:59'),
]);
```

For more details, check out the [WIKI Documentation](./WIKI/README.md).

## Requirements

- PHP version 8 or higher

## Questions or Issues

If you encounter any questions or issues, please create an issue on [GitHub](https://github.com/codeiqbv/laravel-kolmisoft/issues). We aim to fix bugs within 24 to 48 hours.

## Contributing

This package is community-managed, and we welcome contributions from everyone. Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Official Documentation

The official documentation of Kolmisoft can be found on their [Wiki](https://wiki.kolmisoft.com).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
