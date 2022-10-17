<?php

namespace PeachPayments\Checkout;

use PeachPayments\http\Response;
use PeachPayments\HttpClient;
use PeachPayments\Signature;

// @codeCoverageIgnoreStart
require(__DIR__ . '/../Utilities.php');
// @codeCoverageIgnoreEnd

/**
 * Mapping between SDK properties and API properties.
 */
const MAPPING = array(
  'entityId' => 'authentication.entityId',
  'transactionId' => 'merchantTransactionId',
  'invoiceId' => 'merchantInvoiceId',
  'tokeniseCard' => "createRegistration",
  'postCode' => "postcode",
);

/**
 * Class for interacting with the Checkout API.
 */
final class CheckoutAPI
{
  /**
   * The entity ID that will be used on Checkout.
   */
  private string $entityId = '';
  /**
   * The secret that will be used to sign requests.
   */
  private string $secret = '';
  /**
   * The Checkout URL.
   */
  public string $baseUrl = 'https://secure.peachpayments.com/';
  /**
   * The handler for calling the API.
   */
  private HttpClient $httpClient;

  public function __construct(
    string $entityId,
    string $secret,
    HttpClient $httpClient = null
  ) {
    $this->entityId = $entityId;
    $this->secret = $secret;
    if ($httpClient) {
      $this->httpClient = $httpClient;
    } else {
      $this->httpClient = new HttpClient();
    }
  }

  /**
   * Initiate a Checkout session, this will return a URL that the user must be redirected to, to complete the Checkout process.
   * 
   * @param \PeachPayments\Checkout\CheckoutOptions $options Payment request details.
   * @param string $referer An allowlisted URL for the merchant.
   * @return \PeachPayments\http\Response A response from the API.
   */
  public function initiateSession(CheckoutOptions $options, string $referer): Response
  {
    $body = Self::signData($this->entityId, $this->secret, $options);

    $response = $this->httpClient->post(
      $this->baseUrl . 'checkout/initiate',
      json_encode($body),
      [
        'Content-Type: application/json',
        'Accepts: application/json',
        'Referer: ' . $referer
      ]
    );
    return $response;
  }

  /**
   * Validate that a Checkout instance could be created from the specified payment request details.
   * 
   * @param \PeachPayments\Checkout\CheckoutOptions $options Payment request details.
   * @param string $referer An allowlisted URL for the merchant.
   * @return \PeachPayments\http\Response A response from the API.
   */
  public function validate(CheckoutOptions $options, string $referer): Response
  {
    $body = Self::signData($this->entityId, $this->secret, $options);

    $response = $this->httpClient->post(
      $this->baseUrl . 'checkout/validate',
      json_encode($body),
      [
        'Content-Type: application/json',
        'Accepts: application/json',
        'Referer: ' . $referer
      ]
    );
    return $response;
  }

  /**
   * Generate an array of form elements for a particular payment request, this can be output to on a page, to let the user action the request which will create the Checkout instance.
   * 
   * @param \PeachPayments\Checkout\CheckoutOptions $options Payment request details.
   * @return array An array of form elements, with a button labelled `Proceed to Checkout`
   */
  public function prepareFormPost(CheckoutOptions $options): array
  {
    $body = Self::signData($this->entityId, $this->secret, $options);

    $form = ['<form class="checkout-form" method="POST" action="' .  $this->baseUrl . 'checkout">'];
    foreach ($body as $key => $value) {
      $form[] = '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
    }
    $form[] = '<button class="checkout-button" type="submit">Proceed to Checkout</button>';
    $form[] = '</form>';

    return $form;
  }

  /**
   * Retrieve a list of enabled payment methods for a channel given a particular currency.
   * 
   * @param string $currency Three-letter ISO 4217 currency code.
   * @return \PeachPayments\http\Response A response from the API.
   */
  public function getPaymentMethods(string $currency): Response
  {
    $body = array(
      'authentication.entityId' => $this->entityId,
      'currency' => $currency,
    );

    $body['signature'] = Signature::generate($body, $this->secret);

    $response = $this->httpClient->post(
      $this->baseUrl . 'merchant_specs',
      json_encode($body),
      [
        'Content-Type: application/json',
      ]
    );
    return $response;
  }

  /**
   * Gets the status of a Checkout instance.
   * 
   * @param string $checkoutId ID of the Checkout, sent in the webhook when created.
   * @param string $merchantTransactionId Merchant specified transaction ID.
   * @return \PeachPayments\http\Response A response from the API.
   */
  public function getStatus(string $checkoutId, string $merchantTransactionId): Response
  {
    $query = array(
      'authentication.entityId' => $this->entityId,
      'checkoutId' => $checkoutId,
      'merchantTransactionId' => $merchantTransactionId,
    );

    $query['signature'] = Signature::generate($query, $this->secret);

    $params = '';

    foreach ($query as $key => $value) {
      $params = $params . $key . '=' . $value . '&';
    }

    $params = rtrim($params, '&');

    $response = $this->httpClient->get(
      $this->baseUrl . 'status?' . $params
    );
    return $response;
  }

  /**
   * Sign the data for a particular Payment request.
   * Creates an array that has been flattened, as required by the Checkout API, adds the signature to the array.
   * 
   * Will generate a nonce if one has not been set.
   * 
   * @param string $entityId The entity ID for the request
   * @param string $secret The secret to use to generate the signature
   * @param \PeachPayments\Checkout\CheckoutOptions $options Payment request details.
   * @return array A flattened array of the payment request, with signature and entity Id attached.
   */
  public static function signData(
    string $entityId,
    string $secret,
    CheckoutOptions $options
  ): array {
    if (empty($options->nonce)) {
      $options->nonce = getUuid();
    }

    $body = flatten(Self::map($options, MAPPING), '');
    $body['authentication.entityId'] = $entityId;

    $body['signature'] = Signature::generate($body, $secret);

    return $body;
  }

  private static function map($options, array $mapping): array
  {
    $mapped = array();

    foreach ($options as $key => $value) {
      if (array_key_exists($key, $mapping)) {
        $key = $mapping[$key];
      }

      if (is_object($value)) {
        $mapped[$key] = Self::map($value, $mapping);
      } else {
        $mapped[$key] = $value;
      }

      if ($key == "amount") {
        $mapped[$key] = number_format($value, 2, '.', '');
      } else if ($key == "createRegistration") {
        $mapped[$key] = $value ? 'true' : 'false';
      } else if ($key == "customParameters") {
        // Checkout expects custom parameters to look like
        // customParameters[name] = value
        // e.g. customParameters[PAYMENT_SMS_COUNT] = "1"
        foreach ($value as $customParameter => $customValue) {
          $mapped[$key . '[' . $customParameter . ']'] = $customValue;
        }

        unset($mapped[$key]);
      }
    }

    return $mapped;
  }
}
