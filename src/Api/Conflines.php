<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Conflines extends BaseApi
{
    /**
     * Update configuration lines using the MOR API
     *
     * @param array $params Parameters for the configuration update
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function updateConflines($params = [], $raw = false)
    {
        // Validate required parameters
        if (empty($params)) {
            throw new ApiException("No parameters provided for update.");
        }

        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // No parameters are included in hash, only API_Secret_Key is used
        $hashKeys = [];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/conflines_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the configuration update status
        return [
            'success' => (string) $response->status->success,
        ];
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
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Device was not found':
                throw new ApiException("Device was not found.");
            case 'default_user_password_length must be between 6 and 30':
                throw new ApiException("default_user_password_length must be between 6 and 30.");
            case 'default_user_credit must be positive number or -1 for infinity':
                throw new ApiException("default_user_credit must be positive number or -1 for infinity.");
            case 'default_user_balance must be number':
                throw new ApiException("default_user_balance must be number.");
            case 'default_user_postpaid must be 0 or 1':
                throw new ApiException("default_user_postpaid must be 0 or 1.");
            case 'default_user_allow_loss_calls must be 0 or 1':
                throw new ApiException("default_user_allow_loss_calls must be 0 or 1.");
            case 'default_user_credit must be positive integer':
                throw new ApiException("default_user_credit must be positive integer.");
            case 'default_user_time_zone name was not correct':
                throw new ApiException("default_user_time_zone name was not correct.");
            case 'default_user_currency name was not correct':
                throw new ApiException("default_user_currency name was not correct.");
            case 'default_user_quickforwards_rule_id was not found':
                throw new ApiException("default_user_quickforwards_rule_id was not found.");
            case 'default_user_recording_enabled must be 0 or 1':
                throw new ApiException("default_user_recording_enabled must be 0 or 1.");
            case 'default_user_recording_forced_enabled must be 0 or 1':
                throw new ApiException("default_user_recording_forced_enabled must be 0 or 1.");
            case 'default_device_call_limit must be positive integer':
                throw new ApiException("default_device_call_limit must be positive integer.");
            case 'default_device_canreinvite can only be one of the following: \'yes\', \'no\', \'nonat\', \'update\', \'update,nonat\'':
                throw new ApiException("default_device_canreinvite can only be one of the following: 'yes', 'no', 'nonat', 'update', 'update,nonat'.");
            case 'default_device_nat can only be one of the following: \'yes\', \'no\', \'force_rport\', \'comedia\'':
                throw new ApiException("default_device_nat can only be one of the following: 'yes', 'no', 'force_rport', 'comedia'.");
            case 'default_device_qualify can only be \'no\' or >= 1000 integer':
                throw new ApiException("default_device_qualify can only be 'no' or >= 1000 integer.");
            case 'default_device_grace_time must be positive integer':
                throw new ApiException("default_device_grace_time must be positive integer.");
            case 'default_device_location_id was not found':
                throw new ApiException("default_device_location_id was not found.");
            case 'default_user_tariff_id was not found':
                throw new ApiException("default_user_tariff_id was not found.");
            case 'allow_api must be 0 or 1':
                throw new ApiException("allow_api must be 0 or 1.");
            case 'api_secret_key length must be higher than 5':
                throw new ApiException("api_secret_key length must be higher than 5.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 