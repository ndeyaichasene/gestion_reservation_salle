<?php

declare(strict_types=1);

use App\Application;
use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use App\View\Renderer;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

use function DI\autowire;
use function DI\factory;

return [

    SalleRepositoryInterface::class => autowire(SalleRepository::class),

    ReservationRepositoryInterface::class => autowire(ReservationRepository::class),


    Capsule::class => factory(function (): Capsule {

        $bootDatabase = require dirname(__DIR__) . '/config/database.php';

        return $bootDatabase();
    }),



    Dispatcher::class => factory(function (): Dispatcher {

        $routes = require dirname(__DIR__) . '/routes/web.php';

        return \FastRoute\simpleDispatcher($routes);
    }),


    Renderer::class => autowire(Renderer::class),

    SalleValidator::class => autowire(SalleValidator::class),

    ReservationValidator::class => autowire(ReservationValidator::class),

    CreerReservationService::class => autowire(CreerReservationService::class),

    AnnulerReservationService::class => autowire(AnnulerReservationService::class),

    SalleController::class => autowire(SalleController::class),

    ReservationController::class => autowire(ReservationController::class),

    Application::class => autowire(Application::class),
];