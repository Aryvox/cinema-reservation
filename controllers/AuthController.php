<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private const BASE_URL = '/cinema-reservation/public/index.php?page=';

    private $userModel;

    public function __construct() {
        try {
            $db = new Database();
            $this->userModel = new User($db->connect());
        } catch (Throwable $e) {
            die("Erreur de connexion à la base : " . $e->getMessage());
        }
    }

    private function redirect(string $page): void {
        header('Location: ' . self::BASE_URL . $page);
        exit;
    }

    private function ensureAuthenticated(): void {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }

    private function rememberCookieOptions(int $expires): array {
        return [
            'expires' => $expires,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax'
        ];
    }

    // Afficher formulaire d'inscription
    public function showRegister() {
        $errors = [];
        $pageTitle = 'Inscription';
        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Traiter l'inscription
    public function register() {
        $errors = [];
        $pageTitle = 'Inscription';

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../views/auth/register.php';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }
        if (strlen($password) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères";
        }
        if ($password !== $confirmPassword) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }
        if ($this->userModel->emailExists($email)) {
            $errors[] = "Cet email est déjà utilisé";
        }

        if (empty($errors)) {
            if ($this->userModel->register($email, $password, $nom, $prenom)) {
                $this->redirect('login&success=registered');
            }

            $errors[] = "Erreur lors de l'inscription";
        }

        require_once __DIR__ . '/../views/auth/register.php';
    }

    // Afficher formulaire de connexion
    public function showLogin() {
        $pageTitle = 'Connexion';
        $error = null;
        require_once __DIR__ . '/../views/auth/login.php';
    }

    // Traiter la connexion
    public function login() {
        $pageTitle = 'Connexion';
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../views/auth/login.php';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        $user = $this->userModel->login($email, $password);
        if (!$user) {
            $error = "Email ou mot de passe incorrect";
            require_once __DIR__ . '/../views/auth/login.php';
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['last_activity'] = time();

        if ($remember) {
            $token = $this->userModel->createRememberToken($user['id']);
            setcookie('remember_token', $token, $this->rememberCookieOptions(time() + (30 * 24 * 3600)));
        }

        $this->redirect('films');
    }

    // Déconnexion
    public function logout() {
        if (isset($_COOKIE['remember_token'])) {
            $this->userModel->deleteRememberToken($_COOKIE['remember_token']);
            setcookie('remember_token', '', $this->rememberCookieOptions(time() - 3600));
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        $this->redirect('login');
    }

    // Afficher le profil
    public function showProfile() {
        $this->ensureAuthenticated();

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $pageTitle = 'Profil';
        require_once __DIR__ . '/../views/auth/profile.php';
    }

    // Modifier le profil
    public function updateProfile() {
        $this->ensureAuthenticated();

        $user = $this->userModel->getUserById($_SESSION['user_id']);
        $pageTitle = 'Profil';

        $success = '';
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $nom = trim($_POST['nom'] ?? '');
            $prenom = trim($_POST['prenom'] ?? '');

            if ($this->userModel->updateUser($_SESSION['user_id'], $email, $nom, $prenom)) {
                $_SESSION['user_email'] = $email;
                $_SESSION['user_nom'] = $nom;
                $success = "Profil mis à jour avec succès";
            } else {
                $error = "Erreur lors de la mise à jour";
            }

            $user = $this->userModel->getUserById($_SESSION['user_id']);
        }

        require_once __DIR__ . '/../views/auth/profile.php';
    }

    // Supprimer le compte
    public function deleteAccount() {
        $this->ensureAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
            $this->userModel->deleteUser($_SESSION['user_id']);
            $this->logout();
        }
    }
}
