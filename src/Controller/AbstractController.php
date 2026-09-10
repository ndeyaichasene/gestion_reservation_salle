<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\Renderer;
use App\View\Response;

abstract class AbstractController
{
    protected function __construct(
        protected readonly Renderer $renderer
    ) {}

    protected function renderNotFound(string $title): Response
    {
        http_response_code(404);

        return new Response([
            'title' => $title,
        ], 'error/404');
    }

    protected function formatErrors(array $errors): array
    {
        return array_map(
            fn ($error) => is_array($error) ? reset($error) : (string) $error,
            $errors
        );
    }

    abstract public function index(): Response;

    abstract public function show(int $id): Response;

    abstract public function create(): Response;

    abstract public function store(): Response;
}