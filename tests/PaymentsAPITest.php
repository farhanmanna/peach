<?php

use PHPUnit\Framework\TestCase;
use PeachPayments\http\Response;
use PeachPayments\HttpClient;
use PeachPayments\Payments\PaymentsAPI;

/**
 * @covers PeachPayments\Payments\PaymentsAPI
 * @covers PeachPayments\Signature
 * @covers PeachPayments\http\Response
 */
final class PaymentsAPITest extends TestCase
{
  public function testRefundCheckout(): void
  {
    $httpClient = $this->createMock(HttpClient::class);
    $httpClient->expects($this->once())
      ->method('post')
      ->with(
        $this->equalTo('https://api.peachpayments.com/v1/checkout/refund'),
        '{"amount":"1,00","authentication.entityId":"123","currency":"ZAR","id":"123456","paymentType":"RF","signature":"77604ba14177af929d62cad8fa4fbec224338727cc75db8e4b2a482a63b52d81"}',
        [
          'Content-Type: application/json'
        ]
      )
      ->willReturn(new Response(200, ''));

    $api = new PaymentsAPI('123', '321', $httpClient);

    $form = $api->refundCheckout('123456', 'ZAR', 1.00);

    $this->assertSame(200, $form->code);
    $this->assertSame('', $form->body);
  }

  public function testGetTransactionStatus(): void
  {
    $httpClient = $this->createMock(HttpClient::class);
    $httpClient->expects($this->once())
      ->method('get')
      ->with(
        $this->equalTo('https://api.peachpayments.com/v1/checkout/status?authentication.entityId=123&merchantTransactionId=654321&signature=3b5016dd660a79618e9ebeffd5803b07515976725a462af7fe451d50c903e2ba')
      )
      ->willReturn(new Response(200, ''));

    $api = new PaymentsAPI('123', '321', $httpClient);

    $form = $api->getTransactionStatus('654321');

    $this->assertSame(200, $form->code);
    $this->assertSame('', $form->body);
  }

  public function testRequireInitialiseToBeCalled(): void
  {
    $api = new PaymentsAPI(null, null, null);

    $this->expectError();
    $this->expectErrorMessage('Error: entityId cannot be empty');

    $api->getTransactionStatus('654321');

    $api = new PaymentsAPI('1234', null, null);

    $this->expectError();
    $this->expectErrorMessage('Error: secret cannot be empty');

    $api->getTransactionStatus('654321');

    $httpClient = $this->createMock(HttpClient::class);
    $httpClient->expects($this->once())
      ->method('get')
      ->with(
        $this->equalTo('https://api.peachpayments.com/v1/checkout/status?authentication.entityId=123&merchantTransactionId=654321&signature=3b5016dd660a79618e9ebeffd5803b07515976725a462af7fe451d50c903e2ba')
      )
      ->willReturn(new Response(200, ''));

    $api = new PaymentsAPI('123', '321', $httpClient);

    $form = $api->getTransactionStatus('654321');

    $this->assertSame(200, $form->code);
    $this->assertSame('', $form->body);
  }
}
