<?php
/** @var array $viewData */
$title = $title ?? $viewData['title'] ?? 'Page introuvable (404)';
?>
<div class="card error-page">
    <div class="error-code">404</div>
    <h2>Page introuvable</h2>
    <p>La ressource ou la page que vous recherchez n'existe pas ou a été déplacée.</p>
    <a href="/salles" class="btn btn-primary">Retour à la liste des salles</a>
</div>