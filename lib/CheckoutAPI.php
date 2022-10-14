<?php

namespace PeachPayments\Checkout;

use PeachPayments\HttpClient;

const MAPPING = array(
  'entityId' => 'authentication.entityId',
  'transactionId' => 'merchantTransactionId',
  'invoiceId' => 'merchantInvoiceId',
  'tokeniseCard' => "createRegistration",
  'postCode' => "postcode",
);

class CheckoutAPI
{
  private string $entityId = '';
  private string $secret = '';
  public string $baseUrl = 'https://secure.peachpayments.com/';
  private HttpClient $httpClient;

  public function __construct(string $entityId, string $secret, HttpClient $httpClient = null)
  {
    $this->entityId = $entityId;
    $this->secret = $secret;
    if ($httpClient) {
      $this->httpClient = $httpClient;
    } else {
      $this->httpClient = new HttpClient();
    }
  }

  public function initiateSession(CheckoutOptions $options, string $referer)
  {
    $body = Self::generateSignature($this->entityId, $this->secret, $options);

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

  public function validate(CheckoutOptions $options, string $referer)
  {
    $body = Self::generateSignature($this->entityId, $this->secret, $options);

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

  public function prepareFormPost(CheckoutOptions $options)
  {
    $body = Self::generateSignature($this->entityId, $this->secret, $options);

    $form = ['<form class="checkout-form" method="POST" action="' .  $this->baseUrl . 'checkout">'];
    foreach ($body as $key => $value) {
      $form[] = '<input type="hidden" name="' . $key . '" value="' . $value . '" />';
    }
    $form[] = '<button class="checkout-button" type="submit">Proceed to Checkout</button>';
    $form[] = '</form>';

    return $form;
  }

  public static function generateSignature(string $entityId, string $secret, CheckoutOptions $options)
  {
    if (empty($options->nonce)) {
      $options->nonce = Self::getUuid();
    }

    $body = Self::flatten(Self::map($options, MAPPING), '');
    $body['authentication.entityId'] = $entityId;

    $body['signature'] = Self::sign($body, $secret);

    return $body;
  }

  private static function map($options, array $mapping)
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

  private static function flatten($input, string $prefix = '')
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

  private static function sign(array $body, string $secret)
  {
    ksort($body, SORT_STRING);

    $result = '';

    foreach ($body as $key => $value) {
      if (is_string($value) && empty($value)) {
        continue;
      }

      $result = $result . str_replace('_', '.', $key) . $value;
    }

    return hash_hmac('sha256', $result, $secret);
  }

  /**
   * @return string
   * @throws Exception
   */
  private static function getUuid()
  {
    $data = random_bytes(16);

    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
  }
}
