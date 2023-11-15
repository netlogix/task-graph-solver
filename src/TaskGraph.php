<?php

declare(strict_types=1);

namespace Netlogix\DependencyResolver;

use Exception;
use Generator;
use InvalidArgumentException;
use IteratorAggregate;

class TaskGraph implements IteratorAggregate
{
    public function __construct(
        private readonly TaskPoolInterface $tasks
    ) {
    }

    /**
     * @return Generator<Generator<Task>>
     */
    public function getIterator(): Generator
    {
        $tasksToResolve = [];

        foreach ($this->tasks as $task) {
            $tasksToResolve[$task->getName()] = $task->getName();
        }

        $resolvedTasks = [];

        while (!empty($tasksToResolve)) {
            yield $this->getResolvableTasks($tasksToResolve, $resolvedTasks);

            foreach ($tasksToResolve as $task) {
                if ($this->tasks->getTask($task)->isResolved()) {
                    $resolvedTasks[] = $task;
                    unset($tasksToResolve[$task]);
                }
            }

            if (
                !$this->hasResolvableTasks($tasksToResolve, $resolvedTasks) &&
                !empty($tasksToResolve)
            ) {
                throw new Exception("Dependency cycle detected. Cannot resolve dependencies.");
            }
        }
    }

    private function hasResolvableTasks(iterable $tasksToResolve, array $resolvedTasks): bool
    {
        return $this->getResolvableTasks($tasksToResolve, $resolvedTasks)
            ->valid();
    }

    private function getResolvableTasks(iterable $tasksToResolve, array $resolvedTasks): Generator
    {
        foreach ($tasksToResolve as $taskName) {
            $task = $this->tasks->getTask($taskName);
            if ($task->checkDependencies($resolvedTasks)) {
                yield $taskName => $task;
            }
        }
    }

    public function resetPool(): self
    {
        if (!($this->tasks instanceof ResettableTaskPoolInterface)) {
            throw new InvalidArgumentException('TaskPool is not resettable');
        }

        $this->tasks->reset();

        return $this;
    }
}
