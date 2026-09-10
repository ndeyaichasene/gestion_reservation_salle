<?php

declare(strict_types=1);

namespace App\View;

final class Response
{
    public function __construct(
        public readonly mixed $data,
          public readonly ?string $view = null
    ) {
    }
}