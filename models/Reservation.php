<?php
class Reservation {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Créer une réservation
    public function createReservation($userId, $seanceId, $nombrePlaces) {
        try {
            $this->pdo->beginTransaction();
            
            // Vérifier disponibilité
            $stmt = $this->pdo->prepare(
                "SELECT (places_totales - places_reservees) as disponibles 
                 FROM seances WHERE id = ? FOR UPDATE"
            );
            $stmt->execute([$seanceId]);
            $seance = $stmt->fetch();

            if (!$seance) {
                $this->pdo->rollBack();
                return false;
            }
            
            if ($seance['disponibles'] < $nombrePlaces) {
                $this->pdo->rollBack();
                return false;
            }
            
            // Créer la réservation
            $stmt = $this->pdo->prepare(
                "INSERT INTO reservations (user_id, seance_id, nombre_places) VALUES (?, ?, ?)"
            );
            $stmt->execute([$userId, $seanceId, $nombrePlaces]);
            
            // Mettre à jour les places réservées
            $stmt = $this->pdo->prepare(
                "UPDATE seances SET places_reservees = places_reservees + ? WHERE id = ?"
            );
            $stmt->execute([$nombrePlaces, $seanceId]);
            
            $this->pdo->commit();
            return true;
            
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            return false;
        }
    }
    
    // Récupérer les réservations d'un utilisateur
    public function getReservationsByUser($userId) {
        $stmt = $this->pdo->prepare(
            "SELECT r.*, s.date_heure, f.titre as film_titre
             FROM reservations r
             JOIN seances s ON r.seance_id = s.id
             JOIN films f ON s.film_id = f.id
             WHERE r.user_id = ?
             ORDER BY s.date_heure DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
}