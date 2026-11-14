# Kaly contracts

Stable interfaces shared by [Kaly](https://github.com/lekoala/kaly) and its
packages, so that a package can implement them without depending on Kaly.

| Interface | Role |
|---|---|
| `Kaly\Task\TaskDispatcher` | Submits an invocable task under the guarantees of the implementation |
| `Kaly\Task\ScheduledTaskDispatcher` | Adds an absolute lower bound on execution (`?DateTimeImmutable $notBefore`) |

The namespace is `Kaly\Task`, as it was inside Kaly: applications change no
import and no binding.

This package depends on PHP only. It conflicts with `lekoala/kaly` below 0.7,
which still ships these interfaces itself.

## Stability

The interfaces are frozen at 1.0. Adding a method to an interface would break
every implementation: a new capability comes as a separate interface, or in a
major version.

## License

MIT
