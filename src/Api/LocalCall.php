<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class LocalCall extends BaseApi
{
    /**
     * Get local calls using the MOR API
     *
     * @param int $userId The ID of the user whose local calls to retrieve
     * @param int|null $from Unix timestamp of the starting date
     * @param int|null $till Unix timestamp of the ending date
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getLocalCalls($userId, $from = null, $till = null, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($userId) || $userId <= 0) {
            throw new ApiException("Invalid user_id");
        }

        // Construct the parameters
        $params = [
            'u' => config('kolmisoft.username'), // Authentication username
            's_user' => $userId,                 // User ID in MOR database
        ];

        // Add optional parameters
        if ($from !== null) {
            $params['from'] = $from;
        }

        if ($till !== null) {
            $params['till'] = $till;
        }

        // Generate the hash in the correct order
        $hashString = '';
        $hashKeys = ['from', 'till', 's_user']; // Hash includes from, till, s_user

        foreach ($hashKeys as $key) {
            if (isset($params[$key])) {
                $hashString .= $params[$key];
            }
        }

        // Append API secret key to the hash string
        $hashString .= config('kolmisoft.api_secret_key');

        // Generate the hash
        $params['hash'] = sha1($hashString);

        // Debugging for hash validation
        \Log::debug('Hash String: ' . $hashString);
        \Log::debug('Generated Hash: ' . $params['hash']);

        // Send request to the API
        $response = $this->sendRequest('/api/local_calls_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the local calls
        return json_decode(json_encode($response->local_calls), true);
    }

    /**
     * Handle error responses
     *
     * @param string $error The error message
     * @throws ApiException
     */
    private function handleError($error)
    {
        switch ((string)$error) {
            case 'API Requests are disabled':
                throw new ApiException("API Requests are disabled.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'User was not found':
                throw new ApiException("User was not found.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
}
