<?php

/** @var array $viewData */
$title  = $title ?? $viewData['title'] ?? 'Parc des Salles';
$salles = $salles ?? $viewData['salles'] ?? [];
$pagination = $viewData['pagination'] ?? [];
$current = $pagination['current'] ?? 1;
$pages = $pagination['pages'] ?? [];
?>

<div class="page-header">
    <div>
        <h1>Parc des Salles</h1>
        <p class="subtitle">Consultez l'ensemble des salles d'enseignement et d'événements</p>
    </div>
    <a href="/salles/create" class="btn btn-primary">+ Ajouter une salle</a>
</div>

<div class="table-card">
    <table>
        <thead>
            <tr>
                <th>Nom de la salle</th>
                <th>Bâtiment</th>
                <th>Capacité</th>
                <th>Type d'espace</th>
                <th>Statut</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($salles)): ?>
                <tr>
                    <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 2.5rem;">
                        Aucune salle enregistrée pour le moment.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($salles as $salle): ?>
                    <tr>
                        <td>
                            <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>" class="salle-name-link">
                                <?= htmlspecialchars($salle->nom) ?>
                            </a>
                        </td>
                        <td><span class="batiment-tag"><?= htmlspecialchars($salle->batiment) ?></span></td>
                        <td><strong><?= htmlspecialchars((string) $salle->capacite) ?></strong> places</td>
                        <td>
                            <span class="badge badge-type badge-type-<?= htmlspecialchars($salle->type) ?>">
                                <?= htmlspecialchars(ucfirst($salle->type)) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($salle->active): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td style="text-align: right;">
                            <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>" class="btn btn-secondary btn-sm">
                                Voir
                            </a>
                            <a href="/salles/<?= htmlspecialchars((string) $salle->id) ?>/edit" class="btn btn-secondary btn-sm">
                                Modifier
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if (!empty($pages)): ?>
        <div class="pagination">

            <?php if ($pagination['hasPrevious']): ?>
                <a href="?page=<?= $pagination['previous'] ?>" class="btn btn-secondary">
                    &lt;
                </a>
            <?php endif; ?>

            <?php foreach ($pages as $page): ?>
                <?php if ($page === $current): ?>
                    <span class="btn btn-primary">
                        <?= $page ?>
                    </span>
                <?php else: ?>
                    <a href="?page=<?= $page ?>" class="btn btn-secondary">
                        <?= $page ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if ($pagination['hasNext']): ?>
                <a href="?page=<?= $pagination['next'] ?>" class="btn btn-secondary">
                    &gt;
                </a>
            <?php endif; ?>

        </div>
    <?php endif; ?>
</div>