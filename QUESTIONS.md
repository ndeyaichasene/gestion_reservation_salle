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

# ETAPE 3

## 1. Quel type de relation Eloquent avez-vous utilisé ?

J'ai utilisé deux relations complémentaires :
* HasMany dans Salle :

                    $this->hasMany(Reservation::class);
Une salle peut avoir plusieurs réservations.

* BelongsTo dans Reservation :

                            $this->belongsTo(Salle::class);

Une réservation appartient à une seule salle.
Donc la relation est : `Salle 1 → N Réservations`.

## 2. Pourquoi déclarer $fillable ou $guarded ?

Ils servent à contrôler les attributs qu'Eloquent peut remplir automatiquement (mass assignment).

- Avec `$fillable`, on indique explicitement les champs autorisés 
Cela évite notamment qu'un utilisateur puisse essayer de modifier des champs qui ne doivent pas être remplis directement, comme id.

- `$guarded` fonctionne à l'inverse : on indique les champs interdits.

**Dans ce projet, j'ai choisi $fillable pour avoir une liste explicite des champs autorisés.**

## 3. Pourquoi convertir active en booléen ?

Dans MySQL, un BOOLEAN est généralement représenté par TINYINT(1) : 1 ou 0
Mais dans PHP, nous voulons travailler avec : true ou false
Donc on met `'active' => 'boolean'` pour permettre à Eloquent de convertir automatiquement la valeur.

## 4. Pourquoi convertir les dates en objets ?

Parce qu'une date ne doit pas être manipulée uniquement comme une simple chaîne de caractères.
Avec `'date_debut' => 'immutable_datetime'` .Eloquent nous permet de travailler avec des objets date/temps.

# ETAPE 4

## 1. Quelle différence existe entre migration et seeder ?

Migration → sert à gérer la structure de la base de données.

Elle permet de : créer une table ,ajouter une colonne ,modifier une structure ,supprimer une table, etc.

Dans notre projet :

                    migration.php
                        ↓
                    création de la table salles
                    création de la table reservations

Seeder → sert à gérer les données initiales de la base.

Dans notre projet :

                    seed.php
                        ↓
                    insertion des 5 salles

#### *À retenir : Migration = structure | Seeder = données*

## 2. Pourquoi les données initiales doivent-elles être reproductibles ?

Parce qu'on doit pouvoir exécuter le seeder plusieurs fois sans problème.
Cela permet notamment de reconstruire ou réinitialiser facilement un environnement de développement.

#### *Reproductible = on peut relancer le seeder sans créer de données incohérentes ou de doublons.*


## 3. Comment empêcher les doublons ?

Dans notre projet, on utilise :

                                Salle::firstOrCreate(
                                    ['nom' => $salle['nom']],
                                    [...]
                                );

`firstOrCreate() :`

* cherche d'abord une salle avec ce nom ;
* si elle existe → il la récupère ;
* si elle n'existe pas → il la crée.

Donc :

        Salle B12 existe ?
            │
        ┌───┴───┐
        OUI     NON
        ↓        ↓
        récupère   crée

C'est ce qui permet à notre seed.php d'être réexécutable sans doublons.


# ETAPE 5

## 1. Pourquoi séparer la validation syntaxique des règles métier ?

La validation syntaxique vérifie que les données ont une forme correcte :

* email est un email valide ;
* capacite est un entier entre 1 et 1000 ;
* motif contient entre 5 et 255 caractères ;
* date_debut est une date valide.

Les règles métier, elles, vérifient si l'opération respecte les règles de l'application.

Par exemple, pour une réservation :

la salle existe ;
la salle est active ;
la date de début est avant la date de fin ; ...

*On sépare les deux pour avoir une responsabilité claire :*

                                            Validator
                                            ↓
                                            "Est-ce que les données ont une forme correcte ?"

                                            Service
                                            ↓
                                            "Est-ce que cette opération est autorisée par les règles métier ?"

C'est notamment cohérent avec le `Single Responsibility Principle (SRP)` de SOLID: chaque classe a une responsabilité précise.

## 2.Pourquoi créer une interface de validation ?
Pour que les contrôleurs et services dépendent d'une abstraction (ValidatorInterface) plutôt que 
d'une implémentation concrète — ça permet de remplacer facilement le validateur (tests, changement 
de librairie) sans toucher au code qui l'utilise .
L'avantage est que le reste de l'application peut travailler avec l'interface, sans dépendre directement d'une implémentation particulière.

C'est également utile pour le polymorphisme et correspond au principe `Dependency Inversion` de SOLID.

## 3. Pourquoi le validateur ne doit-il pas enregistrer les données ?

Parce que valider et enregistrer sont deux responsabilités différentes.

Le validateur doit uniquement répondre : « Les données sont-elles valides ? »
Il ne doit pas faire : $model->save() ou une requete sql

* Le Validator vérifie les données.
* Le Service applique les règles métier.
* Le Repository s'occupe de la persistance.

Cela évite d'avoir une classe qui fait trop de choses et respecte encore le SRP.

## 4.Comment retourner plusieurs erreurs en une seule fois ?
- En validant chaque champ indépendamment dans une boucle, 
- En capturant chaque exception sans interrompre les autres validations, 
- En accumulant les erreurs dans un tableau associatif [champ => message] retourné par `ValidationResult::failure()`.

En resume :

Le Validator vérifie la forme des données, 
le Service vérifie les règles métier, 
le Repository enregistre les données. 
L'interface permet d'avoir un contrat commun entre les validateurs, et 
ValidationResult permet de retourner toutes les erreurs en une seule fois.                                                                    