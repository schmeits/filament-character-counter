<div class="fi-input-charcounter relative bottom-0.5 text-end text-xs px-2" @if($getCharacterLimit()) :class="{ 'text-danger-500': characterCount > {{ $getCharacterLimit() }} }" @endif>
    <span x-text="characterCount"></span>@if($getCharacterLimit()){{ __('filament-character-counter::character-counter.character_separator') }}{{ $getCharacterLimit() }}@endif {{ __('filament-character-counter::character-counter.character_label') }}
</div>
