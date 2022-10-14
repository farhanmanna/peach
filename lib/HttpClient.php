<?php

namespace PeachPayments;

/**
 *  Currently only CURL client
 */
class HttpClient
{
    /**
     * Basic curl request
     * @throws \JsonException
     */
    public function request(string $url, string $method, $postFields = [], ?array $headers = [])
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_NONE,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $body = curl_exec($curl);

        print_r($body);

        $responseCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $response = json_decode($body, false);

        if ($responseCode !== '200' || !$response) {
            print_r(curl_error($curl));
            curl_close($curl);
            die();
        }

        curl_close($curl);

        return $response;
    }

    /**
     * request with GET method
     * @param string $url
     * @param $postFields
     * @param array|null $headers
     * @return mixed|void
     * @throws \JsonException
     */
    public function get(string $url, ?array $headers = [])
    {
        return Self::request($url, 'GET', [], $headers);
    }

    /**
     * request with POST method
     * @param string $url
     * @param $postFields
     * @param array|null $headers
     * @return mixed|void
     * @throws \JsonException
     */
    public function post(string $url, $postFields = [], ?array $headers = [])
    {
        return Self::request($url, 'POST', $postFields, $headers);
    }
}
