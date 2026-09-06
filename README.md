# Gestion des réservations de salles universitaires

Application PHP orientée objet permettant de consulter les salles de
l'université et gérer leurs réservations.

## Prérequis
- PHP 8.3 ou superieur
- Composer
- MySQL
- PDO MySQL active

## Installation
- composer install
- composer dump-autoload

## Configuration
- Copier .env.example vers .env 
- Renseigner les identifiants de la base.

## Lancement
php -S localhost:8000 -t public

## Configuration de la base
Copier .env.example vers .env et renseigner les identifiants MySQL.

## Création des tables
Exécuter le script SQL fourni dans database/migrations/ sur votre base MySQL.

## Étapes réalisées
- Étape 0 : initialisation Git
- Étape 1 : configuration de Composer et de l'autoloading PSR-4
- Étape 2 : configuration d'Eloquent , connexion MySQL et creation table

## Ajout des données initiales
Exécuter : php database/seed.php
Le script est idempotent (aucun doublon si relancé plusieurs fois).