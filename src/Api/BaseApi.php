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

            // Log detailed error information
            \Log::error('API Request Failed', [
                'status' => $statusCode,
                'response' => $responseBody,
                'headers' => $responseHeaders,
            ]);

            throw new ApiException("Failed to connect to the API. Status: $statusCode, Response: $responseBody");
        }

        $responseBody = $response->body();

        // Log the raw response for debugging
        \Log::debug('API Response: ' . $responseBody);

        // Check if the response is valid XML
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($responseBody);

        if ($xml === false) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            \Log::error('XML Parsing Errors', ['errors' => $errors]);
            throw new ApiException("Failed to parse API response. Raw response: $responseBody");
        }

        if ($raw) {
            return $xml;
        }

        return $xml;
    }

    /**
     * Generate hash for API request
     *
     * @param array $params Request parameters
     * @param array $hashKeys Keys to include in hash
     * @return string
     */
    protected function generateHash(array $params, ?array $hashKeys = []): string
    {
        $hashString = '';
        
        if ($hashKeys) {
            foreach ($hashKeys as $key) {
                if (isset($params[$key])) {
                    $hashString .= $params[$key];
                }
            }
        }

        $hashString .= config('kolmisoft.auth_key');

        return sha1($hashString);
    }
} 