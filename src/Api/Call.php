<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Call extends BaseApi
{
    public function getUserCalls($params = [], $raw = false)
    {
        // Ensure either s_user or s_reseller is provided
        if (empty($params['s_user']) && empty($params['s_reseller'])) {
            throw new ApiException("Either s_user or s_reseller must be provided.");
        }

        // Add global username (not included in the hash)
        $params['u'] = config('kolmisoft.username');

        // Define the parameters included in the hash in the correct order
        $hashKeys = [
            'period_start',
            'period_end',
            's_user',
            's_call_type',
            's_device',
            's_provider',
            's_hgc',
            's_did',
            's_destination',
            'order_by',
            'order_desc',
            'only_did',
            's_uniqueid',
            's_callback_uniqueid',
            'originator_codec_name',
            'terminator_codec_name'
        ];

        // Set default values for parameters not provided
        $defaults = [
            'period_start' => strtotime('today 00:00'), // Start of today
            'period_end' => strtotime('today 23:59'),   // End of today
            's_user' => '',
            's_reseller' => '', // Add support for reseller
            's_call_type' => 'all',
            's_device' => 'all',
            's_provider' => 'all',
            's_hgc' => 'all',
            's_did' => 'all',
            's_destination' => '',
            'order_by' => 'time',
            'order_desc' => '0',
            'only_did' => '0',
            's_uniqueid' => '',
            's_callback_uniqueid' => '',
            'originator_codec_name' => '',
            'terminator_codec_name' => ''
        ];

        // Merge defaults with provided parameters
        foreach ($hashKeys as $key) {
            $params[$key] = $params[$key] ?? $defaults[$key];
        }

        // Construct the hash string
        $hashString = '';
        foreach ($hashKeys as $key) {
            $hashString .= $params[$key];
        }

        // Append the API Secret Key
        $hashString .= config('kolmisoft.api_secret_key');

        // Generate the hash
        $params['hash'] = sha1($hashString);

        // Log the hash string for debugging
        \Log::debug('Hash String: ' . $hashString);
        \Log::debug('Generated Hash: ' . $params['hash']);

        // Send the request
        $response = $this->sendRequest('/api/user_calls_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        return json_decode(json_encode($response->calls_stat), true);
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
}
