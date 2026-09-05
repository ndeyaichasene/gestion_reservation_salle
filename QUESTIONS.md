# ----------------GESTION DES RESERVATIONS D'UNE SALLE UNIVERSITAIRE-------------

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


