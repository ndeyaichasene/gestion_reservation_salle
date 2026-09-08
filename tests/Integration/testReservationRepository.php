<?php

use App\Repository\ReservationRepository;

require_once dirname(__DIR__,2).'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable (dirname(__DIR__,2));
$dotenv->load();

$bootDatabase = require_once dirname(__DIR__,2). '/config/database.php';
$bootDatabase();

$reservationRepository = new ReservationRepository();

$reservations = $reservationRepository->getReservationBySalle(1);

foreach ($reservations as $reservation) {
    echo "ID : {$reservation->id}\n";
    echo "Salle : {$reservation->salle_id}\n";
    echo "Responsable : {$reservation->responsable}\n";
    echo "Email : {$reservation->email}\n";
    echo "Motif : {$reservation->motif}\n";
    echo "Date_debut : {$reservation->date_debut}\n";
    echo "Date_fin : {$reservation->date_fin}\n";
    echo "Statut : {$reservation->statut}\n";
    echo "-------------------\n";
}
