FROM php:7.3-fpm

# PHP
RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS \
    libicu-dev \
    libpq-dev \
    libxml2-dev \
    libzip-dev \
    zlib1g-dev \
    git-core \
    && apt-get -y autoclean

RUN pecl install -o -f \
    xdebug

RUN docker-php-ext-enable \
    xdebug

RUN docker-php-ext-configure \
    intl

RUN docker-php-ext-install \
    intl \
    mbstring \
    opcache \
    soap \
    zip

COPY .docker/php/conf.d/datatimezone.ini /usr/local/etc/php/conf.d/datetimezone.ini
COPY .docker/php/conf.d/memory-limit.ini /usr/local/etc/php/conf.d/memory-limit.ini
COPY .docker/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY .docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# turn off xdebug by default
RUN sed -i 's/^zend_extension=/;zend_extension=/g' /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');" \
    && php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm /tmp/composer-setup.php \
    && composer global require hirak/prestissimo

ARG GITHUB_ACCESS_TOKEN
RUN composer config -g -a github-oauth.github.com ${GITHUB_ACCESS_TOKEN}

RUN chown www-data:www-data /var/www;
RUN usermod -u 1000 www-data
USER www-data

WORKDIR /var/www/tracking
