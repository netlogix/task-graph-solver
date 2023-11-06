<?php

declare(strict_types=1);

namespace Netlogix\DependencyResolver;

use PHPUnit\Framework\TestCase;

class TaskPoolTest extends TestCase
{
    function testCanInstantiate(): void
    {
        $taskPool = new TaskPool();

        $this->assertInstanceOf(TaskPoolInterface::class, $taskPool);
        $this->assertInstanceOf(\Traversable::class, $taskPool);
    }

    function testAddTask(): void
    {
        $taskPool = new TaskPool();

        $task = self::createMock(TaskInterface::class);
        $task->method('getName')->willReturn('TaskA');
        $taskPool->addTask($task);

        $this->assertSame($task, $taskPool->getTask('TaskA'));
    }

    function testAddTaskException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $taskPool = new TaskPool();

        $task = self::createMock(TaskInterface::class);
        $task->method('getName')->willReturn('TaskA');
        $taskPool->addTask($task);
        $taskPool->addTask($task);
    }

    function testGetTask(): void
    {
        $taskPool = new TaskPool();

        $task = self::createMock(TaskInterface::class);
        $task->method('getName')->willReturn('TaskA');
        $taskPool->addTask($task);

        $this->assertSame($task, $taskPool->getTask('TaskA'));
    }

    function testGetTaskException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $taskPool = new TaskPool();

        $taskPool->getTask('TaskB');
    }

    function testGetIterator(): void
    {
        $task = self::createMock(TaskInterface::class);
        $task->method('getName')->willReturn('TaskA');

        $taskPool = new TaskPool([$task]);

        $this->assertSame(['TaskA' => $task], iterator_to_array($taskPool));
    }

    function testReset(): void
    {
        $taskPool = new TaskPool();

        $task = self::createMock(TaskInterface::class);
        $task->method('getName')->willReturn('TaskA');
        $task->expects($this->once())->method('reset');
        $taskPool->addTask($task);

        $taskPool->reset();
    }
}
