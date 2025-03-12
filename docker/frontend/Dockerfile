# Étape de build avec Node.js
FROM node:20-alpine as build

WORKDIR /app

# Installer npm et dépendances
RUN apk add --no-cache nodejs npm

# Installer TypeScript et Vite globalement
RUN npm install -g typescript@latest vite

# Copier les fichiers de package
COPY frontend/package.json frontend/package-lock.json ./

# Installer les dépendances
RUN npm ci

# Copier les fichiers du projet
COPY frontend/ ./

# Build l'application
RUN npm run build

# Étape de production AVEC Node.js
FROM node:20-alpine 

WORKDIR /app

# Copier les fichiers de build
COPY --from=build /app /app

# Installer les dépendances en mode production
RUN npm install --production=false

# Exposer le port
EXPOSE 3000

# Lancer le serveur de dev
CMD ["npm", "run", "dev", "--", "--host", "0.0.0.0"]
