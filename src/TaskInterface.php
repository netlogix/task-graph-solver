<?php
declare(strict_types=1);

namespace Netlogix\DependencyResolver;

interface TaskInterface
{
    function getName(): string;

    /**
     * @return string[] Names of tasks that need to be resolved before this task can be resolved
     */
    function getDependencies(): array;

    /**
     * @param string[] $resolvedTasks Names of tasks that are already resolved
     * @return bool
     */
    function checkDependencies(array $resolvedTasks): bool;

    function resolve(): self;

    function reset(): self;

    function isResolved(): bool;
}
