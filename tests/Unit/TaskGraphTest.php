<?php

declare(strict_types=1);

namespace Netlogix\DependencyResolver\Tests\Unit;

use ArrayIterator;
use Exception;
use InvalidArgumentException;
use Netlogix\DependencyResolver\ResettableTaskPoolInterface;
use Netlogix\DependencyResolver\Task;
use Netlogix\DependencyResolver\TaskGraph;
use Netlogix\DependencyResolver\TaskPoolInterface;
use PHPUnit\Framework\TestCase;

class TaskGraphTest extends TestCase
{
    public function testReset(): void
    {
        $taskPool = self::createMock(ResettableTaskPoolInterface::class);
        $taskPool->expects($this->once())
            ->method('reset');

        $graph = new TaskGraph($taskPool);
        $graph->resetPool();
    }

    public function testResetException(): void
    {
        $taskPool = self::createMock(TaskPoolInterface::class);
        self::expectException(InvalidArgumentException::class);
        $graph = new TaskGraph($taskPool);
        $graph->resetPool();
    }

    public function testResolveDependencies(): void
    {
        $tasks = [
            'TaskA' => new Task('TaskA'),
            'TaskB' => new Task('TaskB', ['TaskC']),
            'TaskC' => new Task('TaskC', ['TaskA']),
        ];

        $taskPool = self::createMock(TaskPoolInterface::class);

        $taskPool->method('getIterator')
            ->willReturn(new ArrayIterator($tasks));
        $taskPool->method('getTask')
            ->willReturnCallback(fn ($name) => $tasks[$name]);

        $graph = new TaskGraph($taskPool);

        $taskList = [];
        foreach ($graph->getIterator() as $tasks) {
            foreach ($tasks as $name => $task) {
                $task->resolve();
                $taskList[] = $name;
            }
        }

        $this->assertEquals(['TaskA', 'TaskC', 'TaskB'], $taskList);
    }

    public function testResolveCycle(): void
    {
        $this->expectException(Exception::class);

        $taskPool = self::createMock(TaskPoolInterface::class);

        $taskPool->method('getIterator')
            ->willReturn(new ArrayIterator([new Task('TaskB', ['TaskA']), new Task('TaskA', ['TaskB'])]));

        foreach ((new TaskGraph($taskPool))->getIterator() as $tasks) {
            foreach ($tasks as $task) {
                $task->resolve();
            }
        }
    }
}
