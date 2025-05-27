# Base image: PHP-FPM 8.4
FROM php:8.4-fpm

# Install system dependencies + Nginx
RUN apt-get update && apt-get install -y \
    nginx \
    netcat-openbsd \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Node.js & npm
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm cache clean --force

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Copy Nginx config & entrypoint
RUN rm -rf /etc/nginx/sites-enabled/
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY entrypoint.sh ./entrypoint.sh
RUN chmod +x ./entrypoint.sh && chmod -R 777 storage bootstrap/cache

# Build Laravel & frontend
RUN composer install --no-interaction --optimize-autoloader \
    && npm ci \
    && npm run build \
    && cp public/build/.vite/manifest.json public/build

# Expose web (nginx)
EXPOSE 80

# Start both nginx and php-fpm
ENTRYPOINT ["/bin/sh", "./entrypoint.sh"]