<?php

require_once __DIR__ . '/vendor/autoload.php';

use Netlogix\DependencyResolver\Task;
use Netlogix\DependencyResolver\TaskPool;
use Netlogix\DependencyResolver\TaskGraph;

$graph = new TaskGraph(
    new TaskPool([
        new Task('TaskA'),
        new Task('TaskB', ['TaskA']),
        new Task('TaskC', ['TaskA']),
        new Task('TaskD', ['TaskB', 'TaskC']),
        new Task('TaskE'),
    ])
);

$first=true;
$lines = ['stateDiagram'];
$lastTasks = [];
foreach ($graph->getIterator() as $tasks) {
    foreach ($tasks as $name => $task) {
        $lastTasks = array_filter($lastTasks, fn($t) => !in_array($t, $task->getDependencies()));
        array_push($lines, ...$first ? ["  [*] --> $name"]
            : array_map(fn($t) => "  $t --> $name", $task->getDependencies()));
        $lastTasks[$name] = $name;
        $task->resolve();
    }
    $first=false;
}
foreach ($lastTasks as $lastTask) {
    $lines[] = "  $lastTask --> [*]";
}

echo implode("\n", $lines) . "\n";
