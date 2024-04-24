<?php

namespace Schmeits\FilamentCharacterCounter\Forms\Components;

use Schmeits\FilamentCharacterCounter\Forms\Concerns\HasCharacterLimit;

class Textarea extends \Filament\Forms\Components\Textarea
{
    use HasCharacterLimit;

    protected string $view = 'filament-character-counter::textarea';
}
