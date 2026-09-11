<?php
declare(strict_types=1);

namespace App\Repository;

use App\Model\Salle;
use Illuminate\Pagination\LengthAwarePaginator;

interface SalleRepositoryInterface{

    public function save(Salle $salle):int;

    public function getAllSalles():array;

    public function getSalleById(int $id):?Salle;

    public function getSallesPaginated(int $perPage, int $page): LengthAwarePaginator;
}