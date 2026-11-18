# Kaly contracts

Stable interfaces shared by [Kaly](https://github.com/lekoala/kaly) and its
packages, so that a package can implement them without depending on Kaly.

| Interface | Role |
|---|---|
| `Kaly\Task\TaskDispatcher` | Submits an invocable task under the guarantees of the implementation |
| `Kaly\Task\ScheduledTaskDispatcher` | Adds an absolute lower bound on execution (`?DateTimeImmutable $notBefore`) |
| `Kaly\View\RendererInterface` | Renders a template with the page's data and the render's shared capabilities |
| `Kaly\View\TemplateLocatorInterface` | Optional capability: tells whether a template exists |
| `Kaly\View\TemplatePathRegistryInterface` | Optional capability: registers a template path per namespace |

The namespaces are `Kaly\Task` and `Kaly\View`, as they were inside Kaly:
applications change no import and no binding.

`RendererInterface::render()` receives the page's data and the render's shared
capabilities as two separate arrays: when a name exists in both, the shared
value wins. What a composed template scope (layout, partial) receives stays the
engine's own scoping rule.

This package depends on PHP only. It conflicts with `lekoala/kaly` below 0.9,
which still ships these interfaces itself.

## Stability

The task interfaces are frozen since 1.0 and the view interfaces since 1.1.
Adding a method to an interface would break every implementation: a new
capability comes as a separate interface, or in a major version.

## License

MIT
