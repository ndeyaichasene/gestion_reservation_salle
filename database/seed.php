<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Model\Salle;


$bootDatabase = require_once dirname(__DIR__) . '/config/database.php';
$bootDatabase();

$salles = [
    ['nom' => 'Amphithéâtre A', 'batiment' => 'Bâtiment Principal', 'capacite' => 250, 'type' => 'amphitheatre'],
    ['nom' => 'Salle B12', 'batiment' => 'Bâtiment B', 'capacite' => 40, 'type' => 'cours'],
    ['nom' => 'Laboratoire Chimie', 'batiment' => 'Bâtiment Sciences', 'capacite' => 24, 'type' => 'laboratoire'],
    ['nom' => 'Salle Informatique 1', 'batiment' => 'Bâtiment C', 'capacite' => 30, 'type' => 'informatique'],
    ['nom' => 'Salle de réunion', 'batiment' => 'Bâtiment Administration', 'capacite' => 12, 'type' => 'reunion'],
];

foreach ($salles as $salle) {
    $salle = Salle::firstOrCreate(
        ['nom' => $salle['nom']],
        [
            'batiment' => $salle['batiment'],
            'capacite' => $salle['capacite'],
            'type'     => $salle['type'],
            'active'   => true,
        ]
    );

    echo $salle->wasRecentlyCreated
        ? "Créée : {$salle->nom}\n"
        : "Déjà existante, ignorée : {$salle->nom}\n";
}