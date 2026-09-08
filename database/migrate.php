<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

$bootDatabase = require_once dirname(__DIR__) . '/config/database.php';
$capsule = $bootDatabase();

$migrations = [
    dirname(__DIR__) . '/database/migrations/001_create_salles_table.php',
    dirname(__DIR__) . '/database/migrations/002_create_reservations_table.php',
];

foreach ($migrations as $migration) {
    $migrationFunction = require $migration;
    $migrationFunction($capsule);

    echo basename($migration) . "executee.\n";
}

echo "Migrations terminées.\n";