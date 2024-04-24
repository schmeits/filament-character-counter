<?php

namespace Schmeits\FilamentCharacterCounter\Tests\Fixtures;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;

class Livewire extends Component implements HasForms
{
    use InteractsWithForms;

    public $data;

    public static function make(): static
    {
        return new static();
    }

    public function form(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->default('1234567890')
                    ->characterLimit(100),

                TextInput::make('enabled')
                    ->characterLimit(50),

                TextInput::make('visible'),

            ]);
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function data($data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            <form wire:submit="create">
                {{ $this->form }}

                <button type="submit">
                    Submit
                </button>
            </form>

            <x-filament-actions::modals />
        </div>
        HTML;
    }
}
