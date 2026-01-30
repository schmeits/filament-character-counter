@php
    use Filament\Support\Facades\FilamentView;

    $customBlocks = $getCustomBlocks();
    $extraInputAttributeBag = $getExtraAttributeBag();
    $fieldWrapperView = $getFieldWrapperView();
    $id = $getId();
    $isDisabled = $isDisabled();
    $livewireKey = $getLivewireKey();
    $key = $getKey();
    $mergeTags = $getMergeTags();
    $statePath = $getStatePath();
    $tools = $getTools();
    $toolbarButtons = $getToolbarButtons();

    // Additional variables that might be needed (with safe defaults)
    $mentions = method_exists($this, 'getMentionsForJs') ? $getMentionsForJs() : [];
    $floatingToolbars = method_exists($this, 'getFloatingToolbars') ? $getFloatingToolbars() : [];
    $linkProtocols = method_exists($this, 'getLinkProtocols') ? $getLinkProtocols() : [];
    $fileAttachmentsMaxSize = method_exists($this, 'getFileAttachmentsMaxSize') ? $getFileAttachmentsMaxSize() : null;
    $fileAttachmentsAcceptedFileTypes = method_exists($this, 'getFileAttachmentsAcceptedFileTypes') ? $getFileAttachmentsAcceptedFileTypes() : [];
@endphp

<x-dynamic-component
    :component="$fieldWrapperView"
    :field="$field"
>
    <div
        class="fi-fo-rich-editor-wrapper"
        x-data="{
            characterCount: 0,
            characterLimit: {{ $getCharacterLimit() }},
            updateCount() {
                try {
                    const editorContent = this.$el.querySelector('.fi-fo-rich-editor-content');
                    if (!editorContent) return;

                    const text = editorContent.textContent || editorContent.innerText || '';
                    this.characterCount = text.trim().length;
                } catch (e) {
                    // Silently ignore errors
                }
            }
        }"
        x-init="
            // Update on Livewire commits
            Livewire.hook('commit', ({ component }) => {
                if (component.id === @js($this->getId())) {
                    setTimeout(() => updateCount(), 100);
                }
            });

            // Initial count
            setTimeout(() => updateCount(), 1000);

            // Periodic update
            setInterval(() => updateCount(), 500);
        "
    >
        <x-filament::input.wrapper
            :valid="! $errors->has($statePath)"
            x-cloak
            :attributes="
                \Filament\Support\prepare_inherited_attributes($extraInputAttributeBag)
                    ->class(['fi-fo-rich-editor'])
            "
        >
            <div
                x-ref="editorWrapper"
                @if (FilamentView::hasSpaMode())
                    {{-- format-ignore-start --}}x-load="visible || event (x-modal-opened)"{{-- format-ignore-end --}}
                @else
                    x-load
                @endif
                x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('rich-editor', 'filament/forms') }}"
                x-data="richEditorFormComponent({
                            acceptedFileTypes: @js($fileAttachmentsAcceptedFileTypes),
                            acceptedFileTypesValidationMessage: @js($fileAttachmentsAcceptedFileTypes ? __('filament-forms::components.rich_editor.file_attachments_accepted_file_types_message', ['values' => implode(', ', $fileAttachmentsAcceptedFileTypes)]) : null),
                            activePanel: @js($getActivePanel()),
                            canAttachFiles: @js(method_exists($this, 'hasFileAttachments') ? $hasFileAttachments() : false),
                            deleteCustomBlockButtonIconHtml: @js(\Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::Trash, alias: 'forms:components.rich-editor.panels.custom-block.delete-button')->toHtml()),
                            editCustomBlockButtonIconHtml: @js(\Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::PencilSquare, alias: 'forms:components.rich-editor.panels.custom-block.edit-button')->toHtml()),
                            extensions: @js($getTipTapJsExtensions()),
                            floatingToolbars: @js($floatingToolbars),
                            hasResizableImages: @js(method_exists($this, 'hasResizableImages') ? $hasResizableImages() : false),
                            isDisabled: @js($isDisabled),
                            isLiveDebounced: @js($isLiveDebounced()),
                            isLiveOnBlur: @js($isLiveOnBlur()),
                            key: @js($key),
                            linkProtocols: @js($linkProtocols),
                            liveDebounce: @js($getNormalizedLiveDebounce()),
                            livewireId: @js($this->getId()),
                            maxFileSize: @js($fileAttachmentsMaxSize),
                            maxFileSizeValidationMessage: @js($fileAttachmentsMaxSize ? trans_choice('filament-forms::components.rich_editor.file_attachments_max_size_message', $fileAttachmentsMaxSize, ['max' => $fileAttachmentsMaxSize]) : null),
                            mentions: @js($mentions),
                            mergeTags: @js($mergeTags),
                            noMergeTagSearchResultsMessage: @js($getNoMergeTagSearchResultsMessage()),
                            placeholder: @js($getPlaceholder()),
                            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: false) }},
                            statePath: @js($statePath),
                            uploadingFileMessage: @js($getUploadingFileMessage()),
                        })"
                x-bind:class="{
                    'fi-fo-rich-editor-uploading-file': isUploadingFile,
                }"
                wire:ignore
                wire:key="{{ $livewireKey }}.editor{{ $isDisabled ? '.disabled' : '' }}"
            >
                @if ((! $isDisabled) && filled($toolbarButtons))
                    <div class="fi-fo-rich-editor-toolbar">
                        @foreach ($toolbarButtons as $buttonGroup)
                            <div class="fi-fo-rich-editor-toolbar-group">
                                @foreach ($buttonGroup as $button)
                                    {{ $tools[$button] ?? throw new Exception("Toolbar button [{$button}] cannot be found.") }}
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="fi-fo-rich-editor-main">
                    <div
                        class="fi-fo-rich-editor-content fi-prose"
                        x-ref="editor"
                    ></div>

                    @if (! $isDisabled)
                        <div
                            x-show="isPanelActive()"
                            class="fi-fo-rich-editor-panels"
                        >
                            <div
                                x-show="isPanelActive('customBlocks')"
                                class="fi-fo-rich-editor-panel"
                            >
                                <div class="fi-fo-rich-editor-panel-header">
                                    <p class="fi-fo-rich-editor-panel-heading">
                                        {{ __('filament-forms::components.rich_editor.tools.custom_blocks') }}
                                    </p>

                                    <div
                                        class="fi-fo-rich-editor-panel-close-btn-ctn"
                                    >
                                        <button
                                            type="button"
                                            x-on:click="togglePanel()"
                                            class="fi-icon-btn"
                                        >
                                            {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::XMark, alias: 'forms:components.rich-editor.panels.custom-blocks.close-button') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="fi-fo-rich-editor-custom-blocks-list">
                                    @foreach ($customBlocks as $block)
                                        @php
                                            $blockId = $block::getId();
                                        @endphp

                                        <button
                                            draggable="true"
                                            type="button"
                                            x-data="{ isLoading: false }"
                                            x-on:click="
                                                isLoading = true

                                                $wire.mountAction(
                                                    'customBlock',
                                                    { editorSelection, id: @js($blockId), mode: 'insert' },
                                                    { schemaComponent: @js($key) },
                                                )
                                            "
                                            x-on:dragstart="$event.dataTransfer.setData('customBlock', @js($blockId))"
                                            x-on:open-modal.window="isLoading = false"
                                            x-on:run-rich-editor-commands.window="isLoading = false"
                                            class="fi-fo-rich-editor-custom-block-btn"
                                        >
                                            {{
                                                \Filament\Support\generate_loading_indicator_html((new \Illuminate\View\ComponentAttributeBag([
                                                    'x-show' => 'isLoading',
                                                ])))
                                            }}

                                            {{ $block::getLabel() }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div
                                x-show="isPanelActive('mergeTags')"
                                class="fi-fo-rich-editor-panel"
                            >
                                <div class="fi-fo-rich-editor-panel-header">
                                    <p class="fi-fo-rich-editor-panel-heading">
                                        {{ __('filament-forms::components.rich_editor.tools.merge_tags') }}
                                    </p>

                                    <div
                                        class="fi-fo-rich-editor-panel-close-btn-ctn"
                                    >
                                        <button
                                            type="button"
                                            x-on:click="togglePanel()"
                                            class="fi-icon-btn"
                                        >
                                            {{ \Filament\Support\generate_icon_html(\Filament\Support\Icons\Heroicon::XMark, alias: 'forms:components.rich-editor.panels.merge-tags.close-button') }}
                                        </button>
                                    </div>
                                </div>

                                <div class="fi-fo-rich-editor-merge-tags-list">
                                    @foreach ($mergeTags as $tag)
                                        <button
                                            draggable="true"
                                            type="button"
                                            x-on:click="insertMergeTag(@js($tag))"
                                            x-on:dragstart="$event.dataTransfer.setData('mergeTag', @js($tag))"
                                            class="fi-fo-rich-editor-merge-tag-btn"
                                        >
                                            <span
                                                data-type="mergeTag"
                                                data-id="{{ $tag }}"
                                            >
                                                {{ $tag }}
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </x-filament::input.wrapper>

        @if (!$isShownInsideControl())
            @include('filament-character-counter::partials.character-count-container')
        @endif
    </div>
</x-dynamic-component>
