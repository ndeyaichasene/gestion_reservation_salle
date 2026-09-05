<?php

declare(strict_types=1);

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

return function():Capsule{
    $capsule = new Capsule();
    $capsule->addConnection([
        'driver' => $_ENV['DB_DRIVER'],
        'host' => $_ENV['DB_HOST'],
        'port' => $_ENV['DB_PORT'],
        'database' => $_ENV['DB_DATABASE'],
        'username' => $_ENV['DB_USERNAME'],
        'password' => $_ENV['DB_PASSWORD'],
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    try {
        $capsule->getConnection()->getPdo();
        
    } catch (\PDOException $e) {
        throw new \RuntimeException('Connexion a la base de donnees impossible : '.$e->getMessage());
    }

    return $capsule;
};
