FROM php:8.4-fpm AS base

COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql intl opcache zip apcu @composer

RUN apt-get update \
 && apt-get install -y --no-install-recommends git unzip \
 && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_HOME=/tmp/composer
WORKDIR /var/www/html

# ---------- dev ----------
FROM base AS dev
ARG UID=1000
ARG GID=1000
RUN groupmod -o -g ${GID} www-data && usermod -o -u ${UID} -g ${GID} www-data
RUN install-php-extensions xdebug
COPY docker/php/php.dev.ini /usr/local/etc/php/conf.d/zz-app.ini
USER www-data

# ---------- prod ----------
FROM base AS prod
ENV APP_ENV=prod APP_DEBUG=0
COPY docker/php/php.prod.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY api/composer.* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
COPY api/ ./
RUN composer dump-autoload --optimize --classmap-authoritative \
 && composer run-script post-install-cmd \
 && chown -R www-data:www-data var
USER www-data