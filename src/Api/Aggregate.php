<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Aggregate extends BaseApi
{
    public function get($params = [], $raw = false)
    {
        // Automatically include the global username
        $params['u'] = config('kolmisoft.username');

        if (!isset($params['hash'])) {
            $params['hash'] = $this->generateHash($params);
        }

        $response = $this->sendRequest('/api/aggregate_get', $params, $raw);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'aggregates' => json_decode(json_encode($response->aggregates), true),
            'totals' => json_decode(json_encode($response->totals), true),
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'You are not authorized to use this functionality':
                throw new ApiException("You are not authorized to use this functionality.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Device was not found':
                throw new ApiException("Device was not found.");
            case 'Terminator was not found':
                throw new ApiException("Terminator was not found.");
            case 'Provider was not found':
                throw new ApiException("Provider was not found.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 