<?php

use PeachPayments\Checkout\Currency;
use PeachPayments\PaymentsClient;

require(__DIR__ . "/../vendor/autoload.php");
require "config.php";

$client = new PaymentsClient(ENTITY_ID, SECRET);

$response = $client->payments->refundCheckout('123456', Currency::ZAR, 1.00);

print_r($response);
