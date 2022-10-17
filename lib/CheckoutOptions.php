<?php

namespace PeachPayments\Checkout;

const PAYMENT_TYPE_DEBIT = 'DB';
const PAYMENT_TYPE_REFUND = 'RF';

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

/**
 * Checkout request options.
 * 
 * Used to define the properties for the Checkout instance.
 */
class CheckoutOptions
{
  /**
   * The amount of the payment request.
   */
  public float $amount;
  /**
   * Unique value to represent each request.
   */
  public ?string $nonce;

  /**
   * The customer is redirected to this URL after completion of Checkout. It must be a valid URL that can be reached through a browser.
   */
  public string $shopperResultUrl;

  /**
   * Merchant-provided reference number unique for your transactions.
   */
  public string $transactionId;
  /**
   * Merchant-provided invoice number unique for your transactions. This identifier is not sent onwards.
   */
  public ?string $invoiceId;

  /** Override the preconfigured webhook URL for this Checkout instance, any changes to Checkout will send a webhook to this url.
   */
  public ?string $notificationUrl;
  /**
   * The customer is redirected to this URL if they cancel Checkout. It must be a valid URL that can be reached through a browser.
   */
  public ?string $cancelUrl;

  /**
   * Details about the customer.
   */
  public ?Customer $customer;
  /**
   * Details about the billing address.
   */
  public ?Address $billing;
  /**
   * Details about the shipping address.
   */
  public ?Address $shipping;

  /**
   * The platform the request is originating from. e.g. Magento
   */
  public ?string $plugin;
  /**
   * Used to provide a name for the application that is creating the Checkout instance.
   */
  public ?string $originator;

  /**
   * The preferred payment method which is active in the Checkout page at the point of redirecting.
   */
  public ?string $defaultPaymentMethod;
  /**
   * Force the default payment method to be the only payment method.

   */
  public ?bool $forceDefaultMethod;

  /**
   * The currency code of the payment request amount.
   * 
   * e.g. ZAR, MUR, GBP, USD, KES, EUR
   */
  public string $currency;

  /**
   * The payment type for the request. Accepts DB.
   */
  public string $paymentType = PAYMENT_TYPE_DEBIT;
  /**
   * Used to enable card tokenisation with COPYandPAY.
   */
  public ?string $tokeniseCard;

  /**
   * A name value pair used for sending custom information.
   * 
   * e.g. `array('SMS_COUNT' => '1')
   */
  public ?array $customParameters;

  public function __construct(
    string $transactionId,
    string $currency,
    float $amount,
    string $shopperResultUrl
  ) {
    $this->transactionId = $transactionId;
    $this->currency = $currency;
    $this->amount = $amount;
    $this->shopperResultUrl = $shopperResultUrl;
  }
}
