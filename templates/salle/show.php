<?php
/** @var array $viewData */
$salle = $salle ?? $viewData['salle'] ?? null;
$title = $title ?? $viewData['title'] ?? ($salle ? 'Salle ' . $salle->nom : 'Détail de la salle');
?>
<div class="page-header">
    <div>
        <h1>Salle <?= htmlspecialchars($salle->nom ?? '') ?></h1>
        <p class="subtitle">Fiche détaillée de la salle universitaire</p>
    </div>
    <div style="display: flex; gap: 0.75rem;">
        <a href="/salles/<?= htmlspecialchars((string) ($salle->id ?? '')) ?>/edit" class="btn btn-secondary">
            Modifier la salle
        </a>
        <a href="/reservations/create" class="btn btn-primary">
            + Réserver cette salle
        </a>
    </div>
</div>

<div class="card">
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Bâtiment</div>
            <div class="value"><?= htmlspecialchars($salle->batiment ?? '') ?></div>
        </div>
        <div class="detail-item">
            <div class="label">Capacité d'accueil</div>
            <div class="value"><?= htmlspecialchars((string) ($salle->capacite ?? '')) ?> places</div>
        </div>
        <div class="detail-item">
            <div class="label">Type d'espace</div>
            <div class="value">
                <span class="badge badge-type badge-type-<?= htmlspecialchars($salle->type ?? '') ?>">
                    <?= htmlspecialchars(ucfirst($salle->type ?? '')) ?>
                </span>
            </div>
        </div>
        <div class="detail-item">
            <div class="label">Disponibilité / Statut</div>
            <div class="value">
                <?php if ($salle && $salle->active): ?>
                    <span class="badge badge-success">Active</span>
                <?php else: ?>
                    <span class="badge badge-danger">Inactive</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div style="margin-top: 1.75rem; border-top: 1px solid var(--border); padding-top: 1.25rem;">
        <a href="/salles" class="btn btn-secondary">
            ← Retour à la liste des salles
        </a>
    </div>
</div>