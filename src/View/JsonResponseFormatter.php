<?php

declare(strict_types=1);

namespace App\View;

final class JsonResponseFormatter implements ResponseFormatterInterface
{
    public function format(Response $response): string
    {
        header('Content-Type: application/json; charset=utf-8');
        return json_encode(
            ['data' => $response->data],
            JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT
        );
    }
}