<?php

use PeachPayments\Checkout\Currency;
use PeachPayments\PaymentsClient;

require(__DIR__ . "/../vendor/autoload.php");
require "config.php";

$client = new PaymentsClient(ENTITY_ID, SECRET);
$client->payments->baseUrl = 'https://qaapi.ppay.io/';

$response = $client->payments->refund('123456', Currency::ZAR, 1.00);

print_r($response);
