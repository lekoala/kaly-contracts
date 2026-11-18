# Changelog

## 1.1.0

- `Kaly\View\RendererInterface`, `Kaly\View\TemplateLocatorInterface` and
  `Kaly\View\TemplatePathRegistryInterface`, extracted from `lekoala/kaly`.
  `render()` takes the page's data and the render's shared capabilities as two
  arrays (`render(string $template, array $data = [], array $shared = [])`).
  The environment object that carried the capabilities in Kaly 0.8 is not
  published here. Kaly 0.9 removes its own copies and requires this version.
- `$shared` is scoped to one render and is never retained for the next one. At
  the entry of the render, the shared value wins over a page value of the same
  name, whatever the engine's own precedence. Composed scopes (layouts,
  partials, components) keep the engine's scoping rule.
- `TemplateLocatorInterface::has()` uses the same resolution as `render()`
  (namespaces, fallbacks, extensions) and concerns the requested template only.
  A renderer that cannot answer faithfully does not implement it.
- `Kaly\Task\TaskDispatcher` and `Kaly\Task\ScheduledTaskDispatcher` now live
  under `src/Task/`, next to `src/View/`. Their namespaces do not change.
- No exception contract and no official adapter: rendering failures stay the
  engine's own. The published signatures, including parameter names, are frozen
  by `tests/contracts.php`.

## 1.0.0

- `Kaly\Task\TaskDispatcher` and `Kaly\Task\ScheduledTaskDispatcher`, extracted
  unchanged from `lekoala/kaly` 0.6.
