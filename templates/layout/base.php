<?php
/** @var array $viewData */
$title        = $title ?? $viewData['title'] ?? 'Gestion des Salles Universitaires';
$contenu      = $contenu ?? $viewData['contenu'] ?? '';
$flashSuccess = $flashSuccess ?? $viewData['flashSuccess'] ?? null;
$flashError   = $flashError ?? $viewData['flashError'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
    <header class="navbar">
        <nav class="nav-container">
            <a href="/salles" class="nav-brand">
                <span class="brand-icon">🏛️</span>
                <span class="brand-name">UnivSalles</span>
            </a>
            <div class="nav-links">
                <a href="/salles" class="nav-link <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/salle') ? 'active' : '' ?>">Salles</a>
                <a href="/reservations" class="nav-link <?= str_starts_with($_SERVER['REQUEST_URI'] ?? '', '/reservation') ? 'active' : '' ?>">Réservations</a>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <?php if (!empty($flashSuccess)): ?>
            <div class="alert alert-success">
                <span class="alert-icon">✓</span>
                <div><?= htmlspecialchars($flashSuccess) ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($flashError)): ?>
            <div class="alert alert-danger">
                <span class="alert-icon">⚠</span>
                <div><?= htmlspecialchars($flashError) ?></div>
            </div>
        <?php endif; ?>

        <?= $contenu ?>
    </main>

    <footer class="footer">
        <p>© <?= date('Y') ?> UnivSalles — Plateforme de gestion des réservations universitaires</p>
    </footer>
</body>
</html>