<?php

namespace PeachPayments;

use PeachPayments\Payments\PaymentsAPI;

/**
 *  Engine object for Payments
 */
class PaymentsClient
{
  /**
   * Payments, this can be used for interacting with the Payments API
   */
  public PaymentsAPI $payments;

  public function __construct(string $entityId, string $secret)
  {
    $this->payments = new PaymentsAPI($entityId, $secret);
  }

  /**
   * Enable Test Mode for this Payment client.
   * 
   * Payments will not be processed in the Live environment.
   */
  public function enableTestMode()
  {
    $this->payments->baseUrl = 'https://testapi.peachpayments.com/';
  }

  /**
   * Initialise the Payments API class with an entityId and secret.
   * 
   * Required in cases where PaymentClient needs to be dependency injected.
   */
  public function initialise(string $entityId, string $secret)
  {
    $this->payments->initialise($entityId, $secret);
  }
}
