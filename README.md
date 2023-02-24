# A package to easily make use of Streamline Icons in your Laravel Blade views.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/erikgaal/blade-streamline-icons.svg?style=flat-square)](https://packagist.org/packages/erikgaal/blade-streamline-icons)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/erikgaal/blade-streamline-icons/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/erikgaal/blade-streamline-icons/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/erikgaal/blade-streamline-icons/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/erikgaal/blade-streamline-icons/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/erikgaal/blade-streamline-icons.svg?style=flat-square)](https://packagist.org/packages/erikgaal/blade-streamline-icons)

## Installation

You can install the package via composer:

```bash
composer require erikgaal/blade-streamline-icons
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="blade-streamline-icons-config"
```

This is the contents of the published config file:

```php
return [
    'prefix' => 'streamline',

    'path' => resource_path('icons/streamline'),

    /*
     * Define any aliases for families here.
     */
    'family_aliases' => [
        'core-line' => 'streamline-mini-line',
        'core' => 'core-free',
        'flex' => 'flex-free',
        'plump' => 'plump-free',
        'sharp' => 'sharp-free',
    ]
];
```

You must login to the Streamline API with:

```bash
php artisan streamline-icons:login
```

## Usage

Retrieve icons from Streamline with:

```bash
php artisan streamline-icons:save core-line interface-home-1
#                                     ▲            ▲
#                                     │            └──── icon
#                                     └──── family                    

# Alternatively, output in terminal.
php artisan streamline-icons:get core-line interface-home-1
```

Icons can be used as self-closing Blade components which will be compiled to SVG icons:

```blade
<x-streamline-core-line-interface-home-1/>
```

You can also pass classes to your icon components:
```blade
<x-streamline-core-line-interface-home-1 class="w-6 h-6 text-gray-500"/>
```

And even use inline styles:
```blade
<x-streamline-core-line-interface-home-1 style="color: #555"/>
```

Or use the @svg directive:
```blade
@svg('streamline-core-line-interface-home-1', 'w-6 h-6', ['style' => 'color: #555'])
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Erik Gaal](https://github.com/erikgaal)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
