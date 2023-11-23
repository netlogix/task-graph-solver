<?php

declare(strict_types=1);

namespace Netlogix\DependencyResolver;

interface TaskInterface
{
    public function getName(): string;

    /**
     * @return string[] Names of tasks that need to be resolved before this task can be resolved
     */
    public function getDependencies(): array;

    /**
     * @param string[] $resolvedTasks Names of tasks that are already resolved
     */
    public function checkDependencies(array $resolvedTasks): bool;

    public function resolve(): self;

    public function reset(): self;

    public function isResolved(): bool;
}
