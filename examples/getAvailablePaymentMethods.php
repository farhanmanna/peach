<?php

use PeachPayments\Checkout\Currency;
use PeachPayments\CheckoutClient;

require(__DIR__ . "/../vendor/autoload.php");
require "config.php";

$client = new CheckoutClient(ENTITY_ID, SECRET);

$response = $client->checkout->getPaymentMethods(Currency::ZAR);

print_r($response);
