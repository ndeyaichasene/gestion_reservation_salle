<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\config\ContainerFactory;
use Illuminate\Database\Capsule\Manager ;
use PHPUnit\Framework\TestCase;

final class CapsuleTest extends TestCase
{
    public function testCapsuleEstDisponibleDansLeContainer(): void
    {
        $container = ContainerFactory::create();

        $capsule = $container->get(Manager::class);

        $this->assertInstanceOf(Manager::class, $capsule);
    }
}