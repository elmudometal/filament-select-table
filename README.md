# This package is an extension of select to allow selecting from a table

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elmudo-dev/filament-select-table.svg?style=flat-square)](https://packagist.org/packages/elmudo-dev/filament-select-table)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/elmudo-dev/filament-select-table/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/elmudometal/filament-select-table/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/elmudo-dev/filament-select-table/fix-php-code-styling.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/elmudometal/filament-select-table/actions?query=workflow%3A"Fix+PHP+code+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elmudo-dev/filament-select-table.svg?style=flat-square)](https://packagist.org/packages/elmudo-dev/filament-select-table)



This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require elmudo-dev/filament-select-table
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-select-table-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-select-table-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-select-table-views"
```

This is the contents of the published config file:

```php
return [
];
```

## Usage

```php
$filamentSelectTable = new ElmudoDev\FilamentSelectTable();
echo $filamentSelectTable->echoPhrase('Hello, ElmudoDev!');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Hernan Soto](https://github.com/elmudometal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.