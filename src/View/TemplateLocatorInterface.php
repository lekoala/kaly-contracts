<?php

declare(strict_types=1);

namespace Kaly\View;

/**
 * Optional capability: a renderer that can tell whether a template exists.
 *
 * `has()` must answer with the same name resolution as `render()`: the same
 * namespaces, fallbacks and file extensions. It concerns the requested
 * template only, not the templates or components it includes.
 *
 * A renderer that cannot answer faithfully should not implement this
 * interface: a wrong negative would make a caller select a fallback that
 * `render()` would have resolved.
 *
 * Not every renderer is backed by a filesystem, so this is deliberately kept
 * out of RendererInterface.
 */
interface TemplateLocatorInterface
{
    public function has(string $template): bool;
}
