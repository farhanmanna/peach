<?php

use PeachPayments\Checkout\CheckoutOptions;
use PeachPayments\Checkout\Currency;
use PeachPayments\CheckoutClient;

require(__DIR__ . "/../vendor/autoload.php");
require "config.php";

$client = new CheckoutClient(ENTITY_ID, SECRET);

$options = new CheckoutOptions('INV-000001', Currency::ZAR, 10.0, 'https://httpbin.org/post');

$response = $client->checkout->initiateSession($options, "https://localhost");

print_r($response);

$redirectUrl = $response["redirectUrl"];

// Redirect user to url.
# header('Location: ' . $redirectUrl);
