<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Stub;

use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

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

    public function getSallesPaginated(int $perPage, int $page): LengthAwarePaginator
    {
        $salles = array_values($this->salles);

        $offset = ($page - 1) * $perPage;

        $items = array_slice($salles, $offset, $perPage);

        return new LengthAwarePaginator(
            $items,
            count($salles),
            $perPage,
            $page
        );
    }

    
}