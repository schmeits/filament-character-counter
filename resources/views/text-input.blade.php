{{--
    resources/views/text-input.blade.php

    Filament's text input with the character counter. The count follows the
    Livewire state (paste, autofill, $set(), reset) and every input event, and
    counts per code point like Laravel's max: rule.
--}}
@php
    use Filament\Forms\Components\TextInput\Actions\HidePasswordAction;
    use Filament\Forms\Components\TextInput\Actions\ShowPasswordAction;

    $fieldWrapperView = $getFieldWrapperView();
    $datalistOptions = $getDatalistOptions();
    $extraAlpineAttributes = $getExtraAlpineAttributes();
    $extraAttributeBag = $getExtraAttributeBag();
    $hasInlineLabel = $hasInlineLabel();
    $id = $getId();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $isPasswordRevealable = $isPasswordRevealable();
    $isPrefixInline = $isPrefixInline();
    $isSuffixInline = $isSuffixInline();
    $mask = $getMask();
    $prefixActions = $getPrefixActions();
    $prefixIcon = $getPrefixIcon();
    $prefixIconColor = $getPrefixIconColor();
    $prefixLabel = $getPrefixLabel();
    $suffixActions = $getSuffixActions();
    $suffixIcon = $getSuffixIcon();
    $suffixIconColor = $getSuffixIconColor();
    $suffixLabel = $getSuffixLabel();
    $statePath = $getStatePath();

    $showCharacterCounter = $shouldShowCharacterCounter();
    $characterCount = $getCharacterCount();
    $characterLimit = $getCharacterLimit();
    $counterId = $getCharacterCounterId();

    $accessibilityAttributes = method_exists($field, 'getAccessibilityAttributes') ? $field->getAccessibilityAttributes() : [];

    if ($showCharacterCounter) {
        $accessibilityAttributes['aria-describedby'] = trim(($accessibilityAttributes['aria-describedby'] ?? '') . ' ' . $counterId);
    }

    if ($isPasswordRevealable) {
        $xData = '{ isPasswordRevealed: false }';
    } elseif (count($extraAlpineAttributes) || filled($mask)) {
        $xData = '{}';
    } else {
        $xData = null;
    }

    if ($isPasswordRevealable) {
        $type = null;
    } elseif (filled($mask)) {
        $type = 'text';
    } else {
        $type = $getType();
    }

    $inputAttributes = $getExtraInputAttributeBag()
        ->merge($extraAlpineAttributes, escape: false)
        ->merge([
            ...$accessibilityAttributes,
            'autocapitalize' => $getAutocapitalize(),
            'autocomplete' => $getAutocomplete(),
            'autofocus' => $isAutofocused(),
            'disabled' => $isDisabled,
            'id' => $id,
            'inlinePrefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
            'inlineSuffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
            'inputmode' => $getInputMode(),
            'list' => $datalistOptions ? $id . '-list' : null,
            'max' => (! $isConcealed) ? $getMaxValue() : null,
            'maxlength' => (! $isConcealed) ? $getMaxLength() : null,
            'min' => (! $isConcealed) ? $getMinValue() : null,
            'minlength' => (! $isConcealed) ? $getMinLength() : null,
            'placeholder' => $getPlaceholder(),
            'readonly' => $isReadOnly(),
            'required' => $isRequired() && (! $isConcealed),
            'step' => $getStep(),
            'type' => $type,
            $applyStateBindingModifiers('wire:model') => $statePath,
            'x-bind:type' => $isPasswordRevealable ? 'isPasswordRevealed ? \'text\' : \'password\'' : null,
            'x-mask' . ($mask instanceof \Filament\Support\RawJs ? ':dynamic' : '') => filled($mask) ? $mask : null,
        ], escape: false)
        ->class([
            'fi-revealable' => $isPasswordRevealable,
        ]);
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
    class="fi-fo-text-input-wrp"
    x-data="{
        characterLimit: {{ $characterLimit }},
        characterCount: {{ $characterCount }},
        countCharacters(value) {
            return [...String(value ?? '')].length
        },
    }"
    x-effect="characterCount = countCharacters($wire.$get('{{ $statePath }}'))"
    x-on:input="characterCount = countCharacters($event.target.value)"
>
    <x-filament::input.wrapper
        :disabled="$isDisabled"
        :inline-prefix="$isPrefixInline"
        :inline-suffix="$isSuffixInline"
        :prefix="$prefixLabel"
        :prefix-actions="$prefixActions"
        :prefix-icon="$prefixIcon"
        :prefix-icon-color="$prefixIconColor"
        :suffix="$suffixLabel"
        :suffix-actions="$suffixActions"
        :suffix-icon="$suffixIcon"
        :suffix-icon-color="$suffixIconColor"
        :valid="! $errors->has($statePath)"
        :x-data="$xData"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($extraAttributeBag)
                ->class(['fi-fo-text-input'])
        "
    >
        <input
            {{
                $inputAttributes->class([
                    'fi-input',
                    'fi-input-has-inline-prefix' => $isPrefixInline && (count($prefixActions) || $prefixIcon || filled($prefixLabel)),
                    'fi-input-has-inline-suffix' => $isSuffixInline && (count($suffixActions) || $suffixIcon || filled($suffixLabel)),
                ])
            }}
        />
        @if ($showCharacterCounter && $isShownInsideControl())
            @include('filament-character-counter::partials.character-count-container')
        @endif
    </x-filament::input.wrapper>
    @if ($showCharacterCounter && (! $isShownInsideControl()))
        @include('filament-character-counter::partials.character-count-container')
    @endif

    @if ($datalistOptions)
        <datalist id="{{ $id }}-list">
            @foreach ($datalistOptions as $option)
                <option value="{{ $option }}"></option>
            @endforeach
        </datalist>
    @endif
</x-dynamic-component>
