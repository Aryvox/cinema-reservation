<?php $pageTitle = 'Réserver'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <a href="/cinema-reservation/public/index.php?page=seances&film_id=<?= $seance['film_id'] ?>" 
       class="back-link">← Retour aux séances</a>
    
    <h1>Réserver vos places</h1>
    
    <div class="reservation-details">
        <h2><?= htmlspecialchars($seance['film_titre']) ?></h2>
        <p><?= date('l j F Y à H:i', strtotime($seance['date_heure'])) ?></p>
        <p><?= $seance['places_disponibles'] ?> places disponibles</p>
    </div>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <form method="POST" action="/cinema-reservation/public/index.php?page=reserver-confirm" class="reservation-form">
        <input type="hidden" name="seance_id" value="<?= $seance['id'] ?>">
        
        <div class="form-group">
            <label for="nombre_places">Nombre de places</label>
            <input type="number" id="nombre_places" name="nombre_places" 
                   min="1" max="<?= min(10, $seance['places_disponibles']) ?>" 
                   value="1" required>
            <small>Maximum 10 places par réservation</small>
        </div>
        
        <button type="submit" class="btn btn-primary btn-large">Confirmer la réservation</button>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>