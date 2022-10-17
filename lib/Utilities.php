<?php

function flatten($input, string $prefix = ''): array
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
function getUuid(): string
{
  $data = random_bytes(16);

  $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
  $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

  return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}
