<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class System extends BaseApi
{
    public function getVersion($raw = false)
    {
        $params = [];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['u'];

        $response = $this->sendRequest('/api/system_version_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->version;
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 