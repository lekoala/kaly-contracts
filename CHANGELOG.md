# Changelog

## 1.1.0

- `Kaly\View\RendererInterface`, `Kaly\View\TemplateLocatorInterface` and
  `Kaly\View\TemplatePathRegistryInterface`, extracted from `lekoala/kaly`.
  `render()` takes the page's data and the render's shared capabilities as two
  arrays (`render(string $template, array $data = [], array $shared = [])`):
  when a name exists in both, the shared value wins. The environment object
  that carried the capabilities in Kaly 0.8 is not published here. Kaly 0.9
  removes its own copies and requires this version.

## 1.0.0

- `Kaly\Task\TaskDispatcher` and `Kaly\Task\ScheduledTaskDispatcher`, extracted
  unchanged from `lekoala/kaly` 0.6.
