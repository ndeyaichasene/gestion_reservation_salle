# Changelog

## [0.0.0] - 2026-09-05

### Added

* Initialisation du dépôt Git.
* Préparation du fichier `.gitignore`.

## [0.1.0] - 2026-09-05

### Added

* Configuration de l'autoloading PSR-4 avec Composer.
* Installation des dépendances :

  * FastRoute
  * Eloquent
  * PHP-DI
  * Respect/Validation
  * PHP dotenv
* Création de l'arborescence du projet.

## [0.2.0] - 2026-09-05

### Added

* Configuration d'Eloquent ORM avec `illuminate/database`.
* Configuration de la connexion MySQL avec `Capsule\Manager`.
* Chargement des variables d'environnement avec `phpdotenv`.
* Ajout du fichier `.env.example`.
* Vérification de la connexion à la base de données.
* Préparation de la structure `database/` pour la persistance.
* Création des migrations des tables `salles` et `reservations`.

## [0.3.0] - 2026-09-06

### Added

* Ajout des modèles Eloquent `Salle` et `Reservation`.
* Mise en place de la relation `hasMany` entre `Salle` et `Reservation`.
* Mise en place de la relation `belongsTo` entre `Reservation` et `Salle`.

## [0.4.0] - 2026-09-06

### Added

* Ajout du script `database/seed.php`.
* Insertion de 5 salles initiales.
* Mise en place d'un seed idempotent avec `firstOrCreate()`.

## [0.5.0] - 2026-09-06

### Added

* Ajout de `ValidatorInterface`.
* Ajout de `ValidationResult`.
* Ajout de `SalleValidator`.
* Ajout de `ReservationValidator`.
* Utilisation de Respect/Validation pour les règles de validation.

## [0.6.0] - 2026-09-06

### Added

* Ajout de `CreerSalleDTO`.
* Ajout de `CreerReservationDTO`.
* Conversion des données de réservation en objets `DateTimeImmutable`.
* Ajout des Builders associés aux DTO.

## [0.7.0] - 2026-09-07

### Added

* Ajout de `SalleRepositoryInterface`.
* Ajout de `ReservationRepositoryInterface`.
* Ajout des implémentations des repositories.
* Encapsulation des accès Eloquent dans les repositories.
* Implémentation de la détection des conflits de réservation.

## [0.8.0] - 2026-09-08

### Added

* Ajout de `CreerReservationService`.
* Implémentation des règles métier liées aux réservations.
* Ajout de `AnnulerReservationService`.
* Ajout de `SalleIndisponibleException`.
* Ajout de `ReservationIntrouvableException`.
* Ajout de `ReservationInvalideException`.

## [0.9.0] - 2026-09-06

### Added

* Ajout de `SalleController`.
* Ajout de `ReservationController`.
* Ajout du composant `Renderer`.
* Ajout des templates des salles.
* Ajout des templates des réservations.
* Ajout des pages d'erreur 404 et 405.
* Mise en place du layout commun.

## [0.10.0] - 2026-09-06

### Added

* Déclaration des routes dans `routes/web.php`.
* Intégration de FastRoute.
* Gestion des routes `FOUND`, `NOT_FOUND` et `METHOD_NOT_ALLOWED`.
* Ajout de l'en-tête HTTP `Allow` pour les réponses 405.

## [0.11.0] - 2026-09-06

### Added

* Ajout des définitions PHP-DI dans `config/container.php`.
* Mise en place de l'injection automatique des dépendances.
* Ajout de `ContainerFactory`.

### Changed

* Simplification de `public/index.php`.
* Déplacement de la logique applicative dans `Application`.
* Injection des dépendances nécessaires dans les classes concernées.

## [0.12.0] - 2026-09-06

### Added

* Configuration de PHPUnit avec `phpunit.xml`.
* Ajout des doublures de repositories en mémoire pour les tests unitaires.
* Ajout des tests unitaires de `CreerReservationService`.
* Tests des règles de validation de `SalleValidator`.
* Tests des règles de validation de `ReservationValidator`.
* Ajout des tests d'intégration Eloquent.
* Tests des relations entre `Salle` et `Reservation`.
* Tests de détection des chevauchements.
* Tests d'annulation des réservations.

## [1.0.0] - 2026-09-09

### Added

* Finalisation de l'application.
* Ajout du diagramme de classes.
* Ajout et amélioration du README.
* Finalisation de la gestion des erreurs HTTP.
* Finalisation de la configuration Docker.
* Ajout des scripts CLI.
* Finalisation de l'intégration des composants de l'application.

### Fixed

* Correction de la vérification d'existence des tables dans les migrations.
* Correction de différents problèmes de configuration et de finalisation du projet.
