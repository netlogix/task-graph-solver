<?php
declare(strict_types=1);

namespace Netlogix\DependencyResolver;

use JetBrains\PhpStorm\NoReturn;

class Task implements TaskInterface
{
    /**
     * @var true
     */
    private bool $resolved = false;

    /**
     * @param array<string> $dependencies
     */
    function __construct(
        private readonly string $name,
        private readonly array  $dependencies = []
    )
    {
        if (array_filter($dependencies, fn($i) => !is_string($i))) {
            throw new \InvalidArgumentException('Dependencies must be strings');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDependencies(): array
    {
        return $this->dependencies;
    }

    /**
     * @param iterable<string> $resolvedTasks
     */
    public function checkDependencies(array $resolvedTasks): bool
    {
        return [] == array_diff($this->getDependencies(), $resolvedTasks);
    }

    public function resolve(): self
    {
        $this->resolved = true;
        return $this;
    }

    public function isResolved(): bool
    {
        return $this->resolved;
    }

    public function reset(): self
    {
        $this->resolved = false;
        return $this;
    }
}
