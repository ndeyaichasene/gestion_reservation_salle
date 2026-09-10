<?php

declare(strict_types=1);

namespace App\View;

final class HtmlResponseFormatter implements ResponseFormatterInterface
{
    public function __construct(
        private readonly Renderer $renderer
    ) {
    }

    public function format(Response $response): string
    {
        if ($response->view === null) {
            return '';
        }

        return $this->renderer->renderView(
            $response->view,
            $response->data
        );
    }
}