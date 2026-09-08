<?php

declare(strict_types=1);

use App\DTO\CreerSalleDTO;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dto = CreerSalleDTO::builder()
    ->nom('Salle A101')
    ->batiment('Bâtiment A')
    ->capacite(40)
    ->type('cours')
    ->active(true)
    ->build();

echo "Nom : {$dto->nom}\n";
echo "Bâtiment : {$dto->batiment}\n";
echo "Capacité : {$dto->capacite}\n";
echo "Type : {$dto->type}\n";
echo "Active : " . ($dto->active ? 'oui' : 'non') . "\n";