<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Stub;

use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;

final class InMemorySalleRepository implements SalleRepositoryInterface
{
    /** @var Salle[] */
    private array $salles = [];

    public function save(Salle $salle): int
    {
        $id = $salle->id ?? count($this->salles) + 1;

        $salle->id = $id;
        $this->salles[$id] = $salle;

        return $id;
    }

    public function getAllSalles(): array
    {
        return array_values($this->salles);
    }

    public function getSalleById(int $id): ?Salle
    {
        return $this->salles[$id] ?? null;
    }
}