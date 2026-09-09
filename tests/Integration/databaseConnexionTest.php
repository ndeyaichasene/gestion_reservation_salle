<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;

final class databaseConnexionTest extends TestCase
{
    public function testConnexionEloquentEstEtablie(): void
    {
        $bootDatabase = require dirname(__DIR__, 2) . '/config/database.php';
        $bootDatabase();

        $this->assertNotNull(Capsule::connection()->getPdo());
    }
}
