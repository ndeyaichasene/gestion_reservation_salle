<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ReservationIntrouvableException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

final class AnnulerReservationService
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations
    ) {
    }

    public function annuler(int $id): Reservation
    {
        $reservation = $this->reservations->getReservationById($id);
        if ($reservation === null) {
            throw ReservationIntrouvableException::inexistante($id);
        }

        return $this->reservations->annulerReservation($reservation);
    }
}
