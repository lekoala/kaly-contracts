<?php

declare(strict_types=1);

namespace Kaly\View;

/**
 * The minimal contract Kaly needs from a template renderer.
 *
 * Kaly standardizes a template identifier, the page data owned by the
 * application, and the shared capabilities of the render. Data and shared
 * variables travel separately, so page data can never silently replace a
 * capability.
 */
interface RendererInterface
{
    /**
     * Render a template by its logical name.
     *
     * Template names may use a namespace notation (eg: admin::template or
     * @admin/template) that the renderer is free to resolve its own way.
     *
     * `$data` is the page's own data, local to the root template. `$shared`
     * holds the values that belong to the whole render: in Kaly, the
     * capabilities of the request (translation, url and asset generation,
     * authentication, CSRF, CSP).
     *
     * `$shared` is scoped to this call. Implementations must not retain it
     * between renders: a value given to one render is never visible to the
     * next.
     *
     * At the entry of the render, a name present in both arrays resolves to
     * the shared value. Implementations must enforce this precedence even
     * when the underlying engine gives its locals priority. Kaly's dispatcher
     * rejects such a collision before calling; the rule above covers direct
     * calls.
     *
     * A renderer that composes templates (layouts, partials, components) does
     * not have to forward the shared values into every composed scope: what a
     * nested scope receives stays the engine's own scoping rule (Twig's
     * `include ... only`, for instance, drops the parent context on purpose).
     *
     * Rendering failures are not normalized here: an exception raised by the
     * engine (missing template, compilation error) stays the engine's own.
     *
     * @param array<string,mixed> $data
     * @param array<string,mixed> $shared
     */
    public function render(string $template, array $data = [], array $shared = []): string;
}
