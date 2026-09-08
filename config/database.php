<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->safeLoad();

return function(): Capsule {
    $capsule = new Capsule();
    $capsule->addConnection([
        'driver'    => getenv('DB_DRIVER') ?: ($_ENV['DB_DRIVER'] ?? 'mysql'),
        'host'      => getenv('DB_HOST') ?: ($_ENV['DB_HOST'] ?? '127.0.0.1'),
        'port'      => (int) (getenv('DB_PORT') ?: ($_ENV['DB_PORT'] ?? 3306)),
        'database'  => getenv('DB_DATABASE') ?: ($_ENV['DB_DATABASE'] ?? 'reservation_salles'),
        'username'  => getenv('DB_USERNAME') ?: ($_ENV['DB_USERNAME'] ?? 'aicha'),
        'password'  => getenv('DB_PASSWORD') !== false ? (string) getenv('DB_PASSWORD') : (string) ($_ENV['DB_PASSWORD'] ?? 'passer'),
        'charset'   => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix'    => '',
    ]);
    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    try {
        $capsule->getConnection()->getPdo();
    } catch (\PDOException $e) {
        throw new \RuntimeException('Connexion a la base de donnees impossible : ' . $e->getMessage());
    }

    return $capsule;
};
