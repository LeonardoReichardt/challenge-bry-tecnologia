FROM php:8.2-apache

# Instalar dependências
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Ativar mod_rewrite
RUN a2enmod rewrite

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copiar projeto
COPY . .

# Instalar dependências Laravel
RUN composer install --no-dev --optimize-autoloader

# Ajustar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Configurar Apache para apontar para public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

EXPOSE 80

CMD ["apache2-foreground"]
