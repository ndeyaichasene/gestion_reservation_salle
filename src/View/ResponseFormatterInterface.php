<?php

declare(strict_types=1);

namespace App\View;

interface ResponseFormatterInterface
{
    public function format(Response $reponse): string;
}