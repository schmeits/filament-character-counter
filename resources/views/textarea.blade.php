{{--
    resources/views/textarea.blade.php

    Filament's textarea with the character counter. The count follows the
    Livewire state and every input event, and counts per code point like
    Laravel's max: rule.
--}}
@php
    use Filament\Support\Facades\FilamentView;

    $fieldWrapperView = $getFieldWrapperView();
    $extraAttributeBag = $getExtraAttributeBag();
    $hasInlineLabel = $hasInlineLabel();
    $isConcealed = $isConcealed();
    $isDisabled = $isDisabled();
    $rows = $getRows();
    $shouldAutosize = $shouldAutosize();
    $statePath = $getStatePath();

    $initialHeight = (($rows ?? 2) * 1.5) + 0.75;
    $maxLength = $getMaxLength();

    $showCharacterCounter = $shouldShowCharacterCounter();
    $characterCount = $getCharacterCount();
    $characterLimit = $getCharacterLimit();
    $counterId = $getCharacterCounterId();

    $accessibilityAttributes = method_exists($field, 'getAccessibilityAttributes') ? $field->getAccessibilityAttributes() : [];

    if ($showCharacterCounter) {
        $accessibilityAttributes['aria-describedby'] = trim(($accessibilityAttributes['aria-describedby'] ?? '') . ' ' . $counterId);
    }
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
    :has-inline-label="$hasInlineLabel"
    class="fi-fo-textarea-wrp"
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
        :valid="! $errors->has($statePath)"
        :attributes="
            \Filament\Support\prepare_inherited_attributes($extraAttributeBag)
                ->class([
                    'fi-fo-textarea',
                    'fi-autosizable' => $shouldAutosize,
                ])
        "
    >
        <div wire:ignore.self style="height: {{ $initialHeight }}rem">
            <textarea
                @if (FilamentView::hasSpaMode())
                    {{-- format-ignore-start --}}x-load="visible || event (x-modal-opened)"{{-- format-ignore-end --}}
                @else
                    x-load
                @endif
                x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('textarea', 'filament/forms') }}"
                x-data="textareaFormComponent({
                            initialHeight: @js($initialHeight),
                            shouldAutosize: @js($shouldAutosize),
                            state: $wire.$entangle('{{ $statePath }}'),
                        })"
                @if ($maxLength)
                    x-init="$watch('state', value => {
                        if (value && [...value].length > {{ (int) $maxLength }}) {
                            state = [...value].slice(0, {{ (int) $maxLength }}).join('')
                        }
                    })"
                @endif
                @if ($shouldAutosize)
                    x-intersect.once="resize()"
                x-on:resize.window="resize()"
                @endif
                x-model="state"
                @if ($isGrammarlyDisabled())
                    data-gramm="false"
                data-gramm_editor="false"
                data-enable-grammarly="false"
                @endif
                {{ $getExtraAlpineAttributeBag() }}
                {{
                    $getExtraInputAttributeBag()
                        ->merge([
                            ...$accessibilityAttributes,
                            'autocomplete' => $getAutocomplete(),
                            'autofocus' => $isAutofocused(),
                            'cols' => $getCols(),
                            'disabled' => $isDisabled,
                            'id' => $getId(),
                            'maxlength' => (! $isConcealed) ? $getMaxLength() : null,
                            'minlength' => (! $isConcealed) ? $getMinLength() : null,
                            'placeholder' => $getPlaceholder(),
                            'readonly' => $isReadOnly(),
                            'required' => $isRequired() && (! $isConcealed),
                            'rows' => $rows,
                            $applyStateBindingModifiers('wire:model') => $statePath,
                        ], escape: false)
                }}
            ></textarea>
        </div>
        @if ($showCharacterCounter && $isShownInsideControl())
            @include('filament-character-counter::partials.character-count-container')
        @endif
    </x-filament::input.wrapper>
    @if ($showCharacterCounter && (! $isShownInsideControl()))
        @include('filament-character-counter::partials.character-count-container')
    @endif

</x-dynamic-component>
