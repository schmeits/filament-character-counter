<?php

declare(strict_types=1);

/*
 * tests/Fixtures/AllFieldsLivewire.php
 *
 * Livewire form that renders every field of this package in the
 * configurations the render tests check.
 */

namespace Schmeits\FilamentCharacterCounter\Tests\Fixtures;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Livewire\Component;
use Schmeits\FilamentCharacterCounter\Forms\Components\RichEditor;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class AllFieldsLivewire extends Component implements HasForms
{
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function form(Schema $form): Schema
    {
        return $form
            ->statePath('data')
            ->components([
                TextInput::make('title')
                    ->characterLimit(100),

                TextInput::make('subtitle')
                    ->maxLength(60)
                    ->showInsideControl(),

                TextInput::make('free_text'),

                TextInput::make('too_long')
                    ->characterLimit(5),

                TextInput::make('closure_limit')
                    ->characterLimit(fn (): ?int => null),

                TextInput::make('disabled_field')
                    ->characterLimit(20)
                    ->disabled(),

                Textarea::make('description')
                    ->characterLimit(500)
                    ->rows(3),

                Textarea::make('notes')
                    ->characterLimit(50)
                    ->showInsideControl(),

                RichEditor::make('content')
                    ->characterLimit(1000)
                    ->fileAttachments(true),

                RichEditor::make('plain_content')
                    ->showCharacterCounter(false),

                TextInput::make('hidden_counter')
                    ->characterLimit(100)
                    ->showCharacterCounter(false),
            ]);
    }

    public function mount(): void
    {
        $this->form->fill([
            'title' => '1234567890',
            'too_long' => 'héllo😀!',
        ]);
    }

    public function render(): string
    {
        return <<<'HTML'
        <div>
            {{ $this->form }}
        </div>
        HTML;
    }
}
