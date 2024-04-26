<?php

namespace Schmeits\FilamentCharacterCounter\Forms\Components;

use Schmeits\FilamentCharacterCounter\Forms\Concerns\CanBeShownInsideControl;
use Schmeits\FilamentCharacterCounter\Forms\Concerns\HasCharacterLimit;

class TextInput extends \Filament\Forms\Components\TextInput
{
    use CanBeShownInsideControl;
    use HasCharacterLimit;

    protected string $view = 'filament-character-counter::text-input';
}
