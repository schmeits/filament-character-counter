<?php

use Filament\Forms\ComponentContainer;

use function Schmeits\FilamentCharacterCounter\Tests\livewire;

it('can test', function () {
    expect(true)->toBeTrue();
});

it('sets its state path from its name', function () {
    $field = (new \Schmeits\FilamentCharacterCounter\Forms\Components\TextInput($name = Str::random()))
        ->container(ComponentContainer::make(Schmeits\FilamentCharacterCounter\Tests\Fixtures\Livewire::make()));

    expect($field)
        ->getStatePath()->toBe($name);
});

it('show the default character counter', function () {
    livewire(Schmeits\FilamentCharacterCounter\Tests\Fixtures\Livewire::class)
        ->assertSeeText(' / 100 characters')
        ->assertSeeText(' / 50 characters')
        ->assertSeeText(' characters');
});
