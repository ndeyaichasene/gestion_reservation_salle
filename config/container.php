<?php

declare(strict_types=1);

use App\Application;
use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\Validation\ReservationValidatorInterface;
use App\Validation\SalleValidator;
use App\Validation\SalleValidatorInterface;
use App\View\Renderer;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

use function DI\autowire;
use function DI\factory;
use function DI\get;
use function DI\value;

use App\View\HtmlResponseFormatter;
use App\View\JsonResponseFormatter;
use App\View\ResponseFormatterInterface;

return [
    'response.format' => value($_ENV['APP_RESPONSE_FORMAT'] ?? 'html'),

    Application::class => autowire(Application::class)->constructorParameter('responseFormatter',get(ResponseFormatterInterface::class)),

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

    SalleValidatorInterface::class => autowire(SalleValidator::class),

    ReservationValidatorInterface::class => autowire(ReservationValidator::class),

    CreerReservationService::class => autowire(CreerReservationService::class),

    AnnulerReservationService::class => autowire(AnnulerReservationService::class),

    HtmlResponseFormatter::class => autowire(HtmlResponseFormatter::class),

    JsonResponseFormatter::class => autowire(JsonResponseFormatter::class),

    ResponseFormatterInterface::class => factory(
        function ($container): ResponseFormatterInterface {
            $format = $_ENV['APP_RESPONSE_FORMAT'] ?? 'html';

            return $format === 'json' ? $container->get(JsonResponseFormatter::class) : $container->get(HtmlResponseFormatter::class);
        }
    ),
];