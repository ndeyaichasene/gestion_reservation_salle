<?php
use App\Repository\SalleRepository;
use App\Model\Salle;
require_once dirname(__DIR__,2).'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable (dirname(__DIR__,2));
$dotenv->load();

$bootDatabase = require_once dirname(__DIR__,2). '/config/database.php';
$bootDatabase();

$salleRepository = new SalleRepository();

$salle = $salleRepository->getSalleById(1);

// //var_dump($salle);
echo "Rechercher par ID\n";
echo "ID : {$salle->id}\n";
echo "Nom : {$salle->nom}\n";
echo "Batiment : {$salle->batiment}\n";
echo "Capacité : {$salle->capacite}\n";
echo "Type : {$salle->type}\n";
echo "Active : {$salle->active}\n";
echo "\n";

echo "Afficher toutes les salles\n";

$salles = $salleRepository->getAllSalles();

foreach ($salles as $salle) {
    echo "ID : {$salle->id}\n";
    echo "Nom : {$salle->nom}\n";
    echo "Batiment : {$salle->batiment}\n";
    echo "Capacité : {$salle->capacite}\n";
    echo "Type : {$salle->type}\n";
    echo "Active : {$salle->active}\n";
    echo "-------------------\n";
}

$salle = new Salle();

$salle->nom = 'Salle Test';
$salle->batiment = 'Bâtiment Test';
$salle->capacite = 20;
$salle->type = 'reunion';
$salle->active = true;

$id = $salleRepository->save($salle);

echo "ID créé : {$id}\n";