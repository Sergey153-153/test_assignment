FROM php:8.2-apache

# Зависимости для pdo_sqlite
RUN apt-get update && apt-get install -y libsqlite3-dev zip unzip git \
    && docker-php-ext-install pdo pdo_sqlite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Копируем код
COPY public/ /var/www/html/
COPY src/ /var/www/src/

WORKDIR /var/www/html

# Папка для SQLite
RUN mkdir -p /var/www/data && chown -R www-data:www-data /var/www/data