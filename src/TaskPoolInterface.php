<?php

declare(strict_types=1);

namespace Netlogix\DependencyResolver;

use IteratorAggregate;
use Traversable;

interface TaskPoolInterface extends IteratorAggregate
{
    /**
     * @return Traversable<TaskInterface>
     */
    public function getIterator(): Traversable;

    public function getTask(string $name): TaskInterface;
}
