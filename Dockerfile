FROM dunglas/frankenphp:1-php8.4

RUN install-php-extensions pgsql pdo_pgsql

WORKDIR /app

COPY . .

ENV SERVER_ROOT=/app/public
ENV SERVER_NAME=:${PORT}

EXPOSE 8080