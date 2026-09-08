<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';


use App\View\Renderer;

// 1. Initialisation de la base de données Eloquent
$bootDatabase = require_once dirname(__DIR__) . '/config/database.php';
$bootDatabase();

// 2. Traitement de la requête HTTP
$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);



// 5. MODE DIRECT ÉTAPE 9 (en attendant les définitions de routes FastRoute de l'étape 10)
// Permet d'afficher et tester immédiatement toutes les vues dans le navigateur !
use App\Repository\SalleRepository;
use App\Repository\ReservationRepository;
use App\Validation\SalleValidator;
use App\Validation\ReservationValidator;
use App\Service\CreerReservationService;
use App\Service\AnnulerReservationService;
use App\Controller\SalleController;
use App\Controller\ReservationController;

$salleRepo = new SalleRepository();
$reservationRepo = new ReservationRepository();
$renderer = new Renderer();
$salleValidator = new SalleValidator();
$reservationValidator = new ReservationValidator();
$creerService = new CreerReservationService($salleRepo, $reservationRepo);
$annulerService = new AnnulerReservationService($reservationRepo);

$salleController = new SalleController($salleRepo, $salleValidator, $renderer);
$reservationController = new ReservationController(
    $reservationRepo,
    $salleRepo,
    $creerService,
    $annulerService,
    $reservationValidator,
    $renderer
);

// Accueil et liste des salles
if ($uri === '/' || $uri === '/index.php' || $uri === '/salles' || $uri === '/salle/index.php') {
    if ($httpMethod === 'POST' && ($uri === '/salles' || $uri === '/salle/index.php')) {
        echo $salleController->store();
    } else {
        echo $salleController->index();
    }
    exit;
}

// Formulaire création salle
if ($uri === '/salles/create' || $uri === '/salle/form.php') {
    echo $salleController->create();
    exit;
}

// Détail salle
if (preg_match('#^/salles/(\d+)$#', $uri, $matches)) {
    echo $salleController->show((int) $matches[1]);
    exit;
}
if ($uri === '/salle/show.php') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 1;
    echo $salleController->show($id);
    exit;
}

// Modification salle
if (preg_match('#^/salles/(\d+)/edit$#', $uri, $matches)) {
    if ($httpMethod === 'POST') {
        echo $salleController->update((int) $matches[1]);
    } else {
        echo $salleController->edit((int) $matches[1]);
    }
    exit;
}

// Liste des réservations
if ($uri === '/reservations' || $uri === '/reservation/index.php') {
    if ($httpMethod === 'POST') {
        echo $reservationController->store();
    } else {
        echo $reservationController->index();
    }
    exit;
}

// Formulaire réservation
if ($uri === '/reservations/create' || $uri === '/reservation/form.php') {
    echo $reservationController->create();
    exit;
}

// Détail réservation
if (preg_match('#^/reservations/(\d+)$#', $uri, $matches)) {
    echo $reservationController->show((int) $matches[1]);
    exit;
}
if ($uri === '/reservation/show.php') {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 1;
    echo $reservationController->show($id);
    exit;
}

// Annulation réservation
if (preg_match('#^/reservations/(\d+)/cancel$#', $uri, $matches)) {
    echo $reservationController->cancel((int) $matches[1]);
    exit;
}

// 404 par défaut
http_response_code(404);
echo $renderer->renderView('error/404', ['title' => 'Page introuvable']);