<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Application;
use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\config\ContainerFactory;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testContainerResoutLesDependances(): void
    {
        $container = ContainerFactory::create();

        $this->assertInstanceOf(
            SalleRepositoryInterface::class,
            $container->get(SalleRepositoryInterface::class)
        );
        $this->assertInstanceOf(
            ReservationRepositoryInterface::class,
            $container->get(ReservationRepositoryInterface::class)
        );
        $this->assertInstanceOf(
            SalleController::class,
            $container->get(SalleController::class)
        );
        $this->assertInstanceOf(
            ReservationController::class,
            $container->get(ReservationController::class)
        );
        $this->assertInstanceOf(
            Application::class,
            $container->get(Application::class)
        );
    }
}
