<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Application;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Validation\ReservationValidatorInterface;
use App\Validation\SalleValidatorInterface;
use App\config\ContainerFactory;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testContainerResoutLesDependances(): void
    {
        $container = ContainerFactory::create();

        $this->assertInstanceOf(SalleRepositoryInterface::class, $container->get(SalleRepositoryInterface::class));
        $this->assertInstanceOf(ReservationRepositoryInterface::class, $container->get(ReservationRepositoryInterface::class));
        $this->assertInstanceOf(SalleValidatorInterface::class, $container->get(SalleValidatorInterface::class));
        $this->assertInstanceOf(ReservationValidatorInterface::class, $container->get(ReservationValidatorInterface::class));
        $this->assertInstanceOf(Application::class, $container->get(Application::class));
    }
}
