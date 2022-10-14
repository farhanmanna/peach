<?php

use PeachPayments\Checkout\CheckoutOptions;
use PeachPayments\Checkout\Currency;
use PeachPayments\CheckoutClient;

require(__DIR__ . "/../vendor/autoload.php");
require "config.php";

$client = new CheckoutClient(ENTITY_ID, SECRET);

$options = new CheckoutOptions('INV-000001', Currency::ZAR, 10.0, 'https://httpbin.org/post');

$form = $client->checkout->prepareFormPost($options);

print_r($form);
