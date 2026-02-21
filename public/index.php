<?php
// Affichage des erreurs pour débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$sessionTimeout = 1800; // 30 minutes

// Expiration de session après inactivité
if (isset($_SESSION['user_id'], $_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $sessionTimeout)) {
    if (isset($_COOKIE['remember_token'])) {
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../models/User.php';

        try {
            $db = new Database();
            $userModel = new User($db->connect());
            $userModel->deleteRememberToken($_COOKIE['remember_token']);
        } catch (Throwable $e) {
            // Ignorer l'erreur en phase de nettoyage
        }

        setcookie('remember_token', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
    }

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }

    session_destroy();
    header('Location: /cinema-reservation/public/index.php?page=login&expired=1');
    exit;
}

// Gestion du "se souvenir de moi"
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../models/User.php';

    try {
        $db = new Database();
        $userModel = new User($db->connect());
        $user = $userModel->getUserByToken($_COOKIE['remember_token']);

        if ($user) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['last_activity'] = time();
        } else {
            setcookie('remember_token', '', [
                'expires' => time() - 3600,
                'path' => '/',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
        }
    } catch (Throwable $e) {
        die("Erreur base de données : " . $e->getMessage());
    }
}

if (isset($_SESSION['user_id'])) {
    $_SESSION['last_activity'] = time();
}

// Routage
$page = $_GET['page'] ?? 'films';

switch ($page) {
    // Authentification
    case 'register':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->register();
        } else {
            $controller->showRegister();
        }
        break;

    case 'login':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;

    case 'logout':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->logout();
        break;

    case 'profile':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->showProfile();
        break;

    case 'profile-update':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->updateProfile();
        break;

    case 'delete-account':
        require_once __DIR__ . '/../controllers/AuthController.php';
        $controller = new AuthController();
        $controller->deleteAccount();
        break;

    // Films
    case 'films':
        require_once __DIR__ . '/../controllers/FilmController.php';
        $controller = new FilmController();
        $controller->index();
        break;

    // Séances et réservations
    case 'seances':
        require_once __DIR__ . '/../controllers/ReservationController.php';
        $controller = new ReservationController();
        $controller->showSeances();
        break;

    case 'reserver':
        require_once __DIR__ . '/../controllers/ReservationController.php';
        $controller = new ReservationController();
        $controller->showReservationForm();
        break;

    case 'reserver-confirm':
        require_once __DIR__ . '/../controllers/ReservationController.php';
        $controller = new ReservationController();
        $controller->create();
        break;

    case 'mes-reservations':
        require_once __DIR__ . '/../controllers/ReservationController.php';
        $controller = new ReservationController();
        $controller->myReservations();
        break;

    default:
        http_response_code(404);
        echo "Page non trouvée";
}
