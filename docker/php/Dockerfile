FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    cron \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    supervisor \
    build-essential

RUN docker-php-ext-install gd pdo pdo_mysql sockets

COPY --from=composer:2.8.0 /usr/bin/composer /usr/local/bin/composer

# Create system user to run Composer and Artisan Commands
RUN useradd -G www-data,root -d /home/www www
RUN mkdir -p /home/www/.composer && \
    chown -R www:www /home/www

WORKDIR /var/www/html
COPY ./app .

RUN chown -R www:www-data  /var/www/html && \
  find /var/www/html -type d -exec chmod 755 {} \; && \
  find /var/www/html -type f -exec chmod 644 {} \; && \
  chgrp -R www-data storage bootstrap/cache && \
  chmod -R ug+rwx storage bootstrap/cache

COPY ./docker/php/crontab /etc/cron.d/crontab
COPY ./docker/php/entrypoint.sh /entrypoint.sh
COPY ./docker/php/local.ini /usr/local/etc/php/conf.d/local.ini
COPY ./docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

RUN docker-php-ext-install opcache

RUN chmod u+s /usr/sbin/cron && crontab -u www /etc/cron.d/crontab
RUN chmod +x /entrypoint.sh

RUN composer install

USER www
EXPOSE 9000

ENTRYPOINT ["sh", "/entrypoint.sh"]
