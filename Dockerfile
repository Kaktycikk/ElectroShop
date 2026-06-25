FROM dunglas/frankenphp:1-php8.4

RUN install-php-extensions pgsql pdo_pgsql

WORKDIR /app

COPY . /app