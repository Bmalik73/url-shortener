##Documentation Frontend - URL Shortener

##Vue d'ensemble
  Ce frontend est une application React développée avec Vite et TypeScript. Il fournit une interface utilisateur pour le service de raccourcissement d'URL.

##Technologies utilisées
- React: Bibliothèque UI
- TypeScript: Typage statique
- Vite: Outil de build et serveur de développement
- Docker: Conteneurisation
- Nginx: Serveur web pour la production

##Structure du projet
frontend/
├── public/              # Fichiers statiques
├── src/                 # Code source
│   ├── components/      # Composants React
│   ├── hooks/           # Hooks personnalisés
│   ├── pages/           # Pages de l'application
│   ├── services/        # Services API
│   ├── types/           # Types TypeScript
│   ├── utils/           # Utilitaires
│   ├── App.tsx          # Composant principal
│   ├── main.tsx         # Point d'entrée
│   └── vite-env.d.ts    # Déclarations Vite
├── .dockerignore        # Fichiers ignorés par Docker
├── .gitignore           # Fichiers ignorés par Git
├── index.html           # Page HTML principale
├── package.json         # Dépendances et scripts
├── tsconfig.app.json    # Configuration TypeScript pour l'application
├── tsconfig.json        # Configuration TypeScript principale
├── tsconfig.node.json   # Configuration TypeScript pour Node.js
└── vite.config.ts       # Configuration Vite


## Installation et démarrage
# Installer les dépendances
npm install

# Démarrer le serveur de développement
npm run dev

##Avec Docker
# Construire et démarrer les conteneurs
docker-compose up -d

# Accéder à l'application
# http://localhost:3000

##Configuration
L'application utilise des variables d'environnement pour la configuration:
VITE_API_URL: URL de l'API backend (par défaut: http://localhost:8000/api)
Ces variables peuvent être définies dans un fichier .env à la racine du projet ou via Docker Compose.

##Scripts disponibles
npm run dev: Démarre le serveur de développement
npm run build: Construit l'application pour la production
npm run lint: Vérifie le code avec ESLint
npm run preview: Prévisualise la version de production localement

##Déploiement
###Production avec Docker
Le projet inclut un Dockerfile optimisé pour la production:
# Étape de build
FROM node:18-alpine as build
WORKDIR /app
COPY frontend/package.json frontend/package-lock.json ./
RUN npm ci
COPY frontend/ ./
RUN npm run build

# Étape de production
FROM nginx:alpine
COPY --from=build /app/dist /usr/share/nginx/html
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]

##Pour déployer:
docker build -t url-shortener-frontend -f docker/frontend/Dockerfile .
docker run -p 80:80 url-shortener-frontend

###Fonctionnalités principales
- Création de liens courts: Interface pour raccourcir des URLs
- Statistiques: Visualisation des statistiques d'utilisation
- Gestion des liens: Interface pour gérer les liens créés

###Bonnes pratiques
- Utiliser TypeScript pour tous les nouveaux composants
Suivre les principes de conception des composants React
Maintenir une couverture de tests adéquate
Utiliser les hooks React pour la gestion d'état

##Dépannage
###Problèmes courants
- Erreurs de build TypeScript:
- Vérifier les configurations dans tsconfig.app.json et tsconfig.node.json
- S'assurer que toutes les dépendances sont correctement typées
- Problèmes de connexion à l'API:
- Vérifier que la variable d'environnement VITE_API_URL est correctement définie
- S'assurer que le backend est en cours d'exécution

###Problèmes Docker:
- Vérifier les logs avec docker-compose logs frontend
- Reconstruire l'image avec docker-compose build frontend

##Contribution
- Créer une branche pour votre fonctionnalité
-Implémenter et tester votre code
- Soumettre une pull request avec une description détaillée

