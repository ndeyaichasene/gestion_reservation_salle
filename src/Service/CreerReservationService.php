<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationInvalideException;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use DateTimeImmutable;

final class CreerReservationService
{
    private const DUREE_MAX_SECONDES = 4 * 3600; // 4 heures max

    public function __construct(
        private readonly SalleRepositoryInterface $salles,
        private readonly ReservationRepositoryInterface $reservations,
    ) {
    }

    public function creer(CreerReservationDTO $dto): Reservation
    {
        // 1. Retrouver la salle
        $salle = $this->salles->getSalleById($dto->salleId);
        if ($salle === null) {
            throw SalleIndisponibleException::inexistante($dto->salleId);
        }

        // 2. Vérifier que la salle est active
        if (!$salle->active) {
            throw SalleIndisponibleException::inactive($dto->salleId);
        }

        // 3. Vérifier que le début précède la fin
        if ($dto->dateDebut >= $dto->dateFin) {
            throw new ReservationInvalideException('La date de début doit précéder la date de fin.');
        }

        // 4. Vérifier que la durée ne dépasse pas 4 heures
        $duree = $dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp();
        if ($duree > self::DUREE_MAX_SECONDES) {
            throw new ReservationInvalideException('Une réservation ne peut pas dépasser quatre heures.');
        }

        // 5. Vérifier que la réservation commence dans le futur
        if ($dto->dateDebut <= new DateTimeImmutable()) {
            throw new ReservationInvalideException('La réservation doit commencer dans le futur.');
        }

        // 6. Rechercher les chevauchements
        $conflit = $this->reservations->getConflitReservation(
            $dto->salleId,
            $dto->dateDebut,
            $dto->dateFin
        );
        if ($conflit !== null) {
            throw SalleIndisponibleException::chevauchement();
        }

        // 7. Créer la réservation
        $reservation = new Reservation([
            'salle_id'    => $dto->salleId,
            'responsable' => $dto->responsable,
            'email'       => $dto->email,
            'motif'       => $dto->motif,
            'date_debut'  => $dto->dateDebut,
            'date_fin'    => $dto->dateFin,
            'statut'      => 'confirmee',
        ]);

        // 8. L'enregistrer
        $this->reservations->save($reservation);

        // 9. Retourner le résultat
        return $reservation;
    }
}
