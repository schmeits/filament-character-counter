<?php

it('can test', function () {
    expect(true)->toBeTrue();
});

it('can create text input with character limit', function () {
    $field = \Schmeits\FilamentCharacterCounter\Forms\Components\TextInput::make('test')
        ->characterLimit(100);

    expect($field->getCharacterLimit())->toBe(100);
});

it('can create textarea with character limit', function () {
    $field = \Schmeits\FilamentCharacterCounter\Forms\Components\Textarea::make('description')
        ->characterLimit(500);

    expect($field->getCharacterLimit())->toBe(500);
});

it('can create rich editor with character limit', function () {
    $field = \Schmeits\FilamentCharacterCounter\Forms\Components\RichEditor::make('content')
        ->characterLimit(1000);

    expect($field->getCharacterLimit())->toBe(1000);
});

it('uses maxLength when characterLimit is not set', function () {
    $field = \Schmeits\FilamentCharacterCounter\Forms\Components\TextInput::make('test')
        ->maxLength(200);

    expect($field->getCharacterLimit())->toBe(200);
});
