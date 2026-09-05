# ----------------GESTION DES RESERVATIONS D'UNE SALLE UNIVERSITAIRE

# ETAPE 1

## 1. Quel est le rôle de Composer ?

Composer est le gestionnaire de dépendances de PHP.

Il permet de :

* Installer les bibliothèques dont notre projet a besoin ;
* Gérer leurs versions ;
* Installer automatiquement leurs dépendances ;
* Charger automatiquement nos classes grâce à l’autoloading PSR-4.

Dans notre projet, Composer va notamment installer FastRoute, Respect/Validation, Eloquent, PHP-DI et PHPDotenv.

#### *À retenir : Composer permet de gérer les bibliothèques et le chargement automatique des classes PHP.*


## 2. Quelle différence existe entre require et require-dev ?
Require est necessaire en production et avec une installation normale exemple `Eloquent`
Require dev est nécessaire pour développer/tester et peut être exclu avec composer install --no-dev exemple : `PHPUnit`

`require` contient les dépendances nécessaires au fonctionnement de l'application.

Exemple :   "require": {

            "nikic/fast-route": "...",
            "illuminate/database": "..."
        }

`require-dev` contient les dépendances utilisées uniquement pendant le développement, notamment pour les tests.

Exemple :   "require-dev": {

            "phpunit/phpunit": "..."
        }

#### *À retenir : require = fonctionnement de l'application ; require-dev = développement et tests.*

## 3. Pourquoi faut-il versionner composer.lock ?

composer.lock contient les versions exactes des dépendances installées, ainsi que leurs dépendances.

Par exemple, dans composer.json, on peut demander :

                                                    illuminate/database:^12.0

Le ^12.0 autorise plusieurs versions compatibles.

Mais composer.lock enregistre la version précise qui a été sélectionnée.

Cela permet à toute l'équipe d'obtenir les mêmes versions et donc d'éviter :

« Chez moi ça fonctionne, mais chez toi ça ne fonctionne pas. »

#### *À retenir : composer.json définit les contraintes ; composer.lock verrouille les versions exactes.*

## 4. Pourquoi ne versionne-t-on pas vendor/ ?

Parce que vendor/ contient toutes les bibliothèques installées par Composer.

Il peut être très volumineux et surtout, il est recréé automatiquement grâce à : composer init
Quand quelqu'un clone le projet, il fait simplement : composer init
et Composer reconstruit vendor/ à partir de composer.lock.

#### *À retenir : vendor/ est généré automatiquement, donc inutile de le mettre dans Git.*

# ETAPE 2

## 1. Quel rôle joue Capsule\Manager ?

Capsule\Manager C'est le point d'entrée qui permet d'utiliser Eloquent sans le framework Laravel :

Il sert notamment à :

* Configurer la connexion à la base de données ;
* Enregistre comme connexion globale;
* Définir le driver (mysql) ;
* Définir l'hôte, le port, la base, l'utilisateur et le mot de passe ;
* Démarrer Eloquent (bootEloquent);
* Rendre la connexion disponible aux modèles Eloquent.

Dans notre projet, c'est donc lui qui fait le lien :

`PHP → Capsule → PDO/MySQL → Base de données`

## 2. Pourquoi Eloquent peut-il fonctionner sans Laravel ?

Parce qu'Eloquent est une bibliothèque (package ) indépendante decouple du reste du framework.Laravel l'utilise comme ORM, mais Eloquent n'appartient pas exclusivement à Laravel.

On peut donc installer via : 

                            composer require illuminate/database

puis utiliser :

                use Illuminate\Database\Capsule\Manager as Capsule;

sans installer Laravel.

#### *À retenir :Laravel fournit énormément de fonctionnalités autour d'Eloquent, mais Eloquent lui-même peut fonctionner seul.*

# 3. Où doit se trouver le démarrage de l'ORM ?

Le démarrage de l'ORM doit être effectué une seule fois, dans la partie configuration/bootstrap de l'application.

Dans notre architecture, nous avons :

config/
└── database.php

C'est donc config/database.php qui configure et démarre Eloquent.

Il ne faut surtout pas faire cela dans :

un contrôleur ❌
un modèle ❌
un repository ❌
une vue ❌

L'idée est :

`public/index.php → configuration / bootstrap → Eloquent → Controllers → Services → Repositories → Models`

Cela respecte la séparation des responsabilités.

## 4. Quelle différence entre ORM et SQL écrit à la main ?

Le SQL à la main donne un contrôle total mais demande d'écrire chaque requête
et d'assembler soi-même les résultats en objets. L'ORM abstrait ça : on manipule
des objets PHP (Salle::find(1)) et l'ORM génère et exécute le SQL, gère les
relations et le mapping automatiquement — au prix d'un peu moins de contrôle fin sur les requêtes générées.

Exemple SQL :

            SELECT * FROM salles WHERE active = 1;

Puis on utilise PDO pour exécuter la requête.

Exemple ORM :

            Salle::where('active', true)->get();

L'ORM va ensuite générer/exécuter le SQL nécessaire.

**Attention : ORM ne signifie pas qu'on ne fait plus de SQL. L'ORM traduit nos opérations en requêtes SQL.**