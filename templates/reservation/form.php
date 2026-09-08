<?php
/** @var array $viewData */
$title            = $title ?? $viewData['title'] ?? 'Réserver une salle';
$salles           = $salles ?? $viewData['salles'] ?? [];
$erreurs          = $erreurs ?? $errors ?? $viewData['errors'] ?? $viewData['erreurs'] ?? [];
$anciennesValeurs = $anciennesValeurs ?? $data ?? $viewData['data'] ?? $viewData['anciennesValeurs'] ?? [];
$erreurGlobale    = $erreurGlobale ?? $generalError ?? $viewData['generalError'] ?? $viewData['erreurGlobale'] ?? $erreurs['global'] ?? null;
?>

<div class="page-header">
    <div>
        <h1>Réserver une salle</h1>
        <p class="subtitle">Enregistrez un nouveau créneau de réservation</p>
    </div>
</div>

<div class="card" style="max-width: 650px;">
    <?php if ($erreurGlobale !== null): ?>
        <div class="alert alert-danger">
            <span class="alert-icon">⚠</span>
            <div><?= htmlspecialchars($erreurGlobale) ?></div>
        </div>
    <?php endif; ?>

    <form method="post" action="/reservations" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div class="form-group">
            <label for="salle_id">Salle souhaitée *</label>
            <select id="salle_id" name="salle_id" class="form-control <?= isset($erreurs['salle_id']) ? 'is-invalid' : '' ?>" required>
                <option value="">-- Choisir une salle --</option>
                <?php foreach ($salles as $salle): ?>
                    <option value="<?= $salle->id ?>" <?= ((string) ($anciennesValeurs['salle_id'] ?? '')) === ((string) $salle->id) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($salle->nom) ?> (<?= htmlspecialchars($salle->batiment) ?> - <?= htmlspecialchars((string) $salle->capacite) ?> places)
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($erreurs['salle_id'])): ?>
                <span class="erreur-text"><?= htmlspecialchars($erreurs['salle_id']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="responsable">Nom du responsable *</label>
            <input 
                type="text" 
                id="responsable"
                name="responsable" 
                class="form-control <?= isset($erreurs['responsable']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($anciennesValeurs['responsable'] ?? '') ?>" 
                placeholder="Ex: Pr. Dupont"
                required
            >
            <?php if (isset($erreurs['responsable'])): ?>
                <span class="erreur-text"><?= htmlspecialchars($erreurs['responsable']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="email">Adresse email de contact *</label>
            <input 
                type="email" 
                id="email"
                name="email" 
                class="form-control <?= isset($erreurs['email']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($anciennesValeurs['email'] ?? '') ?>" 
                placeholder="exemple@universite.fr"
                required
            >
            <?php if (isset($erreurs['email'])): ?>
                <span class="erreur-text"><?= htmlspecialchars($erreurs['email']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="motif">Motif de la réservation *</label>
            <input 
                type="text" 
                id="motif"
                name="motif" 
                class="form-control <?= isset($erreurs['motif']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($anciennesValeurs['motif'] ?? '') ?>" 
                placeholder="Ex: Soutenance de thèse, Cours magistral..."
                required
            >
            <?php if (isset($erreurs['motif'])): ?>
                <span class="erreur-text"><?= htmlspecialchars($erreurs['motif']) ?></span>
            <?php endif; ?>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <div class="form-group">
                <label for="date_debut">Date & Heure de début *</label>
                <input 
                    type="datetime-local" 
                    id="date_debut"
                    name="date_debut" 
                    class="form-control <?= isset($erreurs['date_debut']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($anciennesValeurs['date_debut'] ?? '') ?>" 
                    required
                >
                <?php if (isset($erreurs['date_debut'])): ?>
                    <span class="erreur-text"><?= htmlspecialchars($erreurs['date_debut']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="date_fin">Date & Heure de fin *</label>
                <input 
                    type="datetime-local" 
                    id="date_fin"
                    name="date_fin" 
                    class="form-control <?= isset($erreurs['date_fin']) ? 'is-invalid' : '' ?>"
                    value="<?= htmlspecialchars($anciennesValeurs['date_fin'] ?? '') ?>" 
                    required
                >
                <?php if (isset($erreurs['date_fin'])): ?>
                    <span class="erreur-text"><?= htmlspecialchars($erreurs['date_fin']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Confirmer la réservation</button>
            <a href="/reservations" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>