# URL Shortener - Test Technique COTON

Cette application permet de raccourcir des URLs longues en URLs courtes et faciles à partager, avec une interface utilisateur moderne et responsive.

## Fonctionnalités

- **Raccourcissement d'URL**: Transforme des URLs longues en codes courts et uniques
- **Redirection intelligente**: Redirige les utilisateurs depuis le code court vers l'URL originale
- **Suivi des statistiques**: Comptabilise les visites de chaque URL raccourcie
- **Gestion de l'expiration**: Permet de définir une durée de validité pour les URLs
- **Interface responsive**: S'adapte parfaitement aux appareils mobiles et desktop

## Architecture du projet

### Structure du projet
url-shortener/
├── backend/                # API Symfony 6.4
│   ├── src/                # Code source PHP
│   ├── tests/              # Tests unitaires et fonctionnels
│   └── ...
├── frontend/               # Interface React TypeScript
│   ├── src/                # Code source TypeScript/React
│   ├── public/             # Fichiers statiques
│   └── ...
├── docker/                 # Configuration Docker
│   ├── backend/            # Configuration pour le backend
│   └── frontend/           # Configuration pour le frontend
├── .github/workflows/      # Pipeline CI/CD GitHub Actions
└── README.md               # Documentation

### Technologies utilisées

#### Backend
- **Symfony 6.4**: Framework PHP moderne et robuste
- **SQLite**: Base de données légère et portative
- **PHPUnit**: Tests unitaires et fonctionnels
- **API REST**: Endpoints bien définis pour les différentes opérations

#### Frontend
- **React**: Bibliothèque UI JavaScript
- **TypeScript**: Pour un code plus fiable et maintenable
- **Tailwind CSS**: Design moderne et responsive
- **React Hook Form**: Gestion efficace des formulaires
- **React Router**: Navigation entre les pages

#### DevOps
- **Docker**: Conteneurisation de l'application
- **GitHub Actions**: Pipeline d'intégration et déploiement continus
- **Render.com**: Déploiement cloud gratuit

## Installation et lancement

### Prérequis
- Docker et Docker Compose
- Git

### Installation avec Docker (recommandé)

```bash
# Cloner le repository
git clone https://github.com/votre-username/url-shortener.git
cd url-shortener

# Lancer les conteneurs
docker-compose up -d

# Accéder à l'application
# Frontend: http://localhost:3000
# Backend API: http://localhost:8000/api

Installation en développement
Backend (Symfony)
cd backend

# Installer les dépendances
composer install

# Créer la base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Lancer le serveur de développement
php -S localhost:8000 -t public

Frontend (React)
cd frontend

# Installer les dépendances
npm install

# Lancer le serveur de développement
npm run dev

API Backend
Le backend expose plusieurs endpoints REST:
Méthode  Endpoint          Description
POST    / api/urls         Créer une URL raccourcie 
GET     /api/urls/{code}   Obtenir les informations d'une URL raccourcie
POST    /api/lookup        Rechercher une URL par son code
GET     /{code}            Rediriger vers l'URL originale

Exemple d'utilisation
# Créer une URL raccourcie
curl -X POST http://localhost:8000/api/urls \
  -H "Content-Type: application/json" \
  -d '{"url":"https://www.example.com"}'

# Réponse
{
  "originalUrl": "https://www.example.com",
  "shortUrl": "http://localhost:8000/abc123",
  "code": "abc123",
  "createdAt": "2023-01-01T12:00:00+00:00",
  "expiresAt": "2024-01-01T12:00:00+00:00",
  "visitCount": 0
}

Tests
Backend
cd backend
php bin/phpunit

Frontend
cd frontend
npm test

Déploiement
L'application est configurée pour être déployée automatiquement sur Render.com via GitHub Actions:

Chaque commit sur la branche main déclenche les tests
Si les tests passent, les images Docker sont construites
Les nouvelles images sont déployées sur Render.com

URL de l'application déployée

Frontend: https://url-shortener-frontend.onrender.com
API Backend: https://url-shortener-backend.onrender.com

Choix techniques et architecture
Stratégie d'encodage des URLs
Pour générer des codes courts uniques, j'ai utilisé une approche combinant:

Une génération de codes aléatoires en base62 (a-z, A-Z, 0-9)
Une vérification d'unicité avec retry en cas de collision
Une longueur minimale configurable (actuellement 6 caractères)

Cette approche offre:

Une excellente distribution (62^6 = 56+ milliards de combinaisons possibles)
Des URLs courtes et lisibles
Une faible probabilité de collision

Architecture en couches
Le backend suit une architecture en couches rigoureuse:

Controllers: Points d'entrée de l'API
DTOs: Objets de transfert de données pour l'API
Services: Logique métier
Repositories: Accès aux données
Entities: Modèles de données

Le frontend utilise une architecture en composants avec:

Pages: Composants de haut niveau pour chaque route
Components: Éléments UI réutilisables
Services: Communication avec l'API
Hooks: Logique réutilisable

Améliorations futures
Voici quelques améliorations que je pourrais apporter au projet avec plus de temps:

Authentification: Ajout d'un système d'authentification pour que les utilisateurs puissent gérer leurs URLs
Dashboard: Interface d'administration pour visualiser les statistiques
QR Codes: Génération de QR codes pour les URLs raccourcies
Analytics avancées: Suivi détaillé des visites (localisation, appareil, etc.)
Personnalisation des codes: Permettre aux utilisateurs de choisir leurs propres codes
Migration vers PostgreSQL: Pour une meilleure scalabilité en production
Cache distribué: Utilisation de Redis pour les URLs fréquemment accédées

Conclusion
Cette application démontre une implémentation complète d'un service de raccourcissement d'URL avec une architecture moderne, des tests robustes et un déploiement automatisé. Les choix techniques ont été faits en privilégiant la maintenabilité, la scalabilité et la qualité du code.