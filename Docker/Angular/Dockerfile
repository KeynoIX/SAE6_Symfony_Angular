FROM node:20-alpine AS build

WORKDIR /app

# Copie des fichiers package.json et package-lock.json
COPY front_coaching/package*.json ./

# Installation des dépendances
RUN npm install

# Installation explicite de bootstrap et bootswatch
RUN npm install bootstrap bootswatch --save

# Copie du reste des fichiers de l'application
COPY front_coaching/ .

# Construction de l'application
RUN npm run build -- --configuration production

# Ajouter une commande pour voir où sont les fichiers générés
RUN ls -la dist

# Étape 2: servir l'application avec Nginx
FROM nginx:alpine

# Copie des fichiers construits dans le répertoire de Nginx
# Nous utilisons un chemin générique qui devrait fonctionner quelle que soit la structure
COPY --from=build /app/dist/* /usr/share/nginx/html/

# Exposition du port 80
EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]