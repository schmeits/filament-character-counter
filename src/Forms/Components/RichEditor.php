<?php

declare(strict_types=1);

/*
 * src/Forms/Components/RichEditor.php
 *
 * Filament's RichEditor with a character counter. The editor itself is
 * rendered by Filament untouched; the counter is placed around it, so every
 * editor feature keeps working whatever the Filament version.
 */

namespace Schmeits\FilamentCharacterCounter\Forms\Components;

use Illuminate\Support\HtmlString;
use Schmeits\FilamentCharacterCounter\Forms\Concerns\CanBeShownInsideControl;
use Schmeits\FilamentCharacterCounter\Forms\Concerns\HasCharacterLimit;

class RichEditor extends \Filament\Forms\Components\RichEditor
{
    use CanBeShownInsideControl;
    use HasCharacterLimit;

    public function toHtml(): string
    {
        $editorHtml = parent::toHtml();

        if (! $this->shouldShowCharacterCounter()) {
            return $editorHtml;
        }

        return view('filament-character-counter::rich-editor', [
            'characterCount' => 0,
            'characterLimit' => $this->getCharacterLimit(),
            'counterId' => $this->getCharacterCounterId(),
            'editorHtml' => new HtmlString($editorHtml),
        ])->render();
    }
}
