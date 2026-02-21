<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Film.php';
require_once __DIR__ . '/../models/Seance.php';
require_once __DIR__ . '/../models/Reservation.php';

class ReservationController {
    private const BASE_URL = '/cinema-reservation/public/index.php?page=';

    private $filmModel;
    private $seanceModel;
    private $reservationModel;
    
    public function __construct() {
        $db = new Database();
        $pdo = $db->connect();
        $this->filmModel = new Film($pdo);
        $this->seanceModel = new Seance($pdo);
        $this->reservationModel = new Reservation($pdo);
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

    private function getPositiveIntFromGet(string $key): ?int {
        $value = filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT);
        if ($value === false || $value === null || $value <= 0) {
            return null;
        }

        return $value;
    }

    private function getPositiveIntFromPost(string $key): ?int {
        $value = filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT);
        if ($value === false || $value === null || $value <= 0) {
            return null;
        }

        return $value;
    }

    private function renderReservationForm(array $seance, ?string $error = null): void {
        require __DIR__ . '/../views/reservations/create.php';
    }
    
    // Afficher les séances d'un film
    public function showSeances() {
        $this->ensureAuthenticated();

        $filmId = $this->getPositiveIntFromGet('film_id');
        if ($filmId === null) {
            $this->redirect('films');
        }

        $film = $this->filmModel->getFilmById($filmId);
        if (!$film) {
            $this->redirect('films');
        }

        $seances = $this->seanceModel->getSeancesByFilm($filmId);

        require __DIR__ . '/../views/reservations/seances.php';
    }
    
    // Afficher formulaire de réservation
    public function showReservationForm() {
        $this->ensureAuthenticated();

        $seanceId = $this->getPositiveIntFromGet('seance_id');
        if ($seanceId === null) {
            $this->redirect('films');
        }

        $seance = $this->seanceModel->getSeanceById($seanceId);
        if (!$seance) {
            $this->redirect('films');
        }

        $this->renderReservationForm($seance);
    }
    
    // Traiter la réservation
    public function create() {
        $this->ensureAuthenticated();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('films');
        }

        $seanceId = $this->getPositiveIntFromPost('seance_id');
        $nombrePlaces = $this->getPositiveIntFromPost('nombre_places');

        if ($seanceId === null || $nombrePlaces === null) {
            $this->redirect('films');
        }

        $seance = $this->seanceModel->getSeanceById($seanceId);
        if (!$seance) {
            $this->redirect('films');
        }

        if ($nombrePlaces < 1 || $nombrePlaces > 10) {
            $error = 'Nombre de places invalide';
            $this->renderReservationForm($seance, $error);
            return;
        }

        if (!$this->seanceModel->checkAvailability($seanceId, $nombrePlaces)) {
            $error = 'Pas assez de places disponibles';
            $this->renderReservationForm($seance, $error);
            return;
        }

        $created = $this->reservationModel->createReservation((int) $_SESSION['user_id'], $seanceId, $nombrePlaces);
        if ($created) {
            header('Location: ' . self::BASE_URL . 'mes-reservations&success=1');
            exit;
        }

        $error = 'Erreur lors de la réservation';
        $seance = $this->seanceModel->getSeanceById($seanceId);
        if (!$seance) {
            $this->redirect('films');
        }

        $this->renderReservationForm($seance, $error);
    }
    
    // Afficher les réservations de l'utilisateur
    public function myReservations() {
        $this->ensureAuthenticated();

        $reservations = $this->reservationModel->getReservationsByUser((int) $_SESSION['user_id']);
        require __DIR__ . '/../views/reservations/my-reservations.php';
    }
}