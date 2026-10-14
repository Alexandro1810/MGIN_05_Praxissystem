FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql
ENV APP_ROOT /app
WORKDIR ${APP_ROOT}