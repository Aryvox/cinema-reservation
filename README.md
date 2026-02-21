# Application web de réservation de cinéma (PHP MVC)

Projet PHP sans framework pour la réservation de places de cinéma, avec architecture MVC, authentification par session et base MySQL (PDO).

## Prérequis

- PHP
- MySQL
- Serveur local (WAMP)

## Installation

1. Cloner le projet dans le dossier web (exemple WAMP):
   - `c:/wamp64/www/cinema-reservation`
2. Créer la base de données via le script:
   - exécuter `database/schema.sql` dans phpMyAdmin ou MySQL CLI.
3. Configurer la connexion BDD dans `config/database.php`:
   - `host`, `dbname`, `username`, `password`.

## Lancement

1. Démarrer Apache + MySQL.
2. Ouvrir dans le navigateur:
   - `http://localhost/cinema-reservation/public/index.php`

## Comptes et flux principal

1. Créer un compte via la page d'inscription.
2. Se connecter.
3. Consulter les films à l'affiche.
4. Ouvrir les séances d'un film.
5. Réserver une ou plusieurs places.
6. Consulter "Mes réservations".

## Fonctionnalités implémentées

- Inscription utilisateur
- Connexion sécurisée (mot de passe hashé)
- Option "se souvenir de moi"
- Déconnexion
- Modification / suppression de compte
- Réservation avec vérification de disponibilité
- Historique des réservations utilisateur
- Protection SQL via PDO + requêtes préparées
- Protection XSS via échappement des sorties
- Expiration de session après inactivité

## Structure

- `controllers/` : contrôleurs MVC
- `models/` : accès aux données
- `views/` : templates d'affichage
- `public/` : point d'entrée (`index.php`) + assets
- `database/` : scripts SQL