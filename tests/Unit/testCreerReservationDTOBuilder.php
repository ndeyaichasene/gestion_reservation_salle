<?php

declare(strict_types=1);

use App\DTO\CreerReservationDTO;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dto = CreerReservationDTO::builder()
    ->salleId(2)
    ->responsable('Aicha Sene')
    ->email('aicha@example.com')
    ->motif('Cours de programmation')
    ->dateDebut(new DateTimeImmutable('2026-09-10 10:00'))
    ->dateFin(new DateTimeImmutable('2026-09-10 12:00'))
    ->build();

echo "Salle : {$dto->salleId}\n";
echo "Responsable : {$dto->responsable}\n";
echo "Email : {$dto->email}\n";
echo "Motif : {$dto->motif}\n";
echo "Date début : " . $dto->dateDebut->format('Y-m-d H:i') . "\n";
echo "Date fin : " . $dto->dateFin->format('Y-m-d H:i') . "\n";