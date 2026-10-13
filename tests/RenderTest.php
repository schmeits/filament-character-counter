<?php

declare(strict_types=1);

/*
 * tests/RenderTest.php
 *
 * Renders every field in a real Livewire and Filament form and checks the
 * counter markup: placement, limits, the exceeded message, translations,
 * accessibility attributes and valid Alpine state.
 */

use Illuminate\Support\Facades\View;
use Livewire\Livewire;
use Schmeits\FilamentCharacterCounter\Forms\Components\Textarea;
use Schmeits\FilamentCharacterCounter\Forms\Components\TextInput;
use Schmeits\FilamentCharacterCounter\Tests\Fixtures\AllFieldsLivewire;

function renderAllFields(): string
{
    return Livewire::test(AllFieldsLivewire::class)->html();
}

function renderedDom(string $html): DOMXPath
{
    $document = new DOMDocument;

    libxml_use_internal_errors(true);
    $document->loadHTML('<?xml encoding="utf-8"?>' . $html);
    libxml_clear_errors();

    return new DOMXPath($document);
}

function counterFor(DOMXPath $dom, string $field): ?DOMElement
{
    $node = $dom->query("//*[@id='form.{$field}-character-counter']")->item(0);

    return $node instanceof DOMElement ? $node : null;
}

function counterText(DOMXPath $dom, string $field): string
{
    return trim((string) preg_replace('/\s+/u', ' ', counterFor($dom, $field)->textContent ?? ''));
}

it('points the text fields at views that exist', function () {
    foreach ([TextInput::class, Textarea::class] as $component) {
        $view = (new ReflectionClass($component))->getDefaultProperties()['view'];

        expect(View::exists($view))->toBeTrue("View {$view} of {$component} does not exist.");
    }

    expect(View::exists('filament-character-counter::rich-editor'))->toBeTrue()
        ->and(View::exists('filament-character-counter::partials.character-count-container'))->toBeTrue();
});

it('renders a form with every field without errors', function () {
    Livewire::test(AllFieldsLivewire::class)->assertOk();
});

it('renders exactly one counter per field that shows one', function () {
    $html = renderAllFields();
    $dom = renderedDom($html);

    foreach (['title', 'subtitle', 'free_text', 'too_long', 'closure_limit', 'disabled_field', 'description', 'notes', 'content'] as $field) {
        expect(substr_count($html, "id=\"form.{$field}-character-counter\""))->toBe(1, "Field {$field} should have one counter.");
    }

    expect(counterFor($dom, 'hidden_counter'))->toBeNull()
        ->and(counterFor($dom, 'plain_content'))->toBeNull();
});

it('shows the count and the limit', function () {
    $dom = renderedDom(renderAllFields());

    expect(counterText($dom, 'title'))->toBe('10 / 100 characters')
        ->and(counterText($dom, 'subtitle'))->toBe('0 / 60 characters')
        ->and(counterText($dom, 'description'))->toBe('0 / 500 characters');
});

it('shows only the count when there is no limit', function () {
    $dom = renderedDom(renderAllFields());

    expect(counterText($dom, 'free_text'))->toBe('0 characters')
        ->and(counterText($dom, 'closure_limit'))->toBe('0 characters');
});

it('counts per code point on the first render', function () {
    // 'héllo😀!' is 7 code points but 8 UTF-16 units.
    expect(counterText(renderedDom(renderAllFields()), 'too_long'))->toStartWith('7 / 5 characters');
});

it('says how many characters are over the limit, not only in colour', function () {
    $dom = renderedDom(renderAllFields());

    expect(counterFor($dom, 'too_long')?->getAttribute('class'))->toContain('fi-input-charcounter-exceeded')
        ->and(counterText($dom, 'too_long'))->toBe('7 / 5 characters (2 too many)');
});

it('hides the over-the-limit message while under the limit', function () {
    $dom = renderedDom(renderAllFields());
    $message = $dom->query("//*[@id='form.title-character-counter']//*[@x-show]")->item(0);

    expect($message)->toBeInstanceOf(DOMElement::class)
        ->and($message instanceof DOMElement ? $message->getAttribute('style') : '')->toContain('display: none')
        ->and(counterFor($dom, 'title')?->getAttribute('class'))->not->toContain('fi-input-charcounter-exceeded');
});

it('announces the over-the-limit message to screen readers', function () {
    $dom = renderedDom(renderAllFields());

    expect($dom->query("//*[@id='form.too_long-character-counter']//*[@role='status'][@aria-live='polite']")->length)->toBe(1);
});

it('links the input to its counter with aria-describedby', function () {
    $dom = renderedDom(renderAllFields());

    foreach (['title' => 'input', 'description' => 'textarea'] as $field => $element) {
        $input = $dom->query("//{$element}[@id='form.{$field}']")->item(0);

        expect($input)->toBeInstanceOf(DOMElement::class)
            ->and(explode(' ', $input instanceof DOMElement ? $input->getAttribute('aria-describedby') : ''))->toContain("form.{$field}-character-counter");
    }
});

it('translates the counter', function () {
    app()->setLocale('nl');

    expect(counterText(renderedDom(renderAllFields()), 'too_long'))->toBe('7 / 5 karakters (2 te veel)');
});

it('places the counter inside the control only when asked', function () {
    $dom = renderedDom(renderAllFields());
    $insideWrapper = fn (string $field): int => $dom->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' fi-input-wrp ')]//*[@id='form.{$field}-character-counter']")->length;

    expect($insideWrapper('subtitle'))->toBe(1)
        ->and($insideWrapper('notes'))->toBe(1)
        ->and($insideWrapper('title'))->toBe(0)
        ->and($insideWrapper('description'))->toBe(0);
});

it('always renders a numeric limit in the Alpine state', function () {
    // An empty limit used to render "characterLimit: ," which breaks Alpine.
    $html = renderAllFields();

    expect($html)->not->toMatch('/characterLimit:\s*,/')
        ->and($html)->toContain('characterLimit: 0')
        ->and($html)->toContain('characterLimit: 100');
});

it('follows the Livewire state and every input event instead of keyup', function () {
    $html = renderAllFields();

    expect($html)->not->toContain('keyup')
        ->and($html)->toContain('x-on:input="characterCount = countCharacters($event.target.value)"')
        ->and($html)->toContain('$wire.$get(&#039;data.title&#039;)');
});

it('renders a valid height on the textarea', function () {
    expect(renderAllFields())->toContain('style="height: 5.25rem"');
});

it('keeps the counter on a disabled field', function () {
    expect(counterText(renderedDom(renderAllFields()), 'disabled_field'))->toBe('0 / 20 characters');
});

it('lets Filament render the rich editor itself, with all its features', function () {
    $html = renderAllFields();
    $dom = renderedDom($html);

    $wrapper = $dom->query("//*[contains(@class, 'fi-input-charcounter-rich-editor')]")->item(0);

    expect($wrapper)->toBeInstanceOf(DOMElement::class)
        ->and($dom->query(".//*[@id='form.content-character-counter']", $wrapper)->length)->toBe(1)
        ->and($html)->toContain('richEditorFormComponent(')
        ->and($wrapper instanceof DOMElement ? $wrapper->getAttribute('x-data') : '')->toContain('observer?.disconnect()');

    // Filament 4.12+ and 5 render this option. The old copied view always
    // rendered false here, which switched file attachments off.
    if (str_contains($html, 'canAttachFiles:')) {
        expect($html)->toContain('canAttachFiles: true')
            ->and($html)->not->toContain('canAttachFiles: false');
    }
});
