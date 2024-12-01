<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Lcr extends BaseApi
{
    // Add constants for valid order values
    const ORDER_PRICE = 'price';
    const ORDER_PRIORITY = 'priority';
    const ORDER_PERCENT = 'percent';
    const ORDER_QUALITY = 'quality';

    /**
     * Get list of LCRs
     *
     * @param string|null $name Optional name filter
     * @param bool $raw Whether to return raw response
     * @return array Array of LCRs
     * @throws ApiException
     */
    public function getLcrs($name = null, $raw = false)
    {
        $params = [];

        // Add name filter if provided
        if ($name !== null) {
            $params['s_name'] = $name;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: username must be included in hash
        $hashKeys = ['u'];

        $response = $this->sendRequest('/api/lcrs_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return $this->formatLcrsResponse($response);
    }

    /**
     * Format the LCRs response
     *
     * @param object $response The API response
     * @return array Formatted LCRs
     */
    private function formatLcrsResponse($response)
    {
        $lcrs = [];

        if (!isset($response->lcrs) || !isset($response->lcrs->lcr)) {
            return $lcrs;
        }

        // Handle case where there's only one LCR (not an array)
        if (!is_array($response->lcrs->lcr)) {
            $lcrs[] = $this->formatLcr($response->lcrs->lcr);
        } else {
            foreach ($response->lcrs->lcr as $lcr) {
                $lcrs[] = $this->formatLcr($lcr);
            }
        }

        return $lcrs;
    }

    /**
     * Format a single LCR
     *
     * @param object $lcr The LCR object from response
     * @return array Formatted LCR data
     */
    private function formatLcr($lcr)
    {
        return [
            'id' => (int) $lcr->id,
            'name' => (string) $lcr->name,
            'order' => (string) $lcr->order,
            'first_provider_percent_limit' => (float) $lcr->first_provider_percent_limit,
            'failover_provider_id' => !empty($lcr->failover_provider_id) ? 
                (int) $lcr->failover_provider_id : null,
            'no_failover' => (bool) $lcr->no_failover,
            'minimal_rate_margin_percent' => (float) $lcr->minimal_rate_margin_percent,
            'quality_routing_id' => (int) $lcr->quality_routing_id,
            'allow_loss_calls' => (bool) $lcr->allow_loss_calls,
        ];
    }

    /**
     * Handle error responses
     *
     * @param object $response The error response
     * @throws ApiException
     */
    private function handleError($response)
    {
        $error = (string) $response->error;
        $message = isset($response->message) ? (string) $response->message : null;

        switch ($error) {
            case 'You are not authorized to use this functionality':
                throw new ApiException("You are not authorized to use this functionality.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'No LCRs found':
                throw new ApiException("No LCRs found.");
            case 'LCR was not found':
                throw new ApiException("LCR was not found.");
            case 'Provider was not found':
                throw new ApiException("Provider was not found.");
            case 'Provider not found in LCR':
                throw new ApiException("Provider is not in this LCR.");
            case 'Cannot delete provider':
                throw new ApiException("Cannot delete provider from this LCR.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                $errorMessage = $message ? "$error: $message" : $error;
                throw new ApiException("An unknown error occurred: $errorMessage");
        }
    }

    /**
     * Create a new LCR
     *
     * @param string $name Name of the LCR
     * @param string|null $order Order type (price, priority, percent, quality)
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function create($name, $order = null, $raw = false)
    {
        if (empty($name)) {
            throw new ApiException("Name is required");
        }

        $params = [
            'name' => $name,
        ];

        // Validate and add order if provided
        if ($order !== null) {
            $validOrders = [
                self::ORDER_PRICE,
                self::ORDER_PRIORITY,
                self::ORDER_PERCENT,
                self::ORDER_QUALITY,
            ];

            if (!in_array($order, $validOrders)) {
                throw new ApiException(
                    "Invalid order value. Must be one of: " . implode(', ', $validOrders)
                );
            }

            $params['order'] = $order;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: Only username is included in hash
        $hashKeys = ['u'];

        $response = $this->sendRequest('/api/lcr_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Update an existing LCR
     *
     * @param int $lcrId LCR ID to update
     * @param array $params Update parameters
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function update($lcrId, array $params = [], $raw = false)
    {
        // Validate LCR ID
        if (!is_numeric($lcrId) || $lcrId <= 0) {
            throw new ApiException("Invalid lcr_id");
        }

        $updateParams = [
            'lcr_id' => $lcrId,
        ];

        // Validate and add optional parameters
        $this->validateAndAddUpdateParams($updateParams, $params);

        // Add global username
        $updateParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: Both username and lcr_id are included in hash, in that order
        $hashKeys = ['u', 'lcr_id'];

        $response = $this->sendRequest('/api/lcr_update', $updateParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Validate and add update parameters
     *
     * @param array &$updateParams Reference to parameters array
     * @param array $params Parameters to validate and add
     * @throws ApiException
     */
    private function validateAndAddUpdateParams(&$updateParams, $params)
    {
        // Validate name if provided
        if (isset($params['name'])) {
            if (empty($params['name'])) {
                throw new ApiException("Name cannot be empty");
            }
            $updateParams['name'] = $params['name'];
        }

        // Validate order if provided
        if (isset($params['order'])) {
            $validOrders = [
                self::ORDER_PRICE,
                self::ORDER_PRIORITY,
                self::ORDER_PERCENT,
                self::ORDER_QUALITY,
            ];

            if (!in_array($params['order'], $validOrders)) {
                throw new ApiException(
                    "Invalid order value. Must be one of: " . implode(', ', $validOrders)
                );
            }
            $updateParams['order'] = $params['order'];
        }

        // Validate boolean parameters
        $booleanParams = ['allow_loss_calls', 'no_failover'];
        foreach ($booleanParams as $param) {
            if (isset($params[$param])) {
                if (!in_array($params[$param], [0, 1, '0', '1'], true)) {
                    throw new ApiException("$param must be 0 or 1");
                }
                $updateParams[$param] = (int) $params[$param];
            }
        }

        // Validate numeric parameters
        $numericParams = [
            'minimal_rate_margin_percent',
            'first_provider_percent_limit',
            'quality_routing_id',
        ];

        foreach ($numericParams as $param) {
            if (isset($params[$param])) {
                if (!is_numeric($params[$param])) {
                    throw new ApiException("Invalid $param value");
                }
                $updateParams[$param] = $params[$param];
            }
        }
    }

    /**
     * Delete an LCR
     *
     * @param int $lcrId LCR ID to delete
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function delete($lcrId, $raw = false)
    {
        // Validate LCR ID
        if (!is_numeric($lcrId) || $lcrId <= 0) {
            throw new ApiException("Invalid lcr_id");
        }

        $params = [
            'lcr_id' => $lcrId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: Both username and lcr_id are included in hash, in that order
        $hashKeys = ['u', 'lcr_id'];

        $response = $this->sendRequest('/api/lcr_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Add a provider to an LCR
     *
     * @param int $lcrId LCR ID
     * @param int $providerId Provider ID to add
     * @param bool $failover Whether to add as failover provider
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function addProvider($lcrId, $providerId, $failover = false, $raw = false)
    {
        // Validate LCR ID
        if (!is_numeric($lcrId) || $lcrId <= 0) {
            throw new ApiException("Invalid lcr_id");
        }

        // Validate Provider ID
        if (!is_numeric($providerId) || $providerId <= 0) {
            throw new ApiException("Invalid provider_id");
        }

        $params = [
            'lcr_id' => $lcrId,
            'provider_id' => $providerId,
        ];

        // Add failover parameter if provided
        if ($failover) {
            $params['failover'] = '1';
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: username, lcr_id, and provider_id are included in hash, in that order
        $hashKeys = ['u', 'lcr_id', 'provider_id'];

        $response = $this->sendRequest('/api/lcr_add_provider', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Delete a provider from an LCR
     *
     * @param int $lcrId LCR ID
     * @param int $providerId Provider ID to delete
     * @param bool $failover Whether to delete as failover provider
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function deleteProvider($lcrId, $providerId, $failover = false, $raw = false)
    {
        // Validate LCR ID
        if (!is_numeric($lcrId) || $lcrId <= 0) {
            throw new ApiException("Invalid lcr_id");
        }

        // Validate Provider ID
        if (!is_numeric($providerId) || $providerId <= 0) {
            throw new ApiException("Invalid provider_id");
        }

        $params = [
            'lcr_id' => $lcrId,
            'provider_id' => $providerId,
        ];

        // Add failover parameter if provided
        if ($failover) {
            $params['failover'] = '1';
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: username, lcr_id, and provider_id are included in hash, in that order
        $hashKeys = ['u', 'lcr_id', 'provider_id'];

        $response = $this->sendRequest('/api/lcr_delete_provider', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return [
            'success' => (string) $response->status->success,
        ];
    }
} 