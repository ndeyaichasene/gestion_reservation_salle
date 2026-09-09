<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use DI\ContainerBuilder;
use Illuminate\Database\Capsule\Manager ;
use PHPUnit\Framework\TestCase;

final class CapsuleTest extends TestCase
{
    public function testCapsuleEstDisponibleDansLeContainer(): void
    {
        $builder = new ContainerBuilder();

        $builder->addDefinitions(
            dirname(__DIR__, 2) . '/config/container.php'
        );

        $container = $builder->build();

        $capsule = $container->get(Manager::class);

        $this->assertInstanceOf(Manager::class, $capsule);
    }
}