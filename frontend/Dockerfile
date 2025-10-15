# Etapa 1: Build do Angular
FROM node:22 AS build

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build --prod

# Etapa 2: Servir com Nginx
FROM nginx:alpine

COPY --from=build /app/dist/frontend /usr/share/nginx/html

EXPOSE 4200
CMD ["nginx", "-g", "daemon off;"]
