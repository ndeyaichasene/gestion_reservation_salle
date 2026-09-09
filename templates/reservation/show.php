<?php
/** @var array $viewData */
$reservation = $reservation ?? $viewData['reservation'] ?? null;
$title       = $title ?? $viewData['title'] ?? ($reservation ? 'Réservation #' . $reservation->id : 'Détail de la réservation');
?>

<div class="page-header">
    <div>
        <h1>Réservation #<?= htmlspecialchars((string) ($reservation->id ?? '')) ?></h1>
        <p class="subtitle">Détails de la réservation universitaire</p>
    </div>
    <div>
        <?php if ($reservation->statut === 'confirmee'): ?>
            <span class="badge badge-success" style="font-size: 0.9rem; padding: 0.4rem 1rem;">Confirmée</span>
        <?php else: ?>
            <span class="badge badge-danger" style="font-size: 0.9rem; padding: 0.4rem 1rem;">Annulée</span>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="detail-grid">
        <div class="detail-item">
            <div class="label">Salle concernée</div>
            <div class="value">
                <a href="/salles/<?= htmlspecialchars((string) ($reservation->salle_id ?? '')) ?>">
                    <?= htmlspecialchars($reservation->salle->nom ?? 'Salle #' . $reservation->salle_id) ?>
                </a>
            </div>
        </div>

        <div class="detail-item">
            <div class="label">Responsable</div>
            <div class="value"><?= htmlspecialchars($reservation->responsable) ?></div>
        </div>

        <div class="detail-item">
            <div class="label">Email de contact</div>
            <div class="value" style="font-size: 0.95rem; word-break: break-all;">
                <?= htmlspecialchars($reservation->email) ?>
            </div>
        </div>

        <div class="detail-item">
            <div class="label">Motif de la réservation</div>
            <div class="value" style="font-size: 0.95rem;">
                <?= htmlspecialchars($reservation->motif) ?>
            </div>
        </div>

        <div class="detail-item">
            <div class="label">Début du créneau</div>
            <div class="value">
                <?= $reservation->date_debut ? $reservation->date_debut->format('d/m/Y à H:i') : '' ?>
            </div>
        </div>

        <div class="detail-item">
            <div class="label">Fin du créneau</div>
            <div class="value">
                <?= $reservation->date_fin ? $reservation->date_fin->format('d/m/Y à H:i') : '' ?>
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; border-top: 1px solid var(--border); padding-top: 1.25rem; flex-wrap: wrap; gap: 1rem;">
        <a href="/reservations" class="btn btn-secondary">
            ← Retour à la liste des réservations
        </a>

        <?php if ($reservation->statut === 'confirmee'): ?>
            <form method="post" action="/reservations/<?= htmlspecialchars((string) $reservation->id) ?>/cancel" style="display: inline; margin: 0; padding: 0; box-shadow: none; background: transparent;" onsubmit="return confirm('Confirmez-vous l\'annulation de cette réservation ?');">
                <button type="submit" class="btn btn-danger">
                    Annuler cette réservation
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>