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
     * authentication, CSRF, CSP). When a name exists in both, the shared
     * value wins.
     *
     * A renderer that composes templates (layouts, partials) forwards the
     * shared values as far as its own scoping rules allow; what a nested
     * template scope receives stays the engine's business (Twig's
     * `include ... only`, for instance, drops the parent context on purpose).
     *
     * @param array<string,mixed> $data
     * @param array<string,mixed> $shared
     */
    public function render(string $template, array $data = [], array $shared = []): string;
}
