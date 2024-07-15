<?php

namespace Schmeits\FilamentCharacterCounter\Forms\Components;

use Schmeits\FilamentCharacterCounter\Forms\Concerns\CanBeShownInsideControl;
use Schmeits\FilamentCharacterCounter\Forms\Concerns\HasCharacterLimit;

class RichEditor extends \Filament\Forms\Components\RichEditor
{
    use CanBeShownInsideControl;
    use HasCharacterLimit;

    protected string $view = 'filament-character-counter::rich-editor';
}
