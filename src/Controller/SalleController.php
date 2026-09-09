<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\View\Renderer;

final class SalleController
{
    public function __construct(
        private readonly SalleRepositoryInterface $salles,
        private readonly SalleValidator $validator,
        private readonly Renderer $renderer
    ) {
    }

    public function index(): string
    {
        $salles = $this->salles->getAllSalles();

        return $this->renderer->renderView('salle/index', [
            'title'  => 'Parc des Salles',
            'salles' => $salles,
        ]);
    }

    public function show(int $id): string
    {
        $salle = $this->salles->getSalleById($id);

        if ($salle === null) {
            http_response_code(404);

            return $this->renderer->renderView('error/404', [
                'title' => 'Salle introuvable',
            ]);
        }

        return $this->renderer->renderView('salle/show', [
            'title' => $salle->nom,
            'salle' => $salle,
        ]);
    }

    public function create(): string
    {
        return $this->renderer->renderView('salle/form', [
            'title' => 'Ajouter une salle',
            'data'  => [],
        ]);
    }

    public function store(): string
    {
        $input = $_POST;
        $input['active'] = isset($_POST['active']);

        $validation = $this->validator->validate($input);

        if (!$validation->isValid()) {
            return $this->renderer->renderView('salle/form', [
                'title'  => 'Ajouter une salle',
                'errors' => $validation->errors(),
                'data'   => $input,
            ]);
        }

        $dto = CreerSalleDTO::fromArray($validation->data());

        $salle = new Salle([
            'nom'      => $dto->nom,
            'batiment' => $dto->batiment,
            'capacite' => $dto->capacite,
            'type'     => $dto->type,
            'active'   => $dto->active,
        ]);

        $this->salles->save($salle);

        $this->renderer->redirect(
            '/salles',
            'Salle enregistrée avec succès.'
        );

        return '';
    }

    public function edit(int $id): string
    {
        $salle = $this->salles->getSalleById($id);

        if ($salle === null) {
            http_response_code(404);

            return $this->renderer->renderView('error/404', [
                'title' => 'Salle introuvable',
            ]);
        }

        return $this->renderer->renderView('salle/form', [
            'title' => 'Modifier la salle ' . $salle->nom,
            'salle' => $salle,
            'data'  => [],
        ]);
    }

    public function update(int $id): string
    {
        $salle = $this->salles->getSalleById($id);

        if ($salle === null) {
            http_response_code(404);

            return $this->renderer->renderView('error/404', [
                'title' => 'Salle introuvable',
            ]);
        }

        $input = $_POST;
        $input['active'] = isset($_POST['active']);

        $validation = $this->validator->validate($input);

        if (!$validation->isValid()) {
            return $this->renderer->renderView('salle/form', [
                'title'  => 'Modifier la salle ' . $salle->nom,
                'salle'  => $salle,
                'errors' => $validation->errors(),
                'data'   => $input,
            ]);
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

        return '';
    }
}