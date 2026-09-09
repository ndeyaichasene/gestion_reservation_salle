<?php
/** @var array $viewData */
$title = $title ?? $viewData['title'] ?? 'Méthode non autorisée (405)';
?>
<div class="card error-page">
    <div class="error-code">405</div>
    <h2>Méthode non autorisée</h2>
    <p>Cette méthode HTTP n'est pas autorisée pour l'URL demandée.</p>
    <a href="/salles" class="btn btn-primary">Retour à la liste des salles</a>
</div>