-- Créer la base de données
CREATE DATABASE IF NOT EXISTS cinema CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE cinema;

-- Table des utilisateurs
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table des films
CREATE TABLE films (
    id INT PRIMARY KEY AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    description TEXT,
    duree INT COMMENT 'Durée en minutes',
    affiche VARCHAR(255),
    a_laffiche BOOLEAN DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table des séances
CREATE TABLE seances (
    id INT PRIMARY KEY AUTO_INCREMENT,
    film_id INT NOT NULL,
    date_heure DATETIME NOT NULL,
    places_totales INT DEFAULT 50,
    places_reservees INT DEFAULT 0,
    FOREIGN KEY (film_id) REFERENCES films(id) ON DELETE CASCADE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Table des réservations
CREATE TABLE reservations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    seance_id INT NOT NULL,
    nombre_places INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seance_id) REFERENCES seances(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table pour "se souvenir de moi"
CREATE TABLE remember_tokens (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expires_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Données de test
INSERT INTO films (titre, description, duree, affiche, a_laffiche) VALUES
('Inception', "Dom Cobb est un voleur expérimenté dans l'art périlleux de l'extraction : sa spécialité consiste à s'approprier les secrets les plus précieux d'un individu, enfouis au plus profond de son subconscient, pendant qu'il rêve et que son esprit est particulièrement vulnérable. Très recherché pour ses talents dans l'univers trouble de l'espionnage industriel, Cobb est aussi devenu un fugitif traqué dans le monde entier. Cependant, une ultime mission pourrait lui permettre de retrouver sa vie d'avant.", 148, 'inception.jpg', 1),
('Interstellar', "Dans un proche futur, la Terre est devenue hostile pour l'homme. Les tempêtes de sable sont fréquentes et il n'y a plus que le maïs qui peut être cultivé, en raison d'un sol trop aride. Cooper est un pilote, recyclé en agriculteur, qui vit avec son fils et sa fille dans la ferme familiale. Lorsqu'une force qu'il ne peut expliquer lui indique les coordonnées d'une division secrète de la NASA, il est alors embarqué dans une expédition pour sauver l'humanité.", 169, 'interstellar.jpg', 1),
('The Dark Knight', "Batman est plus que jamais déterminé à éradiquer le crime organisé qui sème la terreur en ville. Epaulé par le lieutenant Jim Gordon et par le procureur de Gotham City, Harvey Dent, Batman voit son champ d'action s'élargir. La collaboration des trois hommes s'avère très efficace et ne tarde pas à porter ses fruits jusqu'à ce qu'un criminel redoutable vienne plonger la ville de Gotham City dans le chaos.", 152, 'the-dark-knight.jpg', 1);

INSERT INTO seances (film_id, date_heure, places_totales) VALUES
(1, '2026-02-25 14:00:00', 50),
(1, '2026-02-25 18:00:00', 50),
(1, '2026-02-25 20:00:00', 50),
(2, '2026-02-25 15:30:00', 60),
(2, '2026-02-25 19:00:00', 60),
(3, '2026-02-25 21:00:00', 40);