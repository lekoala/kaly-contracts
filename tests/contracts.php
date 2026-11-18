<?php

declare(strict_types=1);

/**
 * Freezes the published shape of the contracts.
 *
 * Namespaces, method names, parameter names, types and defaults are part of
 * the public API: a class implements the interface, and PHP 8 callers may use
 * named arguments. Adding a method or renaming a parameter is a breaking
 * change, so it must fail here before it ships.
 */

require __DIR__ . '/../vendor/autoload.php';

use Kaly\Task\ScheduledTaskDispatcher;
use Kaly\Task\TaskDispatcher;
use Kaly\View\RendererInterface;
use Kaly\View\TemplateLocatorInterface;
use Kaly\View\TemplatePathRegistryInterface;

final class ContractCheck
{
    /** @var list<string> */
    public static array $failures = [];

    /**
     * @param \Closure(): void $assertion
     */
    public static function that(string $label, \Closure $assertion): void
    {
        try {
            $assertion();
        } catch (\Throwable $exception) {
            self::$failures[] = $label . ': ' . $exception->getMessage();
        }
    }
}

function expect(bool $condition, string $message): void
{
    if (!$condition) {
        throw new \RuntimeException($message);
    }
}

/**
 * @return array{
 *     name: string,
 *     params: list<array{name: string, type: ?string, hasDefault: bool, default: mixed}>,
 *     return: ?string
 * }
 */
function describe(\ReflectionMethod $method): array
{
    $params = [];
    foreach ($method->getParameters() as $parameter) {
        $params[] = [
            'name' => $parameter->getName(),
            'type' => $parameter->hasType() ? (string) $parameter->getType() : null,
            'hasDefault' => $parameter->isDefaultValueAvailable(),
            'default' => $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null,
        ];
    }

    return [
        'name' => $method->getName(),
        'params' => $params,
        'return' => $method->hasReturnType() ? (string) $method->getReturnType() : null,
    ];
}

/**
 * @param array<string, mixed> $expected
 */
function expectSignature(string $interface, string $method, array $expected): void
{
    ContractCheck::that($interface . '::' . $method, static function () use ($interface, $method, $expected): void {
        $actual = describe(new \ReflectionMethod($interface, $method));

        expect($actual === $expected, 'signature changed: ' . json_encode($actual, JSON_THROW_ON_ERROR));
    });
}

$interfaces = [
    TaskDispatcher::class,
    ScheduledTaskDispatcher::class,
    RendererInterface::class,
    TemplateLocatorInterface::class,
    TemplatePathRegistryInterface::class,
];

foreach ($interfaces as $interface) {
    ContractCheck::that($interface, static function () use ($interface): void {
        expect(interface_exists($interface), 'interface does not exist');
        expect((new \ReflectionClass($interface))->isInterface(), 'not an interface');

        $methods = (new \ReflectionClass($interface))->getMethods();
        expect(count($methods) === 1, 'exactly one method is expected, found ' . count($methods));
        expect($methods[0]->isPublic(), 'the method must be public');
        expect($methods[0]->isAbstract(), 'the method must be abstract');
    });
}

ContractCheck::that('ScheduledTaskDispatcher hierarchy', static function (): void {
    expect(
        is_subclass_of(ScheduledTaskDispatcher::class, TaskDispatcher::class),
        'ScheduledTaskDispatcher must extend TaskDispatcher',
    );
});

expectSignature(TaskDispatcher::class, 'dispatch', [
    'name' => 'dispatch',
    'params' => [
        ['name' => 'task', 'type' => 'object', 'hasDefault' => false, 'default' => null],
    ],
    'return' => 'void',
]);

expectSignature(ScheduledTaskDispatcher::class, 'dispatch', [
    'name' => 'dispatch',
    'params' => [
        ['name' => 'task', 'type' => 'object', 'hasDefault' => false, 'default' => null],
        ['name' => 'notBefore', 'type' => '?DateTimeImmutable', 'hasDefault' => true, 'default' => null],
    ],
    'return' => 'void',
]);

expectSignature(RendererInterface::class, 'render', [
    'name' => 'render',
    'params' => [
        ['name' => 'template', 'type' => 'string', 'hasDefault' => false, 'default' => null],
        ['name' => 'data', 'type' => 'array', 'hasDefault' => true, 'default' => []],
        ['name' => 'shared', 'type' => 'array', 'hasDefault' => true, 'default' => []],
    ],
    'return' => 'string',
]);

expectSignature(TemplateLocatorInterface::class, 'has', [
    'name' => 'has',
    'params' => [
        ['name' => 'template', 'type' => 'string', 'hasDefault' => false, 'default' => null],
    ],
    'return' => 'bool',
]);

expectSignature(TemplatePathRegistryInterface::class, 'setPath', [
    'name' => 'setPath',
    'params' => [
        ['name' => 'namespace', 'type' => 'string', 'hasDefault' => false, 'default' => null],
        ['name' => 'path', 'type' => 'string', 'hasDefault' => false, 'default' => null],
    ],
    'return' => 'void',
]);

if (ContractCheck::$failures !== []) {
    fwrite(STDERR, "contracts failed:\n- " . implode("\n- ", ContractCheck::$failures) . "\n");
    exit(1);
}

echo "contracts ok\n";
