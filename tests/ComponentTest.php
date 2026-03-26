<?php

/**
 * Component tests for the filament-character-counter package.
 *
 * Tests verify that characterLimit() acts as a soft limit (visual only)
 * and maxLength() acts as a hard limit (HTML enforcement + validation).
 */

use Schmeits\FilamentCharacterCounter\Forms\Components\RichEditor;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

it('can test', function () {
    expect(true)->toBeTrue();
});

// --- characterLimit (soft limit) ---

it('can create text input with character limit', function () {
    $field = TextInput::make('test')
        ->characterLimit(100);

    expect($field->getCharacterLimit())->toBe(100);
});

it('can create textarea with character limit', function () {
    $field = Textarea::make('description')
        ->characterLimit(500);

    expect($field->getCharacterLimit())->toBe(500);
});

it('can create rich editor with character limit', function () {
    $field = RichEditor::make('content')
        ->characterLimit(1000);

    expect($field->getCharacterLimit())->toBe(1000);
});

it('characterLimit does not set maxLength on text input', function () {
    $field = TextInput::make('test')
        ->characterLimit(100);

    expect($field->getCharacterLimit())->toBe(100)
        ->and($field->getMaxLength())->toBeNull();
});

it('characterLimit does not set maxLength on textarea', function () {
    $field = Textarea::make('test')
        ->characterLimit(100);

    expect($field->getCharacterLimit())->toBe(100)
        ->and($field->getMaxLength())->toBeNull();
});

it('characterLimit does not set maxLength on rich editor', function () {
    $field = RichEditor::make('test')
        ->characterLimit(100);

    expect($field->getCharacterLimit())->toBe(100)
        ->and($field->getMaxLength())->toBeNull();
});

// --- maxLength (hard limit) ---

it('uses maxLength when characterLimit is not set', function () {
    $field = TextInput::make('test')
        ->maxLength(200);

    expect($field->getCharacterLimit())->toBe(200);
});

it('maxLength sets both character limit and maxLength on text input', function () {
    $field = TextInput::make('test')
        ->maxLength(200);

    expect($field->getCharacterLimit())->toBe(200)
        ->and($field->getMaxLength())->toBe(200);
});

it('maxLength sets both character limit and maxLength on textarea', function () {
    $field = Textarea::make('test')
        ->maxLength(200);

    expect($field->getCharacterLimit())->toBe(200)
        ->and($field->getMaxLength())->toBe(200);
});

// --- characterLimit + maxLength combined ---

it('characterLimit overrides maxLength for counter display', function () {
    $field = TextInput::make('test')
        ->maxLength(200)
        ->characterLimit(150);

    // Counter shows the characterLimit value
    expect($field->getCharacterLimit())->toBe(150)
        // But HTML maxLength stays at 200 (hard enforcement)
        ->and($field->getMaxLength())->toBe(200);
});

it('maxLength after characterLimit does not override the counter', function () {
    $field = TextInput::make('test')
        ->characterLimit(150)
        ->maxLength(200);

    // Counter still shows characterLimit
    expect($field->getCharacterLimit())->toBe(150)
        // Hard limit is maxLength
        ->and($field->getMaxLength())->toBe(200);
});
