<?php

use PHPUnit\Framework\TestCase;
use PeachPayments\http\Response;
use PeachPayments\HttpClient;
use PeachPayments\Payments\PaymentsAPI;

/**
 * @covers PeachPayments\Payments\PaymentsAPI
 * @covers PeachPayments\http\Response
 */
final class PaymentsAPITest extends TestCase
{
  public function testGetStatus(): void
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
}
