FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libicu-dev zlib1g-dev libpng-dev libonig-dev libpq-dev mariadb-client \
    && docker-php-ext-install pdo pdo_mysql intl zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json ./
RUN composer install --no-scripts --no-autoloader

COPY . ./

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN composer dump-autoload --optimize && composer run-script post-install-cmd || true

EXPOSE 8000

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]