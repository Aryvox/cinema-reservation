<?php
class Seance {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Récupérer les séances d'un film
    public function getSeancesByFilm($filmId) {
        $stmt = $this->pdo->prepare(
            "SELECT s.*, 
                    (s.places_totales - s.places_reservees) as places_disponibles,
                    f.titre as film_titre
             FROM seances s
             JOIN films f ON s.film_id = f.id
             WHERE s.film_id = ? AND s.date_heure > NOW()
             ORDER BY s.date_heure ASC"
        );
        $stmt->execute([$filmId]);
        return $stmt->fetchAll();
    }
    
    // Récupérer une séance par ID
    public function getSeanceById($id) {
        $stmt = $this->pdo->prepare(
            "SELECT s.*, 
                    (s.places_totales - s.places_reservees) as places_disponibles,
                    f.titre as film_titre
             FROM seances s
             JOIN films f ON s.film_id = f.id
             WHERE s.id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Vérifier disponibilité
    public function checkAvailability($seanceId, $nombrePlaces) {
        $seance = $this->getSeanceById($seanceId);
        if (!$seance) return false;
        
        return ($seance['places_disponibles'] >= $nombrePlaces);
    }
}