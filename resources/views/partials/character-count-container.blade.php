<div class="fi-input-charcounter" @if($getCharacterLimit()) :class="{ 'warning': characterCount > {{ $getCharacterLimit() }} }" @endif>
    <span x-text="characterCount"></span>@if($getCharacterLimit()){{ (app('translator')->has('filament-character-counter::character-counter.character_separator')?__('filament-character-counter::character-counter.character_separator'):__('filament-character-counter::character-counter.character_seperator')) }}{{ $getCharacterLimit() }}@endif {{ __('filament-character-counter::character-counter.character_label') }}
</div>
