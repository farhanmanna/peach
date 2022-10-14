FROM php:7.4-cli-alpine

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

WORKDIR /usr/src/sdk

CMD [ "php", "./examples/create.php" ]
