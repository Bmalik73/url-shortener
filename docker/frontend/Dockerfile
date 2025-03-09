FROM node:18-alpine as build

WORKDIR /app

# Copier les fichiers de package
COPY package.json package-lock.json ./

# Installer les dépendances
RUN npm ci

# Copier les fichiers du projet
COPY . .

# Build l'application
RUN npm run build

# Étape de production
FROM nginx:alpine

# Copier la configuration nginx
COPY docker/frontend/nginx.conf /etc/nginx/conf.d/default.conf

# Copier les fichiers de build
COPY --from=build /app/dist /usr/share/nginx/html

# Exposer le port
EXPOSE 80

# Démarrer nginx
CMD ["nginx", "-g", "daemon off;"]