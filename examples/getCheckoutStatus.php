<?php

use PeachPayments\CheckoutClient;

require(__DIR__ . "/../vendor/autoload.php");
require "config.php";

$client = new CheckoutClient(ENTITY_ID, SECRET);

$response = $client->checkout->getStatus('123456', '654321');

print_r($response);
