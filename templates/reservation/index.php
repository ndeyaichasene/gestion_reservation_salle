<?php

/** @var array $viewData */

$title = $title ?? $viewData['title'] ?? 'Gestion des Réservations';

$reservations = $reservations ?? $viewData['reservations'] ?? [];

$salles = $salles ?? $viewData['salles'] ?? [];

$salleId = $salleId ?? $selectedSalleId ?? $viewData['selectedSalleId'] ?? $viewData['salleId'] ?? null;

$pagination = $viewData['pagination'] ?? [];

$emptyMessage = $viewData['emptyMessage'] ?? null;

?>

<div class="page-header">
    <div>
        <h1>Gestion des Réservations</h1>
        <p class="subtitle">
            Consultez et gérez les créneaux réservés sur le campus
        </p>
    </div>

    <a href="/reservations/create" class="btn btn-primary">
        + Nouvelle réservation
    </a>
</div>

<div class="filter-bar">
    <form
        method="get"
        action="/reservations"
        style="display: flex; align-items: center; gap: 0.75rem; background: transparent; padding: 0; box-shadow: none;">

        <label for="salle_id">
            Filtrer par salle :
        </label>

        <select
            id="salle_id"
            name="salle_id"
            onchange="this.form.submit()">
            <option value="">
                -- Toutes les salles --
            </option>

            <?php foreach ($salles as $salle): ?>

                <option
                    value="<?= $salle->id ?>"
                    <?= $salleId === (int) $salle->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($salle->nom) ?>
                    (<?= htmlspecialchars($salle->batiment) ?>)
                </option>

            <?php endforeach; ?>

        </select>

        <?php if ($salleId !== null): ?>

            <a
                href="/reservations"
                class="btn btn-secondary btn-sm">
                Réinitialiser
            </a>

        <?php endif; ?>

    </form>
</div>

<div class="table-card">

    <table>

        <thead>
            <tr>
                <th>ID</th>
                <th>Salle</th>
                <th>Responsable</th>
                <th>Date & Heure de début</th>
                <th>Date & Heure de fin</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>

            <?php if ($emptyMessage !== null): ?>
                <tr>
                    <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 2rem;"> <?= htmlspecialchars($emptyMessage) ?> </td>
                </tr> <?php else: ?>

                <?php foreach ($reservations as $reservation): ?>

                    <tr>

                        <td>#<?= htmlspecialchars((string) $reservation->id) ?></td>

                        <td><strong> <?= htmlspecialchars($reservation->salle->nom ?? 'N/A') ?> </strong></td>

                        <td> <?= htmlspecialchars($reservation->responsable) ?> </td>

                        <td> <?= $reservation->date_debut ? $reservation->date_debut->format('d/m/Y à H:i') : '' ?> </td>
                        <td> <?= $reservation->date_fin ? $reservation->date_fin->format('d/m/Y à H:i') : '' ?> </td>
                        <td>
                            <?php if ($reservation->statut === 'confirmee'): ?>
                                <span class="badge badge-success">Confirmée</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Annulée</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>" class="btn btn-secondary btn-sm"> Détails</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($pagination['show']): ?>
        <div class="pagination">
            <?php if ($pagination['hasPrevious']): ?>
                <a href="<?= $pagination['previousUrl'] ?>" class="btn btn-secondary"> &lt; </a>
            <?php endif; ?>
            <?php foreach ($pagination['pages'] as $page): ?>
                <?php if ($page['number'] === $pagination['current']): ?>
                    <span class="btn btn-primary"> <?= $page['number'] ?> </span>
                <?php else: ?>
                    <a href="<?= $page['url'] ?>" class="btn btn-secondary"> <?= $page['number'] ?> </a>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if ($pagination['hasNext']): ?>
                <a href="<?= $pagination['nextUrl'] ?>" class="btn btn-secondary"> &gt; </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>