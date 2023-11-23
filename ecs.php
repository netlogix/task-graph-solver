<?php

declare(strict_types=1);

use Netlogix\CodingGuidelines\Php\DefaultPhp;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    (new DefaultPhp())->configure($ecsConfig);

    $ecsConfig->paths(
        [
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ]
    );
};
