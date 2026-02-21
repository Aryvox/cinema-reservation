<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Cinéma Réservation' ?></title>
    <link rel="stylesheet" href="/cinema-reservation/public/css/style.css">
</head>
<body>
<header>
    <nav>
        <div class="nav-container">
            <a href="/cinema-reservation/public/index.php?page=films" class="logo">Truc de Cinema</a>

            <?php if (!empty($_SESSION['user_id'])): ?>
                <ul class="nav-menu">
                    <li><a href="/cinema-reservation/public/index.php?page=films">Films</a></li>
                    <li><a href="/cinema-reservation/public/index.php?page=mes-reservations">Mes réservations</a></li>
                    <li><a href="/cinema-reservation/public/index.php?page=profile">Profil</a></li>
                    <li><a href="/cinema-reservation/public/index.php?page=logout">Déconnexion</a></li>
                </ul>
                <span class="user-info">Bonjour, <?= htmlspecialchars($_SESSION['user_nom'] ?? 'Utilisateur') ?></span>
            <?php else: ?>
                <ul class="nav-menu">
                    <li><a href="/cinema-reservation/public/index.php?page=login">Connexion</a></li>
                    <li><a href="/cinema-reservation/public/index.php?page=register">Inscription</a></li>
                </ul>
            <?php endif; ?>
        </div>
    </nav>
</header>

<main>
