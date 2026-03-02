FROM php:8.4-cli

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip libzip-dev \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

RUN composer install --no-interaction --prefer-dist

EXPOSE 8000

CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]
