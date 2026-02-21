<?php $pageTitle = 'Connexion'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="auth-container">
    <div class="auth-box">
        <h1>Connexion</h1>

        <?php if (isset($_GET['expired']) && $_GET['expired'] === '1'): ?>
            <div class="alert alert-error">
                Votre session a expiré après inactivité. Veuillez vous reconnecter.
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success']) && $_GET['success'] === 'registered'): ?>
            <div class="alert alert-success">
                Inscription réussie ! Vous pouvez maintenant vous connecter.
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error)): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/cinema-reservation/public/index.php?page=login">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label class="checkbox">
                    <input type="checkbox" name="remember">
                    Se souvenir de moi
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        
        <p class="auth-footer">
            Pas encore inscrit ? <a href="/cinema-reservation/public/index.php?page=register">Créer un compte</a>
        </p>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>