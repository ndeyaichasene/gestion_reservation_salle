<?php

declare(strict_types=1);

namespace App;

use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\View\Renderer;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

final class Application
{
    public function __construct(
        private readonly Dispatcher $dispatcher,
        private readonly Renderer $renderer,
        private readonly SalleController $salleController,
        private readonly ReservationController $reservationController,
        private readonly Capsule $capsule
    ) {}

    public function run(): void
    {
        $this->capsule->getConnection();
        $httpMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $uri = rawurldecode($uri);

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                http_response_code(404);

                echo $this->renderer->renderView(
                    'error/404',
                    ['title' => '404 - Page introuvable']
                );
                break;

            case Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];

                http_response_code(405);

                header('Allow: ' . implode(', ', $allowedMethods));

                echo $this->renderer->renderView(
                    'error/405',
                    [
                        'title' => '405 - Méthode non autorisée',
                        'allowedMethods' => $allowedMethods,
                    ]
                );
                break;

            case Dispatcher::FOUND:
                [$controllerClass, $action] = $routeInfo[1];

                $vars = $routeInfo[2];

                $controller = match ($controllerClass) {
                    SalleController::class => $this->salleController,
                    ReservationController::class => $this->reservationController,
                    default => throw new \RuntimeException(
                        "Contrôleur inconnu : {$controllerClass}"
                    ),
                };

                $refMethod = new \ReflectionMethod(
                    $controller,
                    $action
                );

                $args = [];

                foreach ($refMethod->getParameters() as $param) {
                    $name = $param->getName();

                    if (array_key_exists($name, $vars)) {
                        $val = $vars[$name];

                        $type = $param->getType();

                        if ($type instanceof \ReflectionNamedType) {
                            $val = match ($type->getName()) {
                                'int' => (int) $val,
                                'float' => (float) $val,
                                'bool' => filter_var(
                                    $val,
                                    FILTER_VALIDATE_BOOLEAN
                                ),
                                'string' => (string) $val,
                                default => $val,
                            };
                        }

                        $args[] = $val;
                    } elseif ($param->isDefaultValueAvailable()) {
                        $args[] = $param->getDefaultValue();
                    }
                }

                $response = $controller->$action(...$args);

                if (is_string($response) && $response !== '') {
                    echo $response;
                }

                break;
        }
    }
}