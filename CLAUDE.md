# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Filament v4/v5 plugin package that provides character counter functionality for TextInput, Textarea, and RichEditor form fields. The package extends Filament's core form components to add real-time character counting with customizable display options. It supports both Filament v4 (Livewire v3) and Filament v5 (Livewire v4).

## Package Information

- **Package Name**: schmeits/filament-character-counter
- **Namespace**: Schmeits\FilamentCharacterCounter
- **Filament Version**: 4.x - 5.x
- **PHP Version**: ^8.2
- **Type**: Laravel package / Filament plugin

## Key Commands

### Testing
```bash
composer test              # Run all tests with Pest
composer test-coverage     # Run tests with coverage report
vendor/bin/pest           # Run Pest directly
```

### Code Quality
```bash
composer format           # Format code with Laravel Pint
composer analyse          # Run PHPStan static analysis
vendor/bin/pint          # Run Pint directly
vendor/bin/phpstan analyse  # Run PHPStan directly
```

### Assets
The stylesheet `resources/dist/filament-character-counter.css` is plain CSS and is edited by hand. There is no npm toolchain or build step.

## Architecture

### Component Structure

The package uses a trait-based architecture to extend Filament's core form components:

**Core Components** (in `src/Forms/Components/`):
- `TextInput.php` - Extends `\Filament\Forms\Components\TextInput`
- `Textarea.php` - Extends `\Filament\Forms\Components\Textarea`
- `RichEditor.php` - Extends `\Filament\Forms\Components\RichEditor`

**Traits** (in `src/Forms/Concerns/`):
- `HasCharacterLimit` - Adds character limit functionality, automatically uses `maxLength` if no explicit limit is set
- `CanBeShownInsideControl` - Allows counter to be displayed inside the form control

Each component class is minimal, simply extending the base Filament component and applying the necessary traits. TextInput and Textarea use their own copy of the Filament view (`protected string $view`) so the counter can sit inside the input. RichEditor does not: it overrides `toHtml()` and wraps Filament's own rendering in `resources/views/rich-editor.blade.php`.

### Service Provider

`FilamentCharacterCounterServiceProvider` handles:
- Package registration via Spatie's `laravel-package-tools`
- Asset registration (CSS) using Filament's asset system
- Configuration, translations, and views publishing

### Views

Located in `resources/views/`:
- `text-input.blade.php` - TextInput component view
- `textarea.blade.php` - Textarea component view
- `rich-editor.blade.php` - RichEditor component view
- `partials/` - Shared view components

### Styling

- Source CSS: `resources/css/index.css`
- Compiled CSS: `resources/dist/filament-character-counter.css`
- Uses Tailwind CSS v4 with Filament-specific purging
- CSS is registered as a Filament asset in the service provider

### Testing

Uses Pest PHP for testing:
- Test namespace: `Schmeits\FilamentCharacterCounter\Tests`
- Base test class: `TestCase.php` (extends Orchestra Testbench)
- Architecture tests: `ArchTest.php`
- Component tests: `ComponentTest.php`
- Test fixtures: `tests/Fixtures/Livewire.php`

## Development Notes

### Code Style

- Follows PSR-2 coding standards (enforced by Laravel Pint)
- Pint preset: `laravel` with custom rules in `pint.json`
- Uses single spacing for concatenation and type hints

### Version Support

| Plugin Version | Filament Version | PHP Version |
|----------------|------------------|-------------|
| 1.x            | 3.2              | ^8.1        |
| 4.x            | 4.x              | ^8.2        |
| 5.x            | 4.x - 5.x        | ^8.2        |

Current version (5.x) supports both Filament 4.x and 5.x. Filament v5 is primarily a Livewire v4 upgrade with no Filament-specific breaking changes.

### Trait Design Pattern

When extending functionality, follow the existing pattern:
1. Create a trait in `src/Forms/Concerns/` for reusable logic
2. Keep component classes minimal - just extend base class and use traits
3. Define custom view with `protected string $view`

### Translation Keys

Translations are in `resources/lang/`:
- `character_seperator` - Separator between current and max characters (default: ' / ')
- `character_label` - Label text for characters (default: 'characters')

## Package Dependencies

**Production**:
- `filament/filament` ^4.0.0
- `filament/forms` ^4.0.0
- `spatie/laravel-package-tools` ^1.19.0

**Development**:
- Pest PHP for testing
- PHPStan with Laravel and PHPUnit extensions
- Laravel Pint for code formatting
- Orchestra Testbench for package testing

## Important Constraints

- This is a package, not a Laravel application - no database migrations or application-level features
- No development server needed - this is installed as a dependency in Filament applications
- Always maintain backward compatibility within major versions
- Follow SemVer strictly
- All pull requests must include tests