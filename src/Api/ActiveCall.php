<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class ActiveCall extends BaseApi
{
    public function getActiveCalls($params = [], $raw = false)
    {
        // Add username (included in the hash for this API)
        $params['u'] = config('kolmisoft.username');

        // Define the parameters included in the hash
        $hashKeys = ['u']; // Only 'u' is included in the hash

        // Construct the hash string
        $hashString = '';
        foreach ($hashKeys as $key) {
            $hashString .= $params[$key] ?? '';
        }

        // Append the API Secret Key
        $hashString .= config('kolmisoft.api_secret_key');

        // Generate the hash
        $params['hash'] = sha1($hashString);

        // Log the hash string for debugging
        \Log::debug('Hash String: ' . $hashString);
        \Log::debug('Generated Hash: ' . $params['hash']);

        // Send the request
        $response = $this->sendRequest('/api/active_calls_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        return json_decode(json_encode($response->active_call), true);
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Server was not found':
                throw new ApiException("Server was not found.");
            case 'Status value must be Ringing or Answered':
                throw new ApiException("Status value must be Ringing or Answered.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
}
