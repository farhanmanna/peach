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

  public function __construct(string $entityId, string $secret)
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
}
