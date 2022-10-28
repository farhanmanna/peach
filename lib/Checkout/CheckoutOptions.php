<?php

namespace PeachPayments\Checkout;

const PAYMENT_TYPE_DEBIT = 'DB';
const PAYMENT_TYPE_REFUND = 'RF';

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
