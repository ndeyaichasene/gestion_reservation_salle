<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\DTO\CreerReservationDTO;

$data = [
    'salle_id' => '1',
    'responsable' => 'Aicha Sene',
    'email' => 'aicha@example.com',
    'motif' => 'Cours de programmation',
    'date_debut' => '2026-09-07 10:00',
    'date_fin' => '2026-09-07 12:00',
];

$dto = CreerReservationDTO::fromArray($data);

var_dump($dto);