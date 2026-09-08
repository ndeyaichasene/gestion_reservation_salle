<?php
require_once dirname(__DIR__,2).'/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable (dirname(__DIR__,2));
$dotenv->load();
$bootDatabase = require_once dirname(__DIR__,2). '/config/database.php';
$bootDatabase();

$salle = App\Model\Salle::create([
    'nom' => 'A103',
    'batiment' => 'Bâtiment F',
    'capacite' => 130,
    'type' => 'cours',
]);

echo "$salle->id.\n";