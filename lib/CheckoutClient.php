<?php

namespace PeachPayments;

use PeachPayments\Checkout\CheckoutAPI;

/**
 *  Engine object for payment links
 */
class CheckoutClient
{
  public CheckoutAPI $checkout;

  /**
   * @throws AuthenticationException
   */
  public function __construct(string $entityId, string $secret)
  {
    $this->checkout = new CheckoutAPI($entityId, $secret);
  }

  public function enableTestMode()
  {
    $this->checkout->baseUrl = 'https://testsecure.peachpayments.com/';
  }
}
