<?php

namespace PeachPayments\Checkout;

/**
 * Holder of customer details.
 */
class Customer
{
  /**
   * The customer's first name or given name. Required if you send in any other customer parameters, also required for some risk checks and payment providers. Truncated after 48 characters.
   */
  public ?string $givenName;
  /**
   * The customer's last name or surname. Required if you send in any other customer parameters, also required for some risk checks and payment providers. Truncated after 48 characters.
   */
  public ?string $surname;
  /**
   * The customer's mobile number.
   */
  public ?string $mobile;
  /**
   * The customer's email address.
   */
  public ?string $email;

  public function __construct(
    string $givenName,
    string $surname,
    ?string $mobile = null,
    ?string $email = null
  ) {
    $this->givenName = $givenName;
    $this->surname = $surname;
    $this->mobile = $mobile;
    $this->email = $email;
  }
}
