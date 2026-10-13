{{--
    resources/views/rich-editor.blade.php

    Wraps Filament's own rich editor with the counter. The editor is rendered
    by Filament untouched; a MutationObserver counts the text of the editor
    whenever it changes and is disconnected when the field is removed.
--}}
<div
    class="fi-input-charcounter-rich-editor"
    x-data="{
        characterLimit: @js($characterLimit),
        characterCount: @js($characterCount),
        observer: null,
        countCharacters() {
            const content = this.$refs.editorField.querySelector('.fi-fo-rich-editor-content')

            this.characterCount = content ? [...(content.textContent ?? '').trim()].length : 0
        },
        init() {
            this.countCharacters()

            this.observer = new MutationObserver(() => this.countCharacters())
            this.observer.observe(this.$refs.editorField, { characterData: true, childList: true, subtree: true })
        },
        destroy() {
            this.observer?.disconnect()
        },
    }"
>
    <div x-ref="editorField">
        {{ $editorHtml }}
    </div>

    @include('filament-character-counter::partials.character-count-container')
</div>
