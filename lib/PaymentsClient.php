<?php

namespace PeachPayments;

use PeachPayments\Payments\PaymentsAPI;

class PaymentsClient
{
  public PaymentsAPI $payments;

  public function __construct(string $entityId, string $secret)
  {
    $this->payments = new PaymentsAPI($entityId, $secret);
  }

  public function enableTestMode()
  {
    $this->payments->baseUrl = 'https://testapi.peachpayments.com/';
  }
}
