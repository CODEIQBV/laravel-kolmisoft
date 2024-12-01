<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Email extends BaseApi
{
    /**
     * Send an email using the MOR API
     *
     * @param array $params Parameters for the email
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function sendEmail($params = [], $raw = false)
    {
        // Validate required parameters
        if (empty($params['email_name'])) {
            throw new ApiException("email_name is required.");
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['email_name', 'email_to_user_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/email_send', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return json_decode(json_encode($response), true);
    }
} 