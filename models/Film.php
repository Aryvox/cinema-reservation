<?php
class Film {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Récupérer tous les films à l'affiche
    public function getAllFilms() {
        $stmt = $this->pdo->prepare("SELECT * FROM films WHERE a_laffiche = ? ORDER BY created_at DESC");
        $stmt->execute([1]);
        return $stmt->fetchAll();
    }
    
    // Récupérer un film par ID
    public function getFilmById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM films WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}