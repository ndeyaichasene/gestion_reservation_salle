<?php

declare(strict_types=1);

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use App\Repository\ReservationRepository;
use App\Repository\SalleRepository;
use App\Service\CreerReservationService;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Connexion à la base de données
$database = require dirname(__DIR__, 2) . '/config/database.php';
$database();

// Création des dépendances
$salleRepository = new SalleRepository();
$reservationRepository = new ReservationRepository();

$service = new CreerReservationService(
    $salleRepository,
    $reservationRepository
);

// Création du DTO
$dto = CreerReservationDTO::builder()
    ->salleId(2)
    ->responsable('Aicha Sene')
    ->email('aicha@example.com')
    ->motif('Cours de programmation')
    ->dateDebut(new DateTimeImmutable('+1 day 10:00'))
    ->dateFin(new DateTimeImmutable('+1 day 12:00'))
    ->build();

// Appel du service
$reservation = $service->creer($dto);

// Vérifications
echo "ID réservation : {$reservation->id}\n";
echo "Salle : {$reservation->salle_id}\n";
echo "Responsable : {$reservation->responsable}\n";
echo "Email : {$reservation->email}\n";
echo "Motif : {$reservation->motif}\n";
echo "Statut : {$reservation->statut}\n";

echo "\nRéservation créée avec succès !\n";