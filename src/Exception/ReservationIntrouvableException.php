<?php

declare(strict_types=1);

namespace App\Exception;

final class ReservationIntrouvableException extends \DomainException
{
    public static function inexistante(int $id): self
    {
        return new self("La réservation #{$id} est introuvable.");
    }
}