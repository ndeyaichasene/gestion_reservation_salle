<?php
/** @var array $viewData */
$salle            = $salle ?? $viewData['salle'] ?? null;
$title            = $title ?? $viewData['title'] ?? ($salle ? 'Modifier la salle ' . $salle->nom : 'Ajouter une nouvelle salle');
$erreurs          = $erreurs ?? $errors ?? $viewData['errors'] ?? $viewData['erreurs'] ?? [];
$anciennesValeurs = $anciennesValeurs ?? $data ?? $viewData['data'] ?? $viewData['anciennesValeurs'] ?? [];
$erreurGlobale    = $erreurGlobale ?? $viewData['erreurGlobale'] ?? $erreurs['global'] ?? null;
$isEdit           = $salle !== null;
$formAction       = $isEdit ? "/salles/{$salle->id}/edit" : "/salles";
?>

<div class="page-header">
    <div>
        <h1><?= $isEdit ? 'Modifier la salle ' . htmlspecialchars($salle->nom) : 'Ajouter une nouvelle salle' ?></h1>
        <p class="subtitle">Renseignez les caractéristiques de la salle d'enseignement</p>
    </div>
</div>

<div class="card" style="max-width: 620px;">
    <?php if ($erreurGlobale !== null): ?>
        <div class="alert alert-danger">
            <span class="alert-icon">⚠</span>
            <div><?= htmlspecialchars($erreurGlobale) ?></div>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= $formAction ?>" style="display: flex; flex-direction: column; gap: 1.25rem;">
        <div class="form-group">
            <label for="nom">Nom de la salle *</label>
            <input 
                type="text" 
                id="nom"
                name="nom" 
                class="form-control <?= isset($erreurs['nom']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($anciennesValeurs['nom'] ?? $salle->nom ?? '') ?>" 
                required
            >
            <?php if (isset($erreurs['nom'])): ?>
                <span class="erreur-text"><?= htmlspecialchars($erreurs['nom']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="batiment">Bâtiment *</label>
            <input 
                type="text" 
                id="batiment"
                name="batiment" 
                class="form-control <?= isset($erreurs['batiment']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars($anciennesValeurs['batiment'] ?? $salle->batiment ?? '') ?>" 
                required
            >
            <?php if (isset($erreurs['batiment'])): ?>
                <span class="erreur-text"><?= htmlspecialchars($erreurs['batiment']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="capacite">Capacité d'accueil (places) *</label>
            <input 
                type="number" 
                id="capacite"
                name="capacite" 
                min="1"
                class="form-control <?= isset($erreurs['capacite']) ? 'is-invalid' : '' ?>"
                value="<?= htmlspecialchars((string) ($anciennesValeurs['capacite'] ?? $salle->capacite ?? '')) ?>" 
                required
            >
            <?php if (isset($erreurs['capacite'])): ?>
                <span class="erreur-text"><?= htmlspecialchars($erreurs['capacite']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="type">Type d'espace *</label>
            <select id="type" name="type" class="form-control <?= isset($erreurs['type']) ? 'is-invalid' : '' ?>">
                <?php 
                $types = ['cours', 'informatique', 'laboratoire', 'amphitheatre', 'reunion'];
                $valeurSelectionnee = $anciennesValeurs['type'] ?? $salle->type ?? 'cours';
                foreach ($types as $type): 
                ?>
                    <option value="<?= $type ?>" <?= $valeurSelectionnee === $type ? 'selected' : '' ?>>
                        <?= ucfirst($type) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?php if (isset($erreurs['type'])): ?>
                <span class="erreur-text"><?= htmlspecialchars($erreurs['type']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-check">
            <input 
                type="checkbox" 
                id="active"
                name="active" 
                value="1" 
                <?= ($anciennesValeurs['active'] ?? $salle->active ?? true) ? 'checked' : '' ?>
            >
            <label for="active">Salle active (ouverte aux réservations)</label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $isEdit ? 'Enregistrer les modifications' : 'Créer la salle' ?>
            </button>
            <a href="/salles" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>