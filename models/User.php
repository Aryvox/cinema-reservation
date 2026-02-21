<?php
class User {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    // Inscription
    public function register($email, $password, $nom, $prenom) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (email, password, nom, prenom) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$email, $hashedPassword, $nom, $prenom]);
    }
    
    // Connexion
    public function login($email, $password) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
    
    // Vérifier si email existe
    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() !== false;
    }
    
    // Récupérer utilisateur par ID
    public function getUserById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Modifier compte
    public function updateUser($id, $email, $nom, $prenom) {
        $stmt = $this->pdo->prepare(
            "UPDATE users SET email = ?, nom = ?, prenom = ? WHERE id = ?"
        );
        return $stmt->execute([$email, $nom, $prenom, $id]);
    }
    
    // Supprimer compte
    public function deleteUser($id) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Token "se souvenir de moi"
    public function createRememberToken($userId) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        $stmt = $this->pdo->prepare(
            "INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (?, ?, ?)"
        );
        $stmt->execute([$userId, hash('sha256', $token), $expiresAt]);
        
        return $token;
    }
    
    public function getUserByToken($token) {
        $hashedToken = hash('sha256', $token);
        $stmt = $this->pdo->prepare(
            "SELECT u.* FROM users u 
             JOIN remember_tokens rt ON u.id = rt.user_id 
             WHERE rt.token = ? AND rt.expires_at > NOW()"
        );
        $stmt->execute([$hashedToken]);
        return $stmt->fetch();
    }
    
    public function deleteRememberToken($token) {
        $hashedToken = hash('sha256', $token);
        $stmt = $this->pdo->prepare("DELETE FROM remember_tokens WHERE token = ?");
        return $stmt->execute([$hashedToken]);
    }
}