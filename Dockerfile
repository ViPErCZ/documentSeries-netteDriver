FROM php:7.1-cli-alpine
RUN apk add --no-cache $PHPIZE_DEPS \
	&& pecl install xdebug-2.5.0 \
	&& docker-php-ext-install pdo pdo_mysql \
	&& docker-php-ext-enable xdebug