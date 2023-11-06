<?php
declare(strict_types=1);

namespace Netlogix\DependencyResolver;

interface TaskPoolInterface extends \IteratorAggregate {

    /**
     * @return \Traversable<TaskInterface>
     */
    function getIterator(): \Traversable;

    function getTask(string $name): TaskInterface;
}
