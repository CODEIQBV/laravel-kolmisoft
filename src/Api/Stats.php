<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Stats extends BaseApi
{
    public function getQuickStats($raw = false)
    {
        $params = [];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        // Include 'u' in hash only if user is not an admin
        $hashKeys = $params['u'] !== 'admin' ? ['u'] : [];

        $response = $this->sendRequest('/api/quickstats_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'today' => [
                'calls' => (int) $response->quickstats->today->calls,
                'duration' => (int) $response->quickstats->today->duration,
                'revenue' => (float) $response->quickstats->today->revenue,
                'self_cost' => (float) $response->quickstats->today->self_cost,
                'profit' => (float) $response->quickstats->today->profit,
                'margin' => (float) $response->quickstats->today->margin,
            ],
            'active_calls' => [
                'total' => (int) $response->quickstats->active_calls->total,
                'answered_calls' => (int) $response->quickstats->active_calls->answered_calls,
            ],
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'API Requests are disabled':
                throw new ApiException("API Requests are disabled.");
            case 'GET Requests are disabled':
                throw new ApiException("GET Requests are disabled.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Cannot connect to Elasticsearch':
                throw new ApiException("Cannot connect to Elasticsearch.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 