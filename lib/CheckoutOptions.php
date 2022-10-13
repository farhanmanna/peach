<?php

namespace PeachPayments\Checkout;

class Currency
{
  const ZAR = 'ZAR';
  const USD = 'USD';
  const KES = 'KES';
  const MUR = 'MUR';
  const GBP = 'GBP';
  const EUR = 'EUR';
}

const PAYMENT_TYPE_DEBIT = 'DB';
const PAYMENT_TYPE_REFUND = 'RF';

class Customer
{
  public ?string $givenName;
  public ?string $surname;
  public ?string $mobile;
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

class Address
{
  public ?string $street1;
  public ?string $street2;
  public ?string $city;
  public ?string $company;
  public ?string $country;
  public ?string $state;
  public ?string $postCode;
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

class CheckoutOptions
{
  public float $amount;
  public ?string $nonce;

  public string $shopperResultUrl;

  public string $transactionId;
  public ?string $invoiceId;

  public ?string $notificationUrl;
  public ?string $cancelUrl;

  public ?Customer $customer;
  public ?Address $billing;
  public ?Address $shipping;

  public ?string $plugin;
  public ?string $originator;

  public ?string $defaultPaymentMethod;
  public ?bool $forceDefaultMethod;

  public string $currency;

  public string $paymentType = PAYMENT_TYPE_DEBIT;
  public ?string $tokeniseCard;

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
