<?php

namespace CODEIQBV\Kolmisoft\Api;

use Illuminate\Support\Facades\Http;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class BaseApi
{
    protected function sendRequest($endpoint, $params = [], $raw = false, $hashKeys = [])
    {
        $config = config('kolmisoft');
        $params['u'] = $config['username'];
        $params['p'] = $config['password'];

        if ($config['use_hash']) {
            $params['hash'] = $this->generateHash($params, $hashKeys);
        }

        $response = Http::post($config['api_url'] . $endpoint, $params);

        if ($response->failed()) {
            $statusCode = $response->status();
            $responseBody = $response->body();
            $responseHeaders = $response->headers();


            throw new ApiException("Failed to connect to the API. Status: $statusCode, Response: $responseBody");
        }

        $responseBody = $response->body();

        // Check if the response is valid XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($responseBody);

        if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            throw new ApiException("Failed to parse API response. Raw response: $responseBody");
        }

        if ($raw) {
            return $xml; // Return the SimpleXMLElement directly
        }

        return $xml;
    }

    protected function generateHash($params, $hashKeys)
    {
        // Concatenate all values you want to send into a single string
        $hashString = '';

        foreach ($hashKeys as $key) {
            if (isset($params[$key])) {
                $hashString .= $params[$key];
            }
        }

        // Add API_Secret_Key to the end of hash_string
        $hashString .= config('kolmisoft.auth_key');

        // Calculate SHA1 hash of hash_string
        return sha1($hashString);
    }
}
