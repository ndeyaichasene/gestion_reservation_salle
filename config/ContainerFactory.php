<?php

declare(strict_types=1);

namespace App\config;

use DI\Container;
use DI\ContainerBuilder;

final class ContainerFactory
{
    public static function create(?string $definitions = null): Container
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions(
            $definitions ?? (dirname(__DIR__) . '/config/container.php')
        );

        return $builder->build();
    }

    public function build(?string $definitions = null): Container
    {
        return self::create($definitions);
    }
}
