# Peach Payments - Checkout SDK

## Prod

`composer install --no-dev` - Install dependencies without dev dependencies.

## Development

A Docker file is provided for running php.

`docker compose run example sh` - Launch into the Docker container, to be able to run PHP commands.

Edit the `examples/config.php` file and set the entity id and secret for the Checkout channel.

`composer install` - Install dependencies.

  - `php ./examples/validate.php` - Validate a Checkout instance can be created.
  - `php ./examples/create.php` - Create a Checkout session.
  - `php ./examples/userPost.php` - Build a html form that can be posted on the site to direct the user to Checkout.


## Tests

`docker compose run example sh` - Launch into the Docker container, to be able to run PHP commands.

`./vendor/bin/phpunit tests` - Run tests.
