<?php

declare(strict_types=1);

namespace App\Repository;

use App\Model\Reservation;
use Illuminate\Pagination\LengthAwarePaginator;

class ReservationRepository implements ReservationRepositoryInterface{
    public function save(Reservation $reservation):int
    {
        $reservation->save();
        return $reservation->id;
    }

    public function getAllReservations():array
    {
        $reservations = Reservation::all();
        return $reservations->all();
    }

    public function getReservationById(int $id):?Reservation
    {
        return Reservation::find($id);
        
    }

    public function getReservationBySalle(int $salleId): array
    {
        $reservationBySalle = Reservation::where('salle_id', $salleId)->get();

        return $reservationBySalle->all();
    }

    public function getConflitReservation(int $salleId,\DateTimeImmutable $debut,\DateTimeImmutable $fin):?Reservation
    {
        return Reservation::where('salle_id', $salleId)
            ->where('statut', 'confirmee')
            ->where('date_debut', '<', $fin->format('Y-m-d H:i:s'))
            ->where('date_fin', '>', $debut->format('Y-m-d H:i:s'))
            ->first();
    }

    public function annulerReservation(Reservation $reservation):Reservation{
        $reservation->statut = 'annulee';
        $reservation->save();
        return $reservation;
    }

     public function getReservationsPaginated( int $perPage, int $page ): LengthAwarePaginator
    {
        return Reservation::query()->paginate(
            $perPage,
            ['*'],
            'page',
            $page
        );
    }

        public function getReservationsBySallePaginated(int $salleId,int $perPage,int $page): LengthAwarePaginator
    {
        return Reservation::where('salle_id', $salleId)->paginate(
            $perPage,
            ['*'],
            'page',
            $page
        );
    }

    
}