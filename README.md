# Gestion des réservations de salles universitaires

Application PHP orientée objet permettant de consulter les salles universitaires et de gérer leurs réservations.

## Prérequis

* PHP 8.2 ou supérieur
* Composer
* MySQL 8.0 ou supérieur
* Extension PHP PDO MySQL
* Docker et Docker Compose (optionnel)

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/ndeyaichasene/gestion_reservation_salle.git
cd gestion_reservation_salle
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Configurer l'environnement

Copier le fichier `.env.example` :

```bash
cp .env.example .env
```

Puis renseigner les paramètres de connexion à MySQL dans `.env`.

Le fichier `.env` contient les paramètres locaux de l'application et ne doit pas être versionné.

## Configuration de la base de données

L'application utilise **MySQL** comme système de gestion de base de données et **Eloquent ORM** pour la persistance.

La configuration de la connexion est centralisée dans :

```text
config/database.php
```

Les principales variables utilisées sont :

```text
DB_DRIVER
DB_HOST
DB_PORT
DB_DATABASE
DB_USERNAME
DB_PASSWORD
```

## Création des tables

Les migrations se trouvent dans :

```text
database/migrations/
```

Pour exécuter les migrations :

```bash
composer aicha:migrate
```

ou directement :

```bash
php database/migrate.php
```

Les migrations créent les tables :

* `salles`
* `reservations`

## Données initiales

Le script de données initiales se trouve dans :

```text
database/seed.php
```

Pour charger les données initiales :

```bash
composer aicha:seed
```

ou directement :

```bash
php database/seed.php
```

Le seed est idempotent grâce à l'utilisation de `firstOrCreate()`.

### Installation complète de la base

Les migrations et le seed peuvent être exécutés en une seule commande :

```bash
composer db:setup
```

## Structure de la base de données

### Table `salles`

La table `salles` contient :

* `id`
* `nom`
* `batiment`
* `capacite`
* `type`
* `active`
* `created_at`
* `updated_at`

Les types de salles autorisés sont :

* `cours`
* `informatique`
* `laboratoire`
* `amphitheatre`
* `reunion`

### Table `reservations`

La table `reservations` contient :

* `id`
* `salle_id`
* `responsable`
* `email`
* `motif`
* `date_debut`
* `date_fin`
* `statut`
* `created_at`
* `updated_at`

Une salle peut avoir plusieurs réservations.

Une réservation appartient à une seule salle.

## Lancement de l'application

Pour lancer le serveur PHP intégré :

```bash
composer serve
```

Cette commande utilise :

```bash
php -S 127.0.0.1:8001 -t public
```

L'application est alors accessible sur :

```text
http://127.0.0.1:8001
```

`public/index.php` constitue le point d'entrée unique de l'application.

## Installation avec Docker

Docker peut être utilisé pour exécuter l'application avec son environnement PHP et MySQL.

Construire et démarrer les conteneurs :

```bash
docker compose up -d --build
```

Vérifier l'état des conteneurs :

```bash
docker compose ps
```

Pour arrêter les conteneurs :

```bash
docker compose down
```

Les migrations et les données initiales peuvent ensuite être exécutées avec les commandes Composer prévues par le projet.

## Tests

Les tests utilisent **PHPUnit**.

Pour installer les dépendances de développement :

```bash
composer install
```

Pour lancer les tests :

```bash
composer test
```

ou :

```bash
./vendor/bin/phpunit tests
```

Les tests couvrent notamment :

* les règles métier de création d'une réservation ;
* la validation des salles ;
* la validation des réservations ;
* les repositories ;
* les relations Eloquent ;
* la détection des conflits de réservation ;
* l'annulation des réservations.

## Architecture du projet

L'application est organisée en plusieurs couches :

```text
src/
├── Controller/
├── DTO/
├── Exception/
├── Model/
├── Repository/
├── Service/
├── Validation/
└── View/
```

### Controller

Les contrôleurs gèrent les requêtes HTTP et coordonnent les différentes couches de l'application.

Ils ne réalisent pas directement les requêtes Eloquent.

### DTO

Les DTO permettent de transporter les données entre les différentes couches.

Les DTO de création utilisent un **Builder** afin de construire les objets de manière contrôlée.

### Model

Les modèles représentent les entités persistées avec Eloquent.

Les modèles `Salle` et `Reservation` définissent également leurs relations.

### Repository

Les repositories encapsulent l'accès aux données.

Des interfaces permettent de séparer le contrat de persistance de son implémentation Eloquent.

### Service

Les services contiennent les règles métier de l'application.

Par exemple, `CreerReservationService` vérifie notamment :

* l'existence de la salle ;
* l'état actif de la salle ;
* la validité des dates ;
* le début de la réservation dans le futur ;
* la durée maximale de 4 heures ;
* l'absence de chevauchement avec une réservation confirmée.

### Validation

Les données reçues sont validées avant leur utilisation dans le traitement métier.

La validation utilise **Respect/Validation**.

### View

La couche View est responsable de l'affichage des données.

Les templates ne contiennent pas de règles métier.

## Injection de dépendances

Le projet utilise **PHP-DI** pour gérer l'injection des dépendances.

Les dépendances sont fournies aux classes via leurs constructeurs.

Le conteneur est configuré dans :

```text
config/container.php
```

La création du conteneur est centralisée avec :

```text
config/ContainerFactory.php
```

Le point d'entrée de l'application est :

```text
public/index.php
```

Cette organisation permet de respecter notamment le principe d'inversion des dépendances de SOLID.

## Design patterns utilisés

### Repository Pattern

Le Repository Pattern permet d'isoler la logique d'accès aux données du reste de l'application.

### Builder Pattern

Le Builder est utilisé pour construire les DTO de création de manière progressive et contrôlée.

### Factory Pattern

`ContainerFactory` permet de centraliser la création du conteneur PHP-DI.

### Dependency Injection

L'injection de dépendances permet aux classes de recevoir leurs dépendances sans avoir à les créer elles-mêmes.

## Routage

Le routage est réalisé avec **FastRoute**.

Les routes sont déclarées dans :

```text
routes/web.php
```

L'application gère notamment :

* les routes trouvées (`FOUND`) ;
* les routes inexistantes (`NOT_FOUND`) ;
* les méthodes HTTP non autorisées (`METHOD_NOT_ALLOWED`).

Les erreurs HTTP 404 et 405 disposent de pages dédiées.

## Diagramme de classes

Le diagramme de classes du domaine principal se trouve dans :

```text
database/diagramme.puml
```

Il représente principalement les classes :

```text
Salle
Reservation
```

ainsi que leur relation :

```text
Salle 1 -------- 0..* Reservation
```

## Scripts disponibles

Les principales commandes Composer sont :

```bash
composer serve
composer aicha:migrate
composer aicha:seed
composer db:setup
composer test
```

### Démarrer le serveur

```bash
composer serve
```

### Exécuter les migrations

```bash
composer aicha:migrate
```

### Charger les données initiales

```bash
composer aicha:seed
```

### Préparer complètement la base

```bash
composer db:setup
```

### Exécuter les tests

```bash
composer test
```

## Sécurité

Les informations sensibles ne doivent pas être enregistrées dans Git.

Le fichier `.env` est ignoré par Git.

Le fichier `.env.example` est fourni comme modèle de configuration et peut être versionné.

## Changelog

L'historique des évolutions du projet est disponible dans :

```text
CHANGELOG.md
```

## Licence

Projet réalisé dans le cadre de la formation ODC P8.

Licence MIT.
