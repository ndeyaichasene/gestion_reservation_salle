# Gestion des réservations de salles universitaires

Application PHP orientée objet permettant de consulter les salles de
l'université et gérer leurs réservations.

## Prérequis
- PHP 8.3 ou supérieur
- Composer
- MySQL (ou Docker & Docker Compose)
- PDO MySQL activé

## Lancement avec Docker (Recommandé)

### 1. Démarrer les conteneurs (App PHP 8.3 + MySQL 8.0)
```bash
docker compose up -d --build
```
L'application est accessible sur [http://localhost:8081](http://localhost:8081).
La base MySQL est accessible sur l'hôte au port `3305` (utilisateur: `aicha`, mot de passe: `passer`, bdd: `reservation_salles`).

### 2. Exécuter les migrations et charger les données initiales (seeds)
```bash
docker compose exec app composer db:setup
```
*(Ou séparément : `docker compose exec app composer migrate` puis `docker compose exec app composer seed`)*

### 3. Arrêter les conteneurs
```bash
docker compose down
```

---

## Installation Locale (sans Docker)
- `composer install`
- `composer dump-autoload`
- Copier `.env.example` vers `.env` et renseigner les identifiants MySQL.
- `php database/migrate.php`
- `php database/seed.php`
- `php -S localhost:8000 -t public`

---

## Intégration Continue (CI/CD) & Docker Hub

Le workflow GitHub Actions `.github/workflows/docker-publish.yml` compile et publie automatiquement les images Docker sur Docker Hub.

### Configuration des Secrets GitHub
Dans votre dépôt GitHub, rendez-vous dans **Settings > Secrets and variables > Actions** et ajoutez :
- `DOCKERHUB_USERNAME` : votre identifiant Docker Hub.
- `DOCKERHUB_TOKEN` : votre token d'accès Docker Hub (avec droits Read & Write).

### Publication automatique
- Dès qu'un tag Git est poussé (`git push origin <nom-du-tag>`), l'image Docker correspondante est construite et publiée sur Docker Hub.
- **Pour compiler et publier tous les tags existants** (`v0.0.0` à `v0.8.0`) :
  1. Allez dans l'onglet **Actions** de votre dépôt GitHub.
  2. Sélectionnez **Build and Push Docker Images to Docker Hub**.
  3. Cliquez sur **Run workflow** (laissez l'option par défaut `all`).

## Lancement du serveur
php -S localhost:8000 -t public