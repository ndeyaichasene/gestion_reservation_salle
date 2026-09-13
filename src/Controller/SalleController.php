<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Security\CsrfManager;
use App\Validation\SalleValidatorInterface;
use App\View\Renderer;
use App\View\Response;

final class SalleController extends AbstractController
{
    public const NBRSALLEPARPAGE = 5;

    public function __construct(
        private readonly SalleRepositoryInterface $salles,
        private readonly SalleValidatorInterface $validator,
        private readonly CsrfManager $csrfManager,
        Renderer $renderer
    ) {
        parent::__construct($renderer);
    }

    public function index(): Response
    {
        $page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;

        $salles = $this->salles->getSallesPaginated(
            self::NBRSALLEPARPAGE,
            $page
        );

        $current = $salles->currentPage();
        $last = $salles->lastPage();
        $previous = $current - 1;
        $next = $current + 1;

        $pages = [];

        for ($i = 1; $i <= $last; $i++) {
            $pages[] = $i;
        }

        $pagination = [
            'current' => $current,
            'last' => $last,
            'pages' => $pages,
            'hasPrevious' => !$salles->onFirstPage(),
            'hasNext' => $salles->hasMorePages(),
            'previous' => $previous,
            'next' => $next,
        ];

        return new Response([
            'title' => 'Parc des Salles',
            'salles' => $salles,
            'pagination' => $pagination,
        ], 'salle/index');
    }

    public function show(int $id): Response
    {
        $salle = $this->salles->getSalleById($id);

        if ($salle === null) {
            return $this->renderNotFound('Salle introuvable');
        }

        return new Response([
            'title' => $salle->nom,
            'salle' => $salle,
        ], 'salle/show');
    }

    public function create(): Response
    {
        $csrf_token = $this->csrfManager->generateToken();

        return new Response([
            'title' => 'Ajouter une salle',
            'data' => [],
            'errors' => [],
            'csrf_token' => $csrf_token,
        ], 'salle/form');
    }

    public function store(): Response
    {
        $input = $_POST;

        $token = $_POST['csrf_token'] ?? '';
        $isValid = $this->csrfManager->validateToken($token);

        if (!$isValid) {
            http_response_code(403);

            return new Response([
                'title' => 'Ajouter une salle',
                'data' => $input,
                'errors' => [],
                'erreurGlobale' => 'Token CSRF invalide.',
                'csrf_token' => $this->csrfManager->generateToken(),
            ], 'salle/form');
        }

        $input['active'] = isset($_POST['active']);

        $validation = $this->validator->validate($input);

        if (!$validation->isValid()) {
            return new Response([
                'title' => 'Ajouter une salle',
                'errors' => $this->formatErrors($validation->errors()),
                'data' => $input,
                'csrf_token' => $this->csrfManager->generateToken(),
            ], 'salle/form');
        }

        $dto = CreerSalleDTO::fromArray($validation->data());

        $salle = new Salle([
            'nom' => $dto->nom,
            'batiment' => $dto->batiment,
            'capacite' => $dto->capacite,
            'type' => $dto->type,
            'active' => $dto->active,
        ]);

        $this->salles->save($salle);

        $this->renderer->redirect(
            '/salles',
            'Salle enregistrée avec succès.'
        );
    }

    public function edit(int $id): Response
    {
        $salle = $this->salles->getSalleById($id);

        if ($salle === null) {
            return $this->renderNotFound('Salle introuvable');
        }

        $csrf_token = $this->csrfManager->generateToken();

        return new Response([
            'title' => 'Modifier la salle ' . $salle->nom,
            'salle' => $salle,
            'data' => [],
            'errors' => [],
            'csrf_token' => $csrf_token,
        ], 'salle/form');
    }

    public function update(int $id): Response
    {
        $salle = $this->salles->getSalleById($id);

        if ($salle === null) {
            return $this->renderNotFound('Salle introuvable');
        }

        $input = $_POST;

        $token = $_POST['csrf_token'] ?? '';
        $isValid = $this->csrfManager->validateToken($token);

        if (!$isValid) {
            http_response_code(403);

            return new Response([
                'title' => 'Modifier la salle ' . $salle->nom,
                'salle' => $salle,
                'data' => $input,
                'errors' => [],
                'erreurGlobale' => 'Token CSRF invalide.',
                'csrf_token' => $this->csrfManager->generateToken(),
            ], 'salle/form');
        }

        $input['active'] = isset($_POST['active']);

        $validation = $this->validator->validate($input);

        if (!$validation->isValid()) {
            return new Response([
                'title' => 'Modifier la salle ' . $salle->nom,
                'salle' => $salle,
                'errors' => $this->formatErrors($validation->errors()),
                'data' => $input,
                'csrf_token' => $this->csrfManager->generateToken(),
            ], 'salle/form');
        }

        $dto = CreerSalleDTO::fromArray($validation->data());

        $salle->nom = $dto->nom;
        $salle->batiment = $dto->batiment;
        $salle->capacite = $dto->capacite;
        $salle->type = $dto->type;
        $salle->active = $dto->active;

        $this->salles->save($salle);

        $this->renderer->redirect(
            "/salles/{$id}",
            'Modifications enregistrées avec succès.'
        );
    }
}