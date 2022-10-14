<?php

namespace PeachPayments\Checkout;

use PeachPayments\http\Response;
use PeachPayments\HttpClient;

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
      $options->nonce = Self::getUuid();
    }

    $body = Self::flatten(Self::map($options, MAPPING), '');
    $body['authentication.entityId'] = $entityId;

    $body['signature'] = Self::generateSignature($body, $secret);

    return $body;
  }

  /**
   * Generate a signature for a particular body.
   * 
   * @param array $body A flat list of data that is sorted in the Checkout manner.
   * @param string $secret The secret to sign the data with
   * @return string A signature based on the data.
   */
  public static function generateSignature(array $body, string $secret): string
  {
    ksort($body, SORT_STRING);

    $result = '';

    foreach ($body as $key => $value) {
      if ($key === 'signature' || (is_string($value) && empty($value))) {
        continue;
      }

      $result = $result . str_replace('_', '.', $key) . $value;
    }

    return hash_hmac('sha256', $result, $secret);
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

  private static function flatten($input, string $prefix = ''): array
  {
    $result = array();

    foreach ($input as $key => $value) {
      if (is_object($value) || is_array($value)) {
        $result = array_merge($result, Self::flatten($value, $key . '.'));
      } else {
        $result[$prefix . $key] = $value;
      }
    }

    return $result;
  }

  /**
   * Generate a UUID.
   * 
   * @return string
   */
  private static function getUuid(): string
  {
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }
}
