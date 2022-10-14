<?php

namespace PeachPayments\http;

final class Response
{
  public int $code = 200;
  public $body;

  public function __construct(int $code, $body)
  {
    $this->code = $code;
    $this->body = $body;
  }
}
