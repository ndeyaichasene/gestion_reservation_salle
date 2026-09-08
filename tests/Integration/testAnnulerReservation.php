<?php

declare(strict_types=1);

use App\Model\Reservation;
use App\Repository\ReservationRepository;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$bootDatabase = require dirname(__DIR__, 2) . '/config/database.php';
$bootDatabase();

$repository = new ReservationRepository();

// Récupérer une réservation existante
$reservation = $repository->getReservationById(1);

if ($reservation === null) {
    echo "Réservation introuvable.\n";
    exit;
}

echo "Avant annulation : {$reservation->statut}\n";

// Annuler la réservation
$reservation = $repository->annulerReservation($reservation);

echo "Après annulation : {$reservation->statut}\n";