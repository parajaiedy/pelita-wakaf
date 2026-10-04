FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

# Render menyediakan port lewat $PORT; fallback ke 80 jika kosong.
CMD sh -c "cd /var/www/html && php artisan migrate --force || true; PORT=${PORT:-80}; echo \"Starting PHP server on 0.0.0.0:$PORT\"; php -S 0.0.0.0:$PORT -t public"
