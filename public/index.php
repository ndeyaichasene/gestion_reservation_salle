<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Application;
use App\config\ContainerFactory;

$container = ContainerFactory::create();

$application = $container->get(Application::class);

$application->run();