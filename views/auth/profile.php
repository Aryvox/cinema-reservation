<?php $pageTitle = 'Mon Profil'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container">
    <h1>Mon Profil</h1>
    
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    
    <?php if (isset($error)): ?>
        <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <div class="profile-section">
        <h2>Modifier mes informations</h2>
        <form method="POST" action="/cinema-reservation/public/index.php?page=profile-update">
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required 
                       value="<?= htmlspecialchars($user['prenom']) ?>">
            </div>
            
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required 
                       value="<?= htmlspecialchars($user['nom']) ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?= htmlspecialchars($user['email']) ?>">
            </div>
            
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>
    </div>
    
    <div class="profile-section danger-zone">
        <h2>Zone dangereuse</h2>
        <p>La suppression de votre compte est irréversible.</p>
        <form method="POST" action="/cinema-reservation/public/index.php?page=delete-account" 
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer votre compte ?');">
            <input type="hidden" name="confirm_delete" value="1">
            <button type="submit" class="btn btn-danger">Supprimer mon compte</button>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>