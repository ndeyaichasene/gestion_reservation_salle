<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Validation\ReservationValidator;

$validator = new ReservationValidator();

$data = [
    'salle_id' => 1,
    'responsable' => 'Aicha Sene',
    'email' => 'aicha@example.com',
    'motif' => 'Cours de programmation',
    'date_debut' => new DateTimeImmutable('+1 day 10:00'),
    'date_fin' => new DateTimeImmutable('+1 day 12:00'),
];

$resultat = $validator->validate($data);

var_dump($resultat->isValid());
var_dump($resultat->errors());