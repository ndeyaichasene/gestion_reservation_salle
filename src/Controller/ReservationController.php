<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationInvalideException;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\View\Renderer;

final class ReservationController
{
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly SalleRepositoryInterface $salles,
        private readonly CreerReservationService $creerService,
        private readonly AnnulerReservationService $annulerService,
        private readonly ReservationValidator $validator,
        private readonly Renderer $renderer
    ) {
    }

    public function index(): string
    {
        $salleId = isset($_GET['salle_id']) && $_GET['salle_id'] !== '' ? (int) $_GET['salle_id'] : null;

        if ($salleId !== null) {
            $reservations = $this->reservations->getReservationBySalle($salleId);
        } else {
            $reservations = $this->reservations->getAllReservations();
        }

        $salles = $this->salles->getAllSalles();

        return $this->renderer->renderView('reservation/index', [
            'title'           => 'Gestion des réservations',
            'reservations'    => $reservations,
            'salles'          => $salles,
            'selectedSalleId' => $salleId,
        ]);
    }

    public function show(int $id): string
    {
        $reservation = $this->reservations->getReservationById($id);

        if ($reservation === null) {
            http_response_code(404);

            return $this->renderer->renderView('error/404', [
                'title' => 'Réservation introuvable',
            ]);
        }

        return $this->renderer->renderView('reservation/show', [
            'title'       => 'Détail de la réservation #' . $reservation->id,
            'reservation' => $reservation,
        ]);
    }

    public function create(): string
    {
        $salles = $this->salles->getAllSalles();

        return $this->renderer->renderView('reservation/form', [
            'title'  => 'Réserver une salle',
            'salles' => $salles,
            'data'   => [],
            'errors' => [],
        ]);
    }

    public function store(): string
    {
        $input = $_POST;

        $validation = $this->validator->validate($input);

        if (!$validation->isValid()) {
            $salles = $this->salles->getAllSalles();

            return $this->renderer->renderView('reservation/form', [
                'title'  => 'Réserver une salle',
                'salles' => $salles,
                'data'   => $input,
                'errors' => $validation->errors(),
            ]);
        }

        try {
            $dto = CreerReservationDTO::fromArray(
                $validation->data()
            );

            $this->creerService->creer($dto);

            $this->renderer->redirect(
                '/reservations',
                'Réservation confirmée avec succès.'
            );

            return '';
        } catch (
            SalleIndisponibleException |
            ReservationInvalideException $e
        ) {
            $salles = $this->salles->getAllSalles();

            return $this->renderer->renderView('reservation/form', [
                'title'        => 'Réserver une salle',
                'salles'       => $salles,
                'data'         => $input,
                'errors'       => [],
                'generalError' => $e->getMessage(),
            ]);
        }
    }

    public function cancel(int $id): string
    {
        try {
            $this->annulerService->annuler($id);

            $this->renderer->redirect(
                '/reservations',
                'La réservation a bien été annulée.'
            );
        } catch (ReservationIntrouvableException $e) {
            $this->renderer->redirect(
                '/reservations',
                null,
                $e->getMessage()
            );
        }

        return '';
    }
}