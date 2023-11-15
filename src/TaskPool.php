<?php

declare(strict_types=1);

namespace Netlogix\DependencyResolver;

use ArrayIterator;
use InvalidArgumentException;
use Traversable;

class TaskPool implements ResettableTaskPoolInterface
{
    private array $tasks = [];

    public function __construct(iterable $tasks = [])
    {
        foreach ($tasks as $task) {
            $this->addTask($task);
        }
    }

    public function addTask(TaskInterface $task): self
    {
        if (isset($this->tasks[$task->getName()])) {
            throw new InvalidArgumentException('Task already exists');
        }

        $this->tasks[$task->getName()] = $task;

        return $this;
    }

    public function getTask(string $name): TaskInterface
    {
        if (!isset($this->tasks[$name])) {
            throw new InvalidArgumentException(sprintf('Task "%s" does not exist', $name));
        }

        return $this->tasks[$name];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->tasks);
    }

    public function reset(): self
    {
        array_map(fn ($t) => $t->reset(), $this->tasks);

        return $this;
    }
}
