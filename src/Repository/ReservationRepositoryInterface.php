<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Reservation;
use Illuminate\Pagination\LengthAwarePaginator;

interface ReservationRepositoryInterface
{
    public function save(Reservation $reservation):int;

    public function getAllReservations():array;

    public function getReservationById(int $id):?Reservation;

    public function getReservationBySalle(int $salleId):array;

    public function getConflitReservation(int $salleId,\DateTimeImmutable $dateDebut,\DateTimeImmutable $dateFin):?Reservation;

    public function annulerReservation(Reservation $reservation):Reservation;

    public function getReservationsPaginated(int $perPage, int $page): LengthAwarePaginator;
    
    public function getReservationsBySallePaginated( int $salleId, int $perPage, int $page ): LengthAwarePaginator;


}