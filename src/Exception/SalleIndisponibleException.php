<?php

declare(strict_types=1);

namespace App\Exception;

final class SalleIndisponibleException extends \DomainException
{
    public static function inexistante(int $salleId): self
    {
        return new self("La salle #{$salleId} n'existe pas.");
    }

    public static function inactive(int $salleId): self
    {
        return new self("Cette salle ne peut pas être réservée.");
    }

    public static function chevauchement(): self
    {
        return new self("La salle est indisponible pendant cette période.");
    }
}