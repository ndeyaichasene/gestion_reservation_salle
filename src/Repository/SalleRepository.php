<?php

declare(strict_types=1);

namespace  App\Repository;

use App\Model\Salle;

class SalleRepository implements SalleRepositoryInterface{

    public function save(Salle $salle):int{
        $salle ->save();
        return $salle->id;
    }

    public function getAllSalles():array{

        $salles = Salle::all();
        return $salles->all();
    }

    public function getSalleById(int $id):?Salle{

        $salle = Salle::find($id);
        return $salle;
    }

}