FROM php:7.4-cli-alpine

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \ 
  && pecl install xdebug \
  && docker-php-ext-enable xdebug \
  && apk del pcre-dev ${PHPIZE_DEPS}

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

WORKDIR /usr/src/sdk

CMD [ "php", "./examples/create.php" ]
