<?php

use App\Model\Reservation;
use App\Repository\ReservationRepository;

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 2));
$dotenv->load();

$bootDatabase = require_once dirname(__DIR__, 2) . '/config/database.php';
$bootDatabase();

$reservation = new Reservation();

$data = [
    'salle_id' => '2',
    'responsable' => 'aicha',
    'email' => 'aicha@gmail.com',
    'motif' => 'cours',
    'date_debut' => '2026-09-10 10:00',
    'date_fin' => '2026-09-10 12:00',
    'statut' => 'confirmee',
];

$reservation->fill($data);

$repository = new ReservationRepository();

$id = $repository->save($reservation);

echo "Réservation créée avec l'ID : {$id}\n";

// Nouvelle réservation à tester : 11h → 13h
$debut = new DateTimeImmutable('2026-09-10 11:00');
$fin = new DateTimeImmutable('2026-09-10 13:00');

$conflit = $repository->getConflitReservation(2, $debut, $fin);

if ($conflit !== null) {
    echo "Conflit trouvé !\n";
    echo "Réservation en conflit : {$conflit->id}\n";
} else {
    echo "Aucun conflit.\n";
}