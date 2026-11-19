
# ==========================================================
# Stage 1: Composer dependencies
# ==========================================================

FROM composer:2 AS composer-deps

WORKDIR /var/www

# Install required PHP extensions for Composer
RUN apk add --no-cache \
        oniguruma-dev \
    && docker-php-ext-install mbstring

COPY composer.json ./

RUN composer install \
    --no-interaction \
    --no-progress \
    --prefer-dist

# ==========================================================
# Stage 2: PHP-FPM
# ==========================================================

FROM php:8.5-fpm

# ==========================================================
# System dependencies and PHP extensions
# ==========================================================

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        libonig-dev \
        unzip \
        git \
        curl \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
        mbstring \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*


# ==========================================================
# Xdebug
# ==========================================================

RUN { \
        echo "xdebug.mode=debug,coverage"; \
        echo "xdebug.start_with_request=trigger"; \
        echo "xdebug.client_host=host.docker.internal"; \
        echo "xdebug.client_port=9003"; \
        echo "xdebug.idekey=VSCODE"; \
    } > /usr/local/etc/php/conf.d/99-xdebug.ini


# ==========================================================
# Composer
# ==========================================================

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer


# ==========================================================
# Working directory
# ==========================================================

WORKDIR /var/www


# ==========================================================
# Copy dependencies outside the bind-mounted directory
# ==========================================================

COPY --from=composer-deps /var/www/vendor /opt/vendor


# ==========================================================
# Vendor entrypoint
# ==========================================================

RUN printf '%s\n' \
    '#!/bin/sh' \
    'if [ ! -f /var/www/vendor/autoload.php ]; then' \
    '    mkdir -p /var/www/vendor' \
    '    cp -a /opt/vendor/. /var/www/vendor/' \
    'fi' \
    'exec "$@"' \
    > /usr/local/bin/vendor-entrypoint.sh \
    && chmod +x /usr/local/bin/vendor-entrypoint.sh


# ==========================================================
# Start PHP-FPM
# ==========================================================

ENTRYPOINT ["vendor-entrypoint.sh"]

CMD ["php-fpm", "-F"]
