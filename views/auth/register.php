<?php
$errors = $errors ?? [];
$pageTitle = $pageTitle ?? 'Inscription';
?>
<?php $pageTitle = 'Inscription'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <h1>Inscription</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/cinema-reservation/public/index.php?page=register">
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" id="prenom" name="prenom" required 
                       value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" id="nom" name="nom" required 
                       value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
                <small>Au moins 6 caractères</small>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        
        <p class="auth-footer">
            Déjà inscrit ? <a href="/cinema-reservation/public/index.php?page=login">Se connecter</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>