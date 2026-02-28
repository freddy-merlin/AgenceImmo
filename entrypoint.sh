# Image de base PHP (choisis la version que tu utilises)
FROM php:8.2-fpm

# Installation des dépendances système, extensions PHP, Composer, Node.js
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Définition du répertoire de travail
WORKDIR /var/www

# Copie des fichiers de dépendances (pour bénéficier du cache Docker)
COPY composer.json composer.lock ./
COPY package.json package-lock.json ./

# Installation des dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Installation des dépendances Node et build Vite
RUN npm ci && npm run build

# Copie du reste de l'application
COPY . .

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copie du script d'entrée
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000
ENTRYPOINT ["/entrypoint.sh"]