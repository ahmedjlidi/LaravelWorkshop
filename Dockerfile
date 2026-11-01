FROM php:8.4-cli-bookworm

# Use official PHP and Composer images; Docker selects Intel/AMD or ARM automatically.
RUN apt-get update \
    && apt-get install -y --no-install-recommends libonig-dev libsqlite3-dev libxml2-dev libzip-dev unzip \
    && docker-php-ext-install -j"$(nproc)" mbstring pdo_sqlite dom xml xmlwriter zip pcntl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Cache the locked dependencies in the image. Runtime code is mounted from the editor.
WORKDIR /opt/workshop
COPY composer.json composer.lock ./
RUN composer install --prefer-dist --no-interaction --no-progress --no-scripts --no-autoloader

COPY docker/entrypoint.sh /usr/local/bin/workshop-entrypoint
COPY docker/artisan.sh /usr/local/bin/workshop-artisan
RUN chmod +x /usr/local/bin/workshop-entrypoint /usr/local/bin/workshop-artisan

WORKDIR /app
EXPOSE 8000
ENTRYPOINT ["workshop-entrypoint"]
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000", "--no-reload"]
