<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class ExchangeRate extends BaseApi
{
    /**
     * Update exchange rate using the MOR API
     *
     * @param string $currency The name of the currency to update
     * @param float $rate The exchange rate
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function updateExchangeRate($currency, $rate, $raw = false)
    {
        // Validate required parameters
        if (empty($currency)) {
            throw new ApiException("Currency cannot be empty.");
        }

        if (!is_numeric($rate) || $rate <= 0) {
            throw new ApiException("Exchange rate is invalid.");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'currency' => $currency,
            'rate' => $rate,
        ];

        // Define hash keys for this endpoint
        // currency, rate are included in hash
        $hashKeys = ['currency', 'rate'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/exchange_rate_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the exchange rate update status
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
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Exchange rate is invalid':
                throw new ApiException("Exchange rate is invalid.");
            case 'Currency was not found':
                throw new ApiException("Currency was not found.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 