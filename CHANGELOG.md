# Changelog

All notable changes to `filament-character-counter` will be documented in this file.

## v5.0.0 - Filament v5 Support & RichEditor Fixes - 2026-01-30

### What's New

#### ✨ Added

- **Filament v5 Support**: Full compatibility with Filament v5 (Livewire v4) alongside v4
- **Comprehensive Tests**: Unit tests for all component types (TextInput, Textarea, RichEditor)
- **Better Spacing**: Proper spacing for RichEditor character counter

#### 🐛 Fixed

**RichEditor Character Counting** - Complete refactor:

- Fixed JavaScript initialization errors
- Added all required Filament v4/v5 parameters with safe defaults
- Switched to direct DOM text content reading for accuracy
- Implemented Livewire.hook('commit') for reactive updates
- Character counter now updates in real-time while typing

**HasCharacterLimit Trait**:

- Automatically enforces HTML maxlength attribute
- Prevents paste overflow beyond character limit
- Ensures consistency between visual counter and enforcement

#### 🔧 Technical Details

- RichEditor uses `$el.querySelector()` for DOM-based character counting
- Added `.fi-fo-rich-editor-wrapper` CSS class for proper spacing
- Textarea maxLength enforcement with Alpine.js watcher
- Views compatible with both Filament 4 and 5

**CI/CD Improvements**:

- GitHub Actions test matrix supports both Laravel 11 and 12
- Conditional dev dependency installation via matrix parameters
- Laravel 11: installs larastan ^2.9 and pest-plugin-laravel ^3.0
- Laravel 12: skips incompatible packages (not yet supported by these tools)
- Updated testbench constraint to support both versions (^9.0 || ^10.0)
- PHPStan workflow updated to PHP 8.2 minimum requirement

### Testing

Tested and working in:

- ✅ Filament v4 (Livewire v3)
- ✅ Filament v5 (Livewire v4)

All components (TextInput, Textarea, RichEditor) working correctly in both versions.

## Unreleased - 2026-01-30

### Fixed

- **RichEditor character counting**: Completely refactored RichEditor character counter implementation
  
  - Fixed JavaScript errors during component initialization (missing parameters for richEditorFormComponent)
  - Added all required Filament v4/v5 parameters (acceptedFileTypes, floatingToolbars, mentions, linkProtocols, etc.) with safe defaults
  - Changed from trying to access TipTap editor instance to directly reading DOM element text content
  - Character counter now uses `$el.querySelector('.fi-fo-rich-editor-content')` to get text content
  - Implemented Livewire.hook('commit') for reactive updates + periodic polling (500ms) as fallback
  - RichEditor now properly counts characters in real-time while typing
  
- **HasCharacterLimit trait**: Made `characterLimit()` automatically call `maxLength()` to enforce HTML maxlength attribute
  
  - Prevents users from pasting or entering more characters than the limit
  - Ensures consistency between visual counter and actual enforcement
  

### Technical Details

- Session timestamp: 2026-01-30
- Estimated time: ~2 hours debugging and implementation
- Components tested: TextInput ✓, Textarea ✓, RichEditor ✓
- Test environments: Filament v4 and v5 test projects

## 5.0.0 - 2026-01-30

Added Filament v5 support (Livewire v4 compatible)

- Added: Support for Filament v5 alongside Filament v4
- Updated: Composer dependencies to accept both `^4.0` and `^5.0` versions
- Fixed: Updated test fixtures for Filament v5 API changes (`Filament\Forms\Form` → `Filament\Schemas\Schema`)
- Fixed: Textarea `maxLength` now properly enforces limit even when pasting or programmatic updates (Alpine.js watcher added)
- Added: Comprehensive unit tests for all component types (TextInput, Textarea, RichEditor)
- Note: Views are compatible with both Filament 4 and 5 - no changes required to views
- Note: Character counter functionality remains identical across both versions

## 4.0.0-beta2 - 2025-06-18

Updated readme

## 4.0.0-beta1 - 2025-06-18

First beta release for Filament 4

## 1.3.4 - 2025-04-28

New : Added French translation (thanks to @tgeorgel)

## 1.3.3 - 2025-04-16

Fix: The showInsideControl was missing from the Textarea (Thanks @CharlieEtienne for the PR)

## 1.3.2 - 2025-02-10

Updated:

- updated rich-editor.blade.php
  
  * Fixing async alpine not loading in spa
  * Add disableGrammarly Method
  * Use Heroicons for rich editor and Markdown editor
  * Add CSS class to rich editor editor
  
- updated textarea.blade.php
  
  * Fixing async alpine not loading in spa
  

## 1.3.1 - 2024-07-15

Changed: Updated README with new RichEditor support

## 1.3.0 - 2024-07-15

New: Added Arabic language (thanks to Omar @devOMAR-2)
New: Added RichEditor support

## 1.2.4 - 2024-07-03

New: Added German translation (thanks to @dissto)
Fix: Fixed a translation key (thanks to @dissto)

## 1.2.3 - 2024-05-29

Fix: Respect maxLength in combination with characterLimit

## 1.2.2 - 2024-05-24

fix: typo in translation for the dutch variant of characters

## 1.2.1 - 2024-05-14

Fixed a bug if you use ->live() on the Fields, the counter would stutter and the character count was not correct.

## 1.2.0 - 2024-04-26

Added the option to show the counter in the component (default is outside the component)

## 1.1.0 - 2024-04-25

Fix: Placed helper in correct section

## 1.0.0 - 2024-04-24

- initial release
