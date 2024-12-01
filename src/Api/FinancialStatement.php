<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class FinancialStatement extends BaseApi
{
    /**
     * Get financial statements for a user
     *
     * @param array $params Parameters for the request
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getFinancialStatements($params = [], $raw = false)
    {
        // Validate required parameters
        if (!isset($params['date_from']) || !isset($params['date_till'])) {
            throw new ApiException("date_from and date_till are required.");
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['user_id', 'date_from', 'date_till'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/financial_statements_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return json_decode(json_encode($response->financial_statement), true);
    }
} 