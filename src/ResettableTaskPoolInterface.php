<?php

declare(strict_types=1);

namespace Netlogix\DependencyResolver;

interface ResettableTaskPoolInterface extends TaskPoolInterface
{
    public function reset(): self;
}
