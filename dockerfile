FROM php:7.4-cli-alpine
WORKDIR /usr/src/sdk

CMD [ "php", "./examples/create.php" ]
