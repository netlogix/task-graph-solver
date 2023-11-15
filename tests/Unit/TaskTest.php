<?php

declare(strict_types=1);

namespace Netlogix\DependencyResolver;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    public function testConstructAndGetters(): void
    {
        $task = new Task('TaskA', ['TaskB', 'TaskC']);

        $this->assertSame('TaskA', $task->getName());
        $this->assertEquals(['TaskB', 'TaskC'], $task->getDependencies());
        $this->assertFalse($task->isResolved());
    }

    public function testCheckDependencies(): void
    {
        $resolvedTasks = ['TaskB', 'TaskC'];
        $task = new Task('TaskA', ['TaskB', 'TaskC']);

        $this->assertTrue($task->checkDependencies($resolvedTasks));

        $resolvedTasks = ['TaskB'];
        $this->assertFalse($task->checkDependencies($resolvedTasks));
    }

    public function testResolve(): void
    {
        $task = new Task('TaskA');

        $task->resolve();

        $this->assertTrue($task->isResolved());
    }

    public function testReset(): void
    {
        $task = new Task('TaskA');

        $task->resolve();
        $this->assertTrue($task->isResolved());

        $task->reset();
        $this->assertFalse($task->isResolved());
    }

    public function testConstructWithInvalidDependencies(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Task('TaskA', [1, 'TaskC']);
    }
}
