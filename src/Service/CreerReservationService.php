<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationInvalideException;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Model\Salle;
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
        $this->recupererEtValiderSalle($dto->salleId);
        $this->validerCreneau($dto);
        $this->verifierDisponibilite($dto);

        return $this->enregistrerReservation($dto);
    }

    private function recupererEtValiderSalle(int $salleId): Salle
    {
        $salle = $this->salles->getSalleById($salleId);
        if ($salle === null) {
            throw SalleIndisponibleException::inexistante($salleId);
        }

        if (!$salle->active) {
            throw SalleIndisponibleException::inactive($salleId);
        }

        return $salle;
    }

    private function validerCreneau(CreerReservationDTO $dto): void
    {
        if ($dto->dateDebut >= $dto->dateFin) {
            throw new ReservationInvalideException('La date de début doit précéder la date de fin.');
        }

        $duree = $dto->dateFin->getTimestamp() - $dto->dateDebut->getTimestamp();
        if ($duree > self::DUREE_MAX_SECONDES) {
            throw new ReservationInvalideException('Une réservation ne peut pas dépasser quatre heures.');
        }

        if ($dto->dateDebut <= new DateTimeImmutable()) {
            throw new ReservationInvalideException('La réservation doit commencer dans le futur.');
        }
    }

    private function verifierDisponibilite(CreerReservationDTO $dto): void
    {
        $conflit = $this->reservations->getConflitReservation(
            $dto->salleId,
            $dto->dateDebut,
            $dto->dateFin
        );

        if ($conflit !== null) {
            throw SalleIndisponibleException::chevauchement();
        }
    }

    private function enregistrerReservation(CreerReservationDTO $dto): Reservation
    {
        $reservation = new Reservation([
            'salle_id'    => $dto->salleId,
            'responsable' => $dto->responsable,
            'email'       => $dto->email,
            'motif'       => $dto->motif,
            'date_debut'  => $dto->dateDebut,
            'date_fin'    => $dto->dateFin,
            'statut'      => 'confirmee',
        ]);

        $this->reservations->save($reservation);

        return $reservation;
    }
}
