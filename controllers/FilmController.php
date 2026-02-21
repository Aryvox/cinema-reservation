<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Film.php';

class FilmController {
    private const BASE_URL = '/cinema-reservation/public/index.php?page=';

    private $filmModel;
    
    public function __construct() {
        $db = new Database();
        $this->filmModel = new Film($db->connect());
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
    
    // Afficher tous les films
    public function index() {
        $this->ensureAuthenticated();

        $films = $this->filmModel->getAllFilms();
        require __DIR__ . '/../views/films/index.php';
    }
}