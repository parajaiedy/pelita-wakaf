FROM php:8.3-apache

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

RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Force Apache to listen only on IPv4 0.0.0.0:80 for Render health check
RUN printf 'Listen 0.0.0.0:80\n<IfModule ssl_module>\n    Listen 443\n</IfModule>\n<IfModule mod_gnutls.c>\n    Listen 443\n</IfModule>\n' > /etc/apache2/ports.conf

EXPOSE 80

CMD sh -c "cd /var/www/html && php artisan migrate --force || true && exec apache2-foreground"
