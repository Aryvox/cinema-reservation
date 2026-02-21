<?php $pageTitle = 'Films à l\'affiche'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Films à l'affiche</h1>
    
    <div class="films-grid">
        <?php foreach ($films as $film): ?>
            <div class="film-card">
                <div class="film-poster">
                    <?php if ($film['affiche']): ?>
                        <img src="/cinema-reservation/public/images/<?= htmlspecialchars($film['affiche']) ?>" 
                             alt="<?= htmlspecialchars($film['titre']) ?>">
                    <?php else: ?>
                        <div class="no-poster"></div>
                    <?php endif; ?>
                </div>
                
                <div class="film-info">
                    <h3><?= htmlspecialchars($film['titre']) ?></h3>
                    <p class="film-duration"><?= $film['duree'] ?> min</p>
                    <p class="film-description"><?= htmlspecialchars($film['description']) ?></p>
                    <a href="/cinema-reservation/public/index.php?page=seances&film_id=<?= $film['id'] ?>" 
                       class="btn btn-primary">Voir les séances</a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>