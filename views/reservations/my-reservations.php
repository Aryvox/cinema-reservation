<?php $pageTitle = 'Mes réservations'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Mes réservations</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Réservation effectuée avec succès !</div>
    <?php endif; ?>
    
    <?php if (empty($reservations)): ?>
        <p class="no-data">Vous n'avez aucune réservation.</p>
        <a href="/cinema-reservation/public/index.php?page=films" class="btn btn-primary">Découvrir les films</a>
    <?php else: ?>
        <div class="reservations-list">
            <?php foreach ($reservations as $reservation): ?>
                <div class="reservation-card">
                    <h3><?= htmlspecialchars($reservation['film_titre']) ?></h3>
                    <p><?= date('l j F Y à H:i', strtotime($reservation['date_heure'])) ?></p>
                    <p><?= $reservation['nombre_places'] ?> place(s)</p>
                    <p class="reservation-date">Réservé le <?= date('j/m/Y', strtotime($reservation['created_at'])) ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>