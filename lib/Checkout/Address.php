<?php

namespace PeachPayments\Checkout;

/**
 * Holder of address details.
 */
class Address
{
  /**
   * The door number, floor, building number, building name, and/or street name of the address.
   */
  public ?string $street1;
  /**
   * The adjoining road or locality, if required, of the address.
   */
  public ?string $street2;
  /**
   * The town, district, or city of the address.
   */
  public ?string $city;
  /**
   * The company of the address.
   */
  public ?string $company;
  /**
   * The country of the address (ISO 3166-1).
   */
  public ?string $country;
  /**
   * The county, state, or region of the address.
   */
  public ?string $state;
  /**
   * The postal code or ZIP code of the address.
   */
  public ?string $postCode;
  /**
   * The house number of the address.
   */
  public ?string $houseNumber;

  public function __construct(
    string $street1,
    string $city,
    string $state,
    string $country,
    string $postCode,
    ?string $street2,
    ?string $company,
    ?string $houseNumber
  ) {
    $this->street1 = $street1;
    $this->city = $city;
    $this->state = $state;
    $this->country = $country;
    $this->postCode = $postCode;
    $this->street2 = $street2;
    $this->company = $company;
    $this->houseNumber = $houseNumber;
  }
}
