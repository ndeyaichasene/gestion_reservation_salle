<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\View\Renderer;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use App\Controller\SalleController;
use App\Controller\ReservationController;
use App\Repository\SalleRepository;
use App\Repository\ReservationRepository;
use App\Validation\SalleValidator;
use App\Validation\ReservationValidator;
use App\Service\CreerReservationService;
use App\Service\AnnulerReservationService;

$bootDatabase = require_once dirname(__DIR__) . '/config/database.php';
$bootDatabase();

$httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$uri = $_SERVER['REQUEST_URI'] ?? '/';

if (($pos = strpos($uri, '?')) !== false) {
    $uri = substr($uri, 0, $pos);
}

$uri = rawurldecode($uri);

$routes = require dirname(__DIR__) . '/routes/web.php';
$dispatcher = simpleDispatcher($routes);

$salleRepo = new SalleRepository();
$reservationRepo = new ReservationRepository();

$renderer = new Renderer();

$salleValidator = new SalleValidator();
$reservationValidator = new ReservationValidator();

$creerService = new CreerReservationService($salleRepo,$reservationRepo);

$annulerService = new AnnulerReservationService($reservationRepo);

$salleController = new SalleController($salleRepo,$salleValidator,$renderer);

$reservationController = new ReservationController($reservationRepo,$salleRepo,$creerService,$annulerService,$reservationValidator,$renderer);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case Dispatcher::NOT_FOUND:
        http_response_code(404);

        echo $renderer->renderView(
            'error/404',
            ['title' => 'Page introuvable']
        );
        break;

    case Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);

        echo $renderer->renderView(
            'error/405',
            ['title' => 'Méthode non autorisée']
        );
        break;

    case Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        [$controllerClass, $method] = $handler;

        if ($controllerClass === SalleController::class) {
            $controller = $salleController;
        } elseif ($controllerClass === ReservationController::class) {
            $controller = $reservationController;
        } else {
            throw new \RuntimeException(
                "Contrôleur non pris en charge : {$controllerClass}"
            );
        }

        $vars = array_map(
            static fn (string $value): int|string =>
                ctype_digit($value) ? (int) $value : $value,
            $vars
        );

        echo $controller->$method(...array_values($vars));
        break;
}