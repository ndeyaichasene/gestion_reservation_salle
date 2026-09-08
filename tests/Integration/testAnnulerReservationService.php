<?php

declare(strict_types=1);

use App\Repository\ReservationRepository;
use App\Service\AnnulerReservationService;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

// Connexion à la base de données
$database = require dirname(__DIR__, 2) . '/config/database.php';
$database();

// Création du repository
$reservationRepository = new ReservationRepository();

// Création du service
$service = new AnnulerReservationService(
    $reservationRepository
);

// ID d'une réservation existante
$id = 1;

// Annulation
$reservation = $service->annuler($id);

// Affichage du résultat
echo "Réservation annulée avec succès !\n";
echo "ID : {$reservation->id}\n";
echo "Statut : {$reservation->statut}\n";