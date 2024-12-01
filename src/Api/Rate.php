<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Rate extends BaseApi
{
    public function get($username, $prefix, $byFullDst = false, $raw = false)
    {
        $params = [
            'username' => $username,
            'prefix' => $prefix,
        ];

        if ($byFullDst) {
            $params['by_full_dst'] = '1';
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['username'];

        $response = $this->sendRequest('/api/rate_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Parse rate response (format: rate#destination#prefix)
        $rateInfo = explode('#', (string) $response->rate);

        if (count($rateInfo) !== 3) {
            throw new ApiException("Invalid rate response format");
        }

        return [
            'rate' => (float) $rateInfo[0],
            'destination' => $rateInfo[1],
            'prefix' => $rateInfo[2],
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Rate was not found':
                throw new ApiException("Rate was not found for the selected destination.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'Feature disabled':
                throw new ApiException("Rate checking over HTTP is disabled.");
            case 'API Requests are disabled':
                throw new ApiException("API Requests are disabled.");
            case 'You are not authorized to view this page':
                throw new ApiException("You are not authorized to view this page. Required permissions: See Financial Data and Manage Tariffs.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 