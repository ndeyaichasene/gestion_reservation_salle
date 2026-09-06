# Changelog


## [0.0.0] - 2026-09-05 13:20
### Added
- Initialisation du dépôt Git
- Preparer .gitignore


## [0.1.0] - 2026-09-05 17:02
### Added
- Configuration de l'autoloading PSR-4
- Installation des dépendances (FastRoute,Eloquent, PHP-DI, Respect/Validation)
- Creer l'arborescence du projet

## [0.2.0] - 2026-09-05 18:49
### Added
- Configuration d'Eloquent ORM avec illuminate/database.
- Configuration de la connexion MySQL avec Capsule\Manager (Eloquent hors Laravel)
- Chargement des variables d'environnement via phpdotenv
- Ajout du fichier .env.example.
- Vérification de la connexion à la base de données.
- Préparation de la structure database/ pour la persistance des données.
- Script SQL de création des tables salle et reservation

## [0.3.0] - 2026-09-06 00:10
### Added
- Modèles Eloquent Salle et Reservation (src/Model/)
- Relation hasMany (Salle → Reservation) et belongsTo (Reservation → Salle)


## [0.4.0] - 2026-09-06 03:34
### Added
- Script database/seed.php pour insérer 5 salles initiales
- Idempotence via firstOrCreate() basé sur le nom de la salle


## [0.5.0] - 2026-09-06 14:17
### Added
- Interface ValidatorInterface et classe ValidationResult (src/Validation/)
- SalleValidator et ReservationValidator basés sur Respect\Validation