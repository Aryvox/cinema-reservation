<?php $pageTitle = 'Séances - ' . ($film['titre'] ?? ''); ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <a href="/cinema-reservation/public/index.php?page=films" class="back-link">← Retour aux films</a>
    
    <h1>Séances : <?= htmlspecialchars($film['titre']) ?></h1>
    
    <?php if (empty($seances)): ?>
        <p class="no-data">Aucune séance disponible pour ce film.</p>
    <?php else: ?>
        <div class="seances-list">
            <?php foreach ($seances as $seance): ?>
                <div class="seance-card">
                    <div class="seance-info">
                        <h3><?= date('l j F Y', strtotime($seance['date_heure'])) ?></h3>
                        <p class="seance-time"><?= date('H:i', strtotime($seance['date_heure'])) ?></p>
                        <p class="seance-seats">
                            <?= $seance['places_disponibles'] ?> places disponibles
                        </p>
                    </div>
                    
                    <?php if ($seance['places_disponibles'] > 0): ?>
                        <a href="/cinema-reservation/public/index.php?page=reserver&seance_id=<?= $seance['id'] ?>" 
                           class="btn btn-primary">Réserver</a>
                    <?php else: ?>
                        <button class="btn btn-disabled" disabled>Complet</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>