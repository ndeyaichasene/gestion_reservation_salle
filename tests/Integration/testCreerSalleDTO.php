<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\DTO\CreerSalleDTO;

$data = [
    'nom' => 'Salle B12',
    'batiment' => 'Bâtiment B',
    'capacite' => '40',
    'type' => 'cours',
    'active' => true,
];

$dto = CreerSalleDTO::fromArray($data);

var_dump($dto);