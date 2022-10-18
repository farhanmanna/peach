# Peach Payments - Checkout SDK

## Requirements

PHP 7.4.0 and later.

## Composer

You can install the sdk via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require peachpayments/checkout-sdk
```

To use the sdk, use Composer's [autoload](https://getcomposer.org/doc/01-basic-usage.md#autoloading):

```php
require_once('vendor/autoload.php');
```

## Dependencies

The sdk require the following extensions in order to work properly:

- [`curl`](https://secure.php.net/manual/en/book.curl.php)
- [`json`](https://secure.php.net/manual/en/book.json.php)
- [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php)

If you use Composer, these dependencies should be handled automatically. If you install manually, you'll want to make
sure that these extensions are available.

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

`XDEBUG_MODE=coverage ./vendor/bin/phpunit --coverage-cobertura coverage.xml tests` - Run tests with code coverage
