<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();

$builder->addDefinitions(
    dirname(__DIR__) . '/config/container.php'
);

$container = $builder->build();

$application = $container->get(Application::class);

$application->run();