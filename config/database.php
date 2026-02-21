<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'cinema';
    private $username = 'root';
    private $password = 'HhD~,aJjNy3A5c=';
    private $pdo;
    
    public function connect() {
        if ($this->pdo === null) {
            try {
                $this->pdo = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                    $this->username,
                    $this->password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch(PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }
        return $this->pdo;
    }
}