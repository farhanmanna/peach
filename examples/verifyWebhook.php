<?php

use PeachPayments\Signature;

require(__DIR__ . "/../vendor/autoload.php");
require "config.php";

$webhookData = array(
  'amount' => '548.58',
  'checkoutId' => '2202acf5afbc43d294d7edde7e509f65',
  'currency' => 'ZAR',
  'merchantTransactionId' => 'd7a310b2-c047-4a0d-a6ca-a6e3866aba92',
  'paymentType' => 'DB',
  'result_code' => '000.200.100',
  'result_description' => 'successfully created checkout',
  'signature' => 'bb116bb53189b8a48957b81717383a14bb3c61917c35277d5f2f2cd5d921f3e2',
  'timestamp' => '2022-10-14T06:05:44Z'
);

$signature = Signature::generate($webhookData, 'THIS_IS_MY_SECRET');

$receivedSignature = $webhookData['signature'];

if ($signature === $receivedSignature) {
  print_r('Valid webhook');
  print_r("\n");
} else {
  print_r('Invalid webhook');
  print_r("\n");
}
