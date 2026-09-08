<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

use App\Validation\SalleValidator;

$validator = new SalleValidator();

$data = [
    'nom' => 'Salle B12',
    'batiment' => 'Bâtiment B',
    'capacite' => 40,
    'type' => 'cours',
    'active' => true,
];

$resultat = $validator->validate($data);

var_dump($resultat->isValid());
var_dump($resultat->errors());