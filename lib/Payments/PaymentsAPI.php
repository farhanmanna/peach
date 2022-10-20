<?php

namespace PeachPayments\Payments;

use PeachPayments\http\Response;
use PeachPayments\HttpClient;
use PeachPayments\Signature;

/**
 * Class for interacting with the Payments API.
 */
final class PaymentsAPI
{
  /**
   * The entity ID that will be used on Payments.
   */
  private ?string $entityId;
  /**
   * The secret that will be used to sign requests.
   */
  private ?string $secret;
  /**
   * The Payments URL.
   */
  public string $baseUrl = 'https://api.peachpayments.com/';
  /**
   * The handler for calling the API.
   */
  private HttpClient $httpClient;

  public function __construct(
    ?string $entityId,
    ?string $secret,
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
   * Initialise the Payments API class with an entityId and secret.
   * 
   * Required in cases where PaymentsClient needs to be dependency injected.
   */
  public function initialise(string $entityId, string $secret)
  {
    $this->entityId = $entityId;
    $this->secret = $secret;
  }

  /**
   * Refund a Checkout transaction
   * 
   * @param string $transactionId The ID of the transaction used to complete the Checkout.
   * @param string $currency The currency to refund in.
   * @param float $amount The amount to refund, doesn't have to be the full amount, partial refunds are supported..
   * @return \PeachPayments\http\Response A response from the API.
   */
  public function refundCheckout(string $transactionId, string $currency, float $amount): Response
  {
    assert(!empty($this->entityId), 'Error: entityId cannot be empty');
    assert(!empty($this->secret), 'Error: seccret cannot be empty');

    $body = array(
      'amount' => number_format($amount, 2, ',', ''),
      'authentication.entityId' => $this->entityId,
      'currency' => $currency,
      'id' => $transactionId,
      'paymentType' => 'RF',
    );

    $body['signature'] = Signature::generate($body, $this->secret);

    $response = $this->httpClient->post(
      $this->baseUrl . 'v1/checkout/refund',
      json_encode($body),
      [
        'Content-Type: application/json'
      ]
    );
    return $response;
  }

  /**
   * Get transaction status
   * 
   * @param string $transactionId The ID of the transaction used to complete the Checkout.
   * @return \PeachPayments\http\Response A response from the API.
   */
  public function getTransactionStatus(string $merchantTransactionId): Response
  {
    assert(!empty($this->entityId), 'Error: entityId cannot be empty');
    assert(!empty($this->secret), 'Error: seccret cannot be empty');

    $query = array(
      'authentication.entityId' => $this->entityId,
      'merchantTransactionId' => $merchantTransactionId,
    );

    $query['signature'] = Signature::generate($query, $this->secret);

    $params = '';

    foreach ($query as $key => $value) {
      $params = $params . $key . '=' . $value . '&';
    }

    $params = rtrim($params, '&');

    $response = $this->httpClient->get(
      $this->baseUrl . 'v1/checkout/status?' . $params
    );
    return $response;
  }
}
