<?php

declare(strict_types=1);

namespace Tests\Unit\Service\Stub;

use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

final class InMemoryReservationRepository implements ReservationRepositoryInterface
{
    /** @var Reservation[] */
    private array $reservations = [];

    public function save(Reservation $reservation): int
    {
        $id = $reservation->id ?? count($this->reservations) + 1;

        $reservation->id = $id;
        $this->reservations[$id] = $reservation;

        return $id;
    }

    public function getAllReservations(): array
    {
        return array_values($this->reservations);
    }

    public function getReservationById(int $id): ?Reservation
    {
        return $this->reservations[$id] ?? null;
    }

    public function getReservationBySalle(int $salleId): array
    {
        return array_values(
            array_filter(
                $this->reservations,
                fn (Reservation $reservation) =>
                    $reservation->salle_id === $salleId
            )
        );
    }

    public function getConflitReservation(
        int $salleId,
        \DateTimeImmutable $dateDebut,
        \DateTimeImmutable $dateFin
    ): ?Reservation {
        foreach ($this->reservations as $reservation) {
            if (
                $reservation->salle_id === $salleId
                && $reservation->statut === 'confirmee'
                && $dateDebut < $reservation->date_fin
                && $dateFin > $reservation->date_debut
            ) {
                return $reservation;
            }
        }

        return null;
    }

    public function annulerReservation(Reservation $reservation): Reservation
    {
        $reservation->statut = 'annulee';

        return $reservation;
    }
}