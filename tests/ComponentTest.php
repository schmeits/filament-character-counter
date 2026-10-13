<?php

declare(strict_types=1);

/*
 * tests/ComponentTest.php
 *
 * The PHP API of the fields: characterLimit() is a soft limit that only drives
 * the counter, maxLength() stays the hard limit that the browser and
 * validation enforce.
 */

use Schmeits\FilamentCharacterCounter\Forms\Components\RichEditor;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

dataset('fields', [
    'text input' => fn () => TextInput::make('test'),
    'textarea' => fn () => Textarea::make('test'),
    'rich editor' => fn () => RichEditor::make('test'),
]);

it('uses the character limit for the counter', function ($field) {
    expect($field->characterLimit(100)->getCharacterLimit())->toBe(100);
})->with('fields');

it('does not set maxLength from the character limit', function ($field) {
    expect($field->characterLimit(100)->getMaxLength())->toBeNull();
})->with('fields');

it('accepts a closure as character limit', function ($field) {
    expect($field->characterLimit(fn (): int => 42)->getCharacterLimit())->toBe(42);
})->with('fields');

it('returns 0 when there is no limit at all', function ($field) {
    expect($field->getCharacterLimit())->toBe(0);
})->with('fields');

it('treats a null, zero or negative character limit as no limit', function ($limit) {
    expect(TextInput::make('test')->characterLimit($limit)->getCharacterLimit())->toBe(0);
})->with([
    'null' => null,
    'closure returning null' => fn () => fn (): ?int => null,
    'zero' => 0,
    'negative' => -5,
]);

it('falls back to maxLength when no character limit is set', function () {
    $field = TextInput::make('test')->maxLength(200);

    expect($field->getCharacterLimit())->toBe(200)
        ->and($field->getMaxLength())->toBe(200);
});

it('falls back to maxLength when the character limit is null', function () {
    expect(Textarea::make('test')->maxLength(200)->characterLimit(null)->getCharacterLimit())->toBe(200);
});

it('lets the character limit win from maxLength, in either order', function () {
    $before = TextInput::make('test')->maxLength(200)->characterLimit(150);
    $after = TextInput::make('test')->characterLimit(150)->maxLength(200);

    expect($before->getCharacterLimit())->toBe(150)
        ->and($before->getMaxLength())->toBe(200)
        ->and($after->getCharacterLimit())->toBe(150)
        ->and($after->getMaxLength())->toBe(200);
});

it('shows the counter by default', function ($field) {
    expect($field->shouldShowCharacterCounter())->toBeTrue();
})->with('fields');

it('hides the counter when switched off, also via a closure', function ($field) {
    expect($field->showCharacterCounter(false)->shouldShowCharacterCounter())->toBeFalse()
        ->and($field->showCharacterCounter(fn (): bool => false)->shouldShowCharacterCounter())->toBeFalse();
})->with('fields');

it('keeps getShowCharacterCounter() working for existing code', function () {
    expect(TextInput::make('test')->showCharacterCounter(false)->getShowCharacterCounter())->toBeFalse();
});

it('can show the counter inside the control', function () {
    expect(TextInput::make('test')->isShownInsideControl())->toBeFalse()
        ->and(TextInput::make('test')->showInsideControl()->isShownInsideControl())->toBeTrue()
        ->and(Textarea::make('test')->showInsideControl(fn (): bool => true)->isShownInsideControl())->toBeTrue();
});
