{{--
    resources/views/partials/character-count-container.blade.php

    The counter itself. Expects the Alpine state characterCount and
    characterLimit from the surrounding element, plus $characterCount,
    $characterLimit and $counterId for the first, server-side render.
    Going over the limit is shown in words and bold, never by colour alone.
--}}
<div
    id="{{ $counterId }}"
    @class([
        'fi-input-charcounter',
        'fi-input-charcounter-exceeded' => $characterLimit > 0 && $characterCount > $characterLimit,
    ])
    @if ($characterLimit > 0)
        x-bind:class="{ 'fi-input-charcounter-exceeded': characterCount > characterLimit }"
    @endif
>
    <span x-text="characterCount">{{ $characterCount }}</span>@if ($characterLimit > 0){{ __('filament-character-counter::character-counter.character_separator') }}{{ $characterLimit }}@endif
    {{ __('filament-character-counter::character-counter.character_label') }}

    @if ($characterLimit > 0)
        <span role="status" aria-live="polite" class="fi-input-charcounter-exceeded-message">
            <span
                x-show="characterCount > characterLimit"
                x-text="@js(__('filament-character-counter::character-counter.characters_over')).replace(':count', characterCount - characterLimit)"
                @style(['display: none' => $characterCount <= $characterLimit])
            >{{ $characterCount > $characterLimit ? __('filament-character-counter::character-counter.characters_over', ['count' => $characterCount - $characterLimit]) : '' }}</span>
        </span>
    @endif
</div>
