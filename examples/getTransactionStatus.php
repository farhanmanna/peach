<?php

use PeachPayments\PaymentsClient;

require(__DIR__ . "/../vendor/autoload.php");
require "config.php";

$client = new PaymentsClient(ENTITY_ID, SECRET);

$response = $client->payments->getTransactionStatus('654321');

print_r($response);
