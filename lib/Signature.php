<?php

namespace PeachPayments;

final class Signature
{
  /**
   * Generate a signature for a particular body.
   * 
   * @param array $body A flat list of data that is sorted in the Checkout manner.
   * @param string $secret The secret to sign the data with
   * @return string A signature based on the data.
   */
  public static function generate(array $body, string $secret): string
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
}
