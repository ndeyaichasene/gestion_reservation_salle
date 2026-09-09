<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\config\ContainerFactory;
use DI\Container;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

final class ContainerFactoryTest extends TestCase
{
    public function testCreateRetourneUneInstanceDeContainer(): void
    {
        $container = ContainerFactory::create();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    public function testBuildRetourneUneInstanceDeContainer(): void
    {
        $factory = new ContainerFactory();
        $container = $factory->build();

        $this->assertInstanceOf(Container::class, $container);
        $this->assertInstanceOf(ContainerInterface::class, $container);
    }
}
