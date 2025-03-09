# URL Shortener - Backend

Ce module backend fournit l'API REST pour le service de raccourcissement d'URL.

## Technologies utilisées

- **Symfony 6.4**: Framework PHP moderne pour le développement d'applications web
- **PHP 8.1+**: Langage de programmation côté serveur
- **SQLite**: Base de données légère et portable pour le stockage des URLs
- **PHPUnit**: Framework de test pour PHP

## Installation

### Prérequis

- PHP 8.1 ou supérieur
- Composer
- Extensions PHP: pdo_sqlite, json, mbstring

### Installation locale


# Cloner le projet (si ce n'est pas déjà fait)
git clone https://github.com/Bmalik73/url-shortener.git
cd url-shortener/backend

# Installer les dépendances
composer install

# Configurer l'environnement
# (optionnel) Copier le .env en .env.local et ajuster les paramètres si nécessaire
cp .env .env.local

# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Démarrer le serveur
php bin/console server:start

L'API sera accessible à l'adresse: http://localhost:8000/api

## Architecture

L'application suit une architecture en couches:

Controllers: Points d'entrée de l'API
Services: Logique métier
Repository: Accès aux données
Entities: Modèles de données
DTOs: Objets de transfert de données

## Documentation API

Consultez le fichier api-routes.md pour une documentation détaillée des endpoints disponibles.

## Tests

Le projet inclut une suite complète de tests unitaires et fonctionnels pour garantir la qualité et la fiabilité du code.

### Exécution des tests

# Exécuter tous les tests
php bin/phpunit

# Exécuter un test spécifique
php bin/phpunit tests/Service/UrlEncoderTest.php

# Exécuter une méthode de test spécifique
php bin/phpunit --filter testEncode tests/Service/UrlEncoderTest.php
```

### Structure des tests

Les tests sont organisés selon la structure suivante :

- **Tests unitaires** : Testent les composants individuels de l'application
  - `tests/Service/UrlEncoderTest.php` : Tests pour le service d'encodage d'URL
  - `tests/Service/UrlServiceTest.php` : Tests pour le service principal de gestion des URLs

- **Tests fonctionnels** : Testent l'application dans son ensemble
  - `tests/Controller/UrlControllerTest.php` : Tests pour les endpoints de l'API

### Couverture des tests

Les tests couvrent les fonctionnalités principales de l'application :

1. **Création d'URLs raccourcies**
   - Validation des entrées
   - Génération de codes uniques
   - Gestion des dates d'expiration

2. **Redirection vers les URLs originales**
   - Redirection correcte
   - Gestion des URLs expirées
   - Gestion des codes inexistants

3. **Récupération des informations sur les URLs**
   - Obtention des métadonnées (date de création, expiration, etc.)
   - Comptage des visites

4. **Encodage et décodage des URLs**
   - Conversion entre IDs et codes courts
   - Génération de codes aléatoires
   - Validation de l'unicité des codes

### Base de données de test

Les tests fonctionnels utilisent une base de données SQLite en mémoire pour garantir l'isolation et la rapidité des tests. La configuration de test est définie dans le fichier `.env.test`.

## Commandes utiles

- Nettoyer les URLs expirées
php bin/console app:CleanupUrlsCommand