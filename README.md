# Kaly contracts

Stable interfaces shared by [Kaly](https://github.com/lekoala/kaly) and its
packages, so that a package can implement them without depending on Kaly.

Each domain owns its source directory:

```
src/
  Task/   -> Kaly\Task
  View/   -> Kaly\View
```

## Tasks

| Interface | Role |
|---|---|
| `Kaly\Task\TaskDispatcher` | Submits an invocable task under the guarantees of the implementation |
| `Kaly\Task\ScheduledTaskDispatcher` | Adds an absolute lower bound on execution (`?DateTimeImmutable $notBefore`) |

## Views

| Interface | Role |
|---|---|
| `Kaly\View\RendererInterface` | Renders a template with the page's data and the render's shared capabilities |
| `Kaly\View\TemplateLocatorInterface` | Optional capability: tells whether a template exists, with the same resolution as `render()` |
| `Kaly\View\TemplatePathRegistryInterface` | Optional capability: registers a template path per namespace |

The namespaces are `Kaly\Task` and `Kaly\View`, as they were inside Kaly:
applications change no import and no binding.

`RendererInterface::render()` receives the page's data and the render's shared
capabilities as two separate arrays. `$shared` is scoped to one render: it is
never retained for the next one, and at the entry of the render a name present
in both arrays resolves to the shared value. Kaly's dispatcher rejects such a
collision before calling; the renderer applies the rule for direct calls. What
a composed template scope (layout, partial, component) receives stays the
engine's own scoping rule.

This package depends on PHP only. It conflicts with `lekoala/kaly` below 0.9,
which still ships these interfaces itself.

## Interoperability

The contract standardizes the call Kaly makes, not the engine behind it:
configuration, template name resolution, composed-scope rules and rendering
failures stay the engine's own. It is therefore usable with engines that expose
a two-argument renderer (the Schranz Templating prototype and the PSR template
renderer proposal), and with engines whose data lives on a view object
(Tempest) or whose common parameters follow another precedence (Yii); each
needs a small bridge, none needs a change here.

The third argument is the deliberate difference: page data and the render's
shared capabilities travel separately, so a capability is never silently
replaced by page data. `has()` and `setPath()` are optional because not every
engine can answer them faithfully. Rendering exceptions stay engine-specific:
this package publishes no exception contract.

This package ships no adapter: a bridge belongs to the integration that needs
it.

## Stability

The task interfaces are frozen since 1.0 and the view interfaces since 1.1.
Adding a method to an interface would break every implementation: a new
capability comes as a separate interface, or in a major version. The published
signatures, including parameter names, are frozen by `tests/contracts.php`.

## License

MIT
