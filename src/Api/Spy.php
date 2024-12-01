<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Spy extends BaseApi
{
    public function initiateCall($activeCallId, $raw = false)
    {
        $params = [
            'active_call_id' => $activeCallId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['u', 'active_call_id'];

        $response = $this->sendRequest('/api/spy_call', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status->success,
            'spy_device' => (string) $response->status->spy_device,
            'channel' => (string) $response->status->channel,
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Chanspy is disabled':
                throw new ApiException("Chanspy is disabled.");
            case 'Active call was not found':
                throw new ApiException("Active call was not found.");
            case 'No Spy Device assigned. Select it under User edit preferences.':
                throw new ApiException("No Spy Device assigned. Select it under User edit preferences.");
            case 'Cannot connect to Asterisk Server':
                throw new ApiException("Cannot connect to Asterisk Server.");
            case 'Spy Device is registered on different Server than this Call. Spying on this Call is not possible.':
                throw new ApiException("Spy Device is registered on different Server than this Call. Spying on this Call is not possible.");
            case 'Unable to spy virtual device':
                throw new ApiException("Unable to spy virtual device.");
            case 'You are not authorized to use this functionality':
                throw new ApiException("You are not authorized to use this functionality.");
            case 'Access denied':
                throw new ApiException("Access denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 