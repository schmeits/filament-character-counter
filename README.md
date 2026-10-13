# Filament Character Counter

[![Latest Version on Packagist](https://img.shields.io/packagist/v/schmeits/filament-character-counter.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-character-counter)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/schmeits/filament-character-counter/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/schmeits/filament-character-counter/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/schmeits/filament-character-counter.svg?style=flat-square)](https://packagist.org/packages/schmeits/filament-character-counter)

A character counter for the TextInput, Textarea and RichEditor fields of [Filament](https://filamentphp.com/) 4 and 5.

![example-screenshot.png](https://github.com/schmeits/filament-character-counter/raw/main/docs-assets/screenshots/example-screenshot.png)

## Compatibility

| Plugin version | Filament | Laravel      | PHP                          |
|----------------|----------|--------------|------------------------------|
| 1.x            | 3.2      | 10, 11       | 8.1 or newer                 |
| 5.x            | 4, 5     | 12, 13       | 8.2 or newer (8.3 for L13)   |

## Installation

```bash
composer require schmeits/filament-character-counter:"^5.0"
```

For Filament 3.2, use version `^1.0`.

The package ships a small stylesheet that is registered with Filament. After installing or updating, publish Filament's assets as usual:

```bash
php artisan filament:assets
```

## Usage

```php
use Schmeits\FilamentCharacterCounter\Forms\Components\RichEditor;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

TextInput::make('title')
    ->characterLimit(50),

Textarea::make('description')
    ->characterLimit(155),

RichEditor::make('content')
    ->characterLimit(155),
```

### Soft limit and hard limit

- `characterLimit()` is a **soft limit**: it only drives the counter. The user can type past it; the counter then says how many characters are too many, for example "(12 too many)".
- `maxLength()` is a **hard limit**: the browser stops typing at that length and Laravel validates it. Without a `characterLimit()`, the counter uses `maxLength()`.

```php
// Counter shows 0 / 50, typing stops at 50.
TextInput::make('title')
    ->maxLength(50),

// Counter warns past 150, typing stops at 200.
TextInput::make('title')
    ->characterLimit(150)
    ->maxLength(200),
```

Both accept a closure. Without any limit the counter only shows the number of characters.

### Counter inside the field

TextInput and Textarea can show the counter inside the input:

```php
TextInput::make('title')
    ->showInsideControl()
    ->characterLimit(50),
```

### Hiding the counter

```php
use Filament\Schemas\Components\Utilities\Get;

// Never show the counter on this field
TextInput::make('slug')
    ->showCharacterCounter(false)
    ->characterLimit(100),

// Show it only when another field is on
Textarea::make('bio')
    ->showCharacterCounter(fn (Get $get): bool => (bool) $get('is_public'))
    ->characterLimit(255),
```

### How characters are counted

The counter counts per character (code point), the same way Laravel's `max:` rule does, so an emoji counts as one. It follows typing, pasting, autofill and changes made from PHP, such as `$set()` or resetting the form.

### Accessibility

Going over the limit is shown in words and in bold, not by colour alone, and is announced to screen readers. The input is linked to its counter with `aria-describedby`.

## Configuration

Publish the config file:

```bash
php artisan vendor:publish --tag="filament-character-counter-config"
```

```php
return [
    // Set to false to leave out the package stylesheet and style the counter yourself.
    'load_css' => true,
];
```

The counter uses these CSS classes: `fi-input-charcounter` for the counter and `fi-input-charcounter-exceeded` when the limit is passed.

## Translations

Publish the translations:

```bash
php artisan vendor:publish --tag="filament-character-counter-translations"
```

```php
return [
    'character_separator' => ' / ',
    'character_label' => 'characters',
    'characters_over' => '(:count too many)',
];
```

## Upgrading to 5.2

- The RichEditor is no longer a copy of Filament's view. Filament renders the editor itself and the counter is placed around it, so features such as file attachments and mentions work again. If you published `rich-editor.blade.php`, delete it and publish again.
- The CSS class for an exceeded limit changed from `warning` to `fi-input-charcounter-exceeded`.
- The old translation key `character_seperator` is no longer read. Use `character_separator`.
- `getShowCharacterCounter()` is deprecated in favour of `shouldShowCharacterCounter()`.

## Testing

```bash
composer test
composer analyse
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Tally Schmeits](https://github.com/schmeits)
- Inspired by [the package](https://github.com/sweebee/filament-char-counter) by Wiebe Nieuwenhuis
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
