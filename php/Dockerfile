
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
        $PHPIZE_DEPS \
    && docker-php-ext-install \
        pdo \
        pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ==========================================================
# Install PEAR / PECL
# ==========================================================

RUN curl -fsSL https://pear.php.net/go-pear.phar \
        -o /tmp/go-pear.phar \
    && php /tmp/go-pear.phar \
    && rm /tmp/go-pear.phar

# ==========================================================
# Xdebug
# ==========================================================

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN { \
        echo "xdebug.mode=debug"; \
        echo "xdebug.start_with_request=yes"; \
        echo "xdebug.client_host=host.docker.internal"; \
        echo "xdebug.client_port=9003"; \
        echo "xdebug.idekey=VSCODE"; \
        echo "xdebug.log=/tmp/xdebug.log"; \
        echo "xdebug.log_level=7"; \
    } > /usr/local/etc/php/conf.d/99-xdebug.ini

# ==========================================================
# PHP upload configuration
# ==========================================================

RUN { \
        echo "file_uploads=On"; \
        echo "upload_max_filesize=10M"; \
        echo "post_max_size=12M"; \
        echo "max_file_uploads=20"; \
    } > /usr/local/etc/php/conf.d/99-uploads.ini

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
