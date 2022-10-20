<?php

namespace PeachPayments;

use PeachPayments\Checkout\CheckoutAPI;

/**
 *  Engine object for Checkout
 */
class CheckoutClient
{
  /**
   * Checkout, this can be used for creating new Checkout instances or for validating webhooks.
   */
  public CheckoutAPI $checkout;

  public function __construct(?string $entityId, ?string $secret)
  {
    $this->checkout = new CheckoutAPI($entityId, $secret);
  }

  /**
   * Enable Test Mode for this Checkout client.
   * 
   * Checkout instances will not be created in the Live environment.
   */
  public function enableTestMode()
  {
    $this->checkout->baseUrl = 'https://testsecure.peachpayments.com/';
  }

  /**
   * Initialise the Checkout API class with an entityId and secret.
   * 
   * Required in cases where CheckoutClient needs to be dependency injected.
   */
  public function initialise(string $entityId, string $secret)
  {
    $this->checkout->initialise($entityId, $secret);
  }
}
