FROM php:8.5-fpm

# ==========================================================
# System dependencies and PHP extensions
# ==========================================================

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libpq-dev \
        unzip \
        git \
        curl \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
    && pecl install xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ==========================================================
# Xdebug
# ==========================================================

RUN { \
        echo "zend_extension=xdebug"; \
        echo "xdebug.mode=debug"; \
        echo "xdebug.start_with_request=yes"; \
        echo "xdebug.client_host=host.docker.internal"; \
        echo "xdebug.client_port=9003"; \
        echo "xdebug.idekey=VSCODE"; \
        echo "xdebug.log=/tmp/xdebug.log"; \
        echo "xdebug.log_level=7"; \
    } > /usr/local/etc/php/conf.d/99-xdebug.ini

# ==========================================================
# Composer
# ==========================================================

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json ./

RUN composer install \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts

# ==========================================================
# Working directory
# ==========================================================

WORKDIR /var/www

# ==========================================================
# PHP-FPM
# ==========================================================

CMD ["php-fpm", "-F"]