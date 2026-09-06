<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$bootDatabase = require dirname(__DIR__) . '/config/database.php';
$bootDatabase();

echo "Connexion Eloquent OK";