# Filament Character Counter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/schmeits/filament-character-counter.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-character-counter)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/schmeits/filament-character-counter/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/schmeits/filament-character-counter/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/schmeits/filament-character-counter.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-character-counter)

This is a [Filament](https://filamentphp.com/) character counter TextField, Textarea and RichEditor form field for Filament v3.2 

![example-screenshot.png](https://github.com/schmeits/filament-character-counter/raw/main/docs-assets/screenshots/example-screenshot.png)


## Installation

You can install the package via composer:

```bash
composer require schmeits/filament-character-counter
```

You can publish the translations file with:

```bash
php artisan vendor:publish --tag="filament-character-counter-translations"
```

This is the contents of the published translation file:

```php
return [
    'character_seperator' => ' / ',
    'character_label' => 'characters',
];
```

## Usage

```php
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;

TextInput::make('title')
    ->characterLimit(50),

Textarea::make('description')
    ->characterLimit(155),

RichEditor::make('content')
    ->characterLimit(155),
```

maxLength can also be used to limit the maximum number of characters, there is no need to specify the characterLimit as well.
```php
TextInput::make('title')
    ->maxLength(50),
```

As an additional option, you can place the counter within your component using:

```php
TextInput::make('title')
    ->showInsideControl(true)
    ->characterLimit(50),
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

- [Tally Schmeits](https://github.com/schmeits)
- Inspired on [the package](https://github.com/sweebee/filament-char-counter) by Wiebe Nieuwenhuis
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
