FROM php:8.0-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libpq-dev \
    git \
    unzip \
    libonig-dev \
    pkg-config


ENV ONIG_CFLAGS -I/usr/include

ENV ONIG_LIBS -L/usr/lib -lonig


RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath opcache

COPY . .

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ENV PATH="/root/.composer/vendor/bin:${PATH}"

RUN composer install

CMD php artisan serve --host=0.0.0.0 --port=1200
