# URL Shortener Frontend

Ce projet est le frontend de l'application URL Shortener, développé avec React, TypeScript et Tailwind CSS.

## Fonctionnalités

- Raccourcissement d'URLs
- Affichage des statistiques d'utilisation
- Interface utilisateur intuitive et responsive
- Validation des formulaires

## Technologies utilisées

- React 18
- TypeScript
- Tailwind CSS
- Vite
- Vitest pour les tests
- React Router pour la navigation
- Axios pour les requêtes HTTP

## Prérequis

- Node.js (v16+)
- npm ou yarn

## Installation

Pour démarrer votre application, vous pouvez maintenant exécuter:
Pour démarrer le développement du frontend
cd frontend
npm install
npm run dev
L'application sera disponible sur http://localhost:3000
Ou, avec Docker:
Pour démarrer l'application complète avec Docker
docker-compose up -d
Le backend sera disponible sur http://localhost:8000
Le frontend sera disponible sur http://localhost:3000
frontend/
├── public/ # Fichiers statiques
├── src/ # Code source
│ ├── components/ # Composants React
│ ├── hooks/ # Hooks personnalisés
│ ├── pages/ # Pages de l'application
│ ├── services/ # Services (API, etc.)
│ ├── types/ # Types TypeScript
│ ├── utils/ # Utilitaires
│ ├── App.tsx # Composant principal
│ └── main.tsx # Point d'entrée
├── tests/ # Tests
└── ... # Fichiers de configuration

## Tests

Pour exécuter les tests:
npm run test

## Avantages

Ce frontend est:

- Typé avec TypeScript pour éviter les erreurs et améliorer la maintenabilité
- Stylisé avec Tailwind CSS pour un design moderne et responsive
- Testé avec Vitest et Testing Library
- Bien organisé avec une architecture en composants
- Dockerisé pour un déploiement facile
