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
use App\View\Response;

final class ReservationController extends AbstractController
{
    public const NBRRESERVATIONPARPAGE = 2;
    public function __construct(
        private readonly ReservationRepositoryInterface $reservations,
        private readonly SalleRepositoryInterface $salles,
        private readonly CreerReservationService $creerService,
        private readonly AnnulerReservationService $annulerService,
        private readonly ReservationValidator $validator,
        Renderer $renderer
    ) {
        parent::__construct($renderer);
    }

    public function index(): Response
    {
        $salleId = isset($_GET['salle_id']) && $_GET['salle_id'] !== '' ? (int) $_GET['salle_id'] : null;

        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        if ($salleId !== null) {
            $reservations = $this->reservations->getReservationsBySallePaginated($salleId, self::NBRRESERVATIONPARPAGE, $page);
        } else {
            $reservations = $this->reservations->getReservationsPaginated(self::NBRRESERVATIONPARPAGE, $page);
        }

        $salles = $this->salles->getAllSalles();

        $current = $reservations->currentPage();
        $last = $reservations->lastPage();
        $show = $last > 1;

        $pages = [];

        for ($i = 1; $i <= $last; $i++) {
            $pages[] = [
                'number' => $i,
                'url' => '?'
                    . ($salleId !== null ? 'salle_id=' . $salleId . '&' : '')
                    . 'page=' . $i,
            ];
        }

        $pagination = [
            'show' => $show,
            'current' => $current,
            'pages' => $pages,
            'hasPrevious' => !$reservations->onFirstPage(),
            'hasNext' => $reservations->hasMorePages(),
            'previousUrl' => '?'
                . ($salleId !== null ? 'salle_id=' . $salleId . '&' : '')
                . 'page=' . ($current - 1),
            'nextUrl' => '?'
                . ($salleId !== null ? 'salle_id=' . $salleId . '&' : '')
                . 'page=' . ($current + 1),
        ];
        $emptyMessage = null;

if ($reservations->isEmpty()) {
    $emptyMessage = $salleId !== null
        ? 'Aucune réservation pour cette salle.'
        : 'Aucune réservation trouvée.';
}
        return new Response([
            'title' => 'Gestion des réservations',
            'reservations' => $reservations,
            'salles' => $salles,
            'selectedSalleId' => $salleId,
            'pagination' => $pagination,
            'emptyMessage'=>$emptyMessage
        ], 'reservation/index');
    }

    public function show(int $id): Response
    {
        $reservation = $this->reservations->getReservationById($id);

        if ($reservation === null) {
            return $this->renderNotFound('Réservation introuvable');
        }

        return new Response([
            'title'       => 'Détail de la réservation #' . $reservation->id,
            'reservation' => $reservation,
        ], 'reservation/show');
    }

    public function create(): Response
    {
        $salles = $this->salles->getAllSalles();

        return new Response([
            'title'  => 'Réserver une salle',
            'salles' => $salles,
            'data'   => [],
            'errors' => [],
        ], 'reservation/form');
    }

    public function store(): Response
    {
        $input = $_POST;

        $validation = $this->validator->validate($input);

        if (!$validation->isValid()) {
            $salles = $this->salles->getAllSalles();

            return new Response([
                'title'  => 'Réserver une salle',
                'salles' => $salles,
                'data'   => $input,
                'errors' => $this->formatErrors($validation->errors()),
            ], 'reservation/form');
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
        } catch (
            SalleIndisponibleException |
            ReservationInvalideException $e
        ) {
            $salles = $this->salles->getAllSalles();

            return new Response([
                'title'        => 'Réserver une salle',
                'salles'       => $salles,
                'data'         => $input,
                'errors'       => [],
                'generalError' => $e->getMessage(),
            ], 'reservation/form');
        }
    }

    public function cancel(int $id): void
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
    }
}
