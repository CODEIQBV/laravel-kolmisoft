<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class NumberPool extends BaseApi
{
    public function getPools($params = [], $raw = false)
    {
        // Validate parameters if provided
        if (isset($params['number_pool_id']) && (!is_numeric($params['number_pool_id']) || $params['number_pool_id'] <= 0)) {
            throw new ApiException("Invalid number_pool_id");
        }

        if (isset($params['show_numbers'])) {
            if ($params['show_numbers'] !== '1') {
                throw new ApiException("show_numbers must be '1'");
            }

            // Validate number listing parameters when show_numbers is enabled
            if (isset($params['number_limit_row_count'])) {
                if (!is_numeric($params['number_limit_row_count']) || $params['number_limit_row_count'] <= 0) {
                    throw new ApiException("number_limit_row_count must be greater than 0");
                }
            }

            if (isset($params['number_limit_offset'])) {
                if (!is_numeric($params['number_limit_offset']) || $params['number_limit_offset'] < 0) {
                    throw new ApiException("number_limit_offset must be greater than or equal to 0");
                }
            }

            // Validate user_ids format if provided
            if (isset($params['user_ids']) && $params['user_ids'] !== 'all') {
                if (!preg_match('/^\d+(?:,\d+)*$/', $params['user_ids'])) {
                    throw new ApiException("user_ids must be 'all' or comma-separated numbers");
                }
            }
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // No parameters included in hash for this endpoint
        $hashKeys = [];

        $response = $this->sendRequest('/api/number_pools_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        // Initialize empty array for pools
        $pools = [];

        // Check if number pools exist and is not null
        if (isset($response->status->number_pools) && isset($response->status->number_pools->number_pool)) {
            // Handle case where there's only one pool (not an array)
            if (!is_array($response->status->number_pools->number_pool)) {
                $pools[] = $this->formatPool($response->status->number_pools->number_pool);
            } else {
                // Handle multiple pools
                foreach ($response->status->number_pools->number_pool as $pool) {
                    $pools[] = $this->formatPool($pool);
                }
            }
        }

        return $pools;
    }

    private function formatPool($pool)
    {
        $formatted = [
            'id' => (int) $pool->id,
            'name' => (string) $pool->name,
            'comment' => (string) $pool->comment,
            'owner_id' => (int) $pool->owner_id,
        ];

        // Add numbers if they exist
        if (isset($pool->numbers) && isset($pool->numbers->number)) {
            $formatted['numbers'] = [];
            $numbers = !is_array($pool->numbers->number) ? [$pool->numbers->number] : $pool->numbers->number;
            
            foreach ($numbers as $number) {
                $formatted['numbers'][] = $this->formatNumber($number);
            }
        }

        return $formatted;
    }

    private function formatNumber($number)
    {
        $formatted = [
            'number' => (string) $number->number,
            'comment' => (string) $number->comment,
        ];

        // Add user assignments if they exist
        if (isset($number->users) && isset($number->users->user)) {
            $formatted['users'] = [];
            $users = !is_array($number->users->user) ? [$number->users->user] : $number->users->user;
            
            foreach ($users as $user) {
                $formatted['users'][] = [
                    'id' => (int) $user->id,
                    'username' => (string) $user->username,
                    'list_type' => (string) $user->list_type,
                ];
            }
        }

        return $formatted;
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Number Pool was not found':
                throw new ApiException("Number Pool was not found.");
            case 'Numbers cannot be empty':
                throw new ApiException("Numbers cannot be empty.");
            case 'Invalid numbers format':
                throw new ApiException("Invalid numbers format. Allowed characters: 0-9 a-z A-Z # %");
            case 'Number Pool Numbers not found':
                throw new ApiException("Specified numbers were not found in the pool.");
            case 'Number Pool Numbers are in use':
                throw new ApiException("Cannot delete numbers that are in use.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }

    public function create($name, $comment = null, $raw = false)
    {
        // Validate required parameters
        if (empty($name)) {
            throw new ApiException("Name is required");
        }

        $params = [
            'name' => $name,
        ];

        // Add optional comment if provided
        if ($comment !== null) {
            $params['comment'] = $comment;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['name'];

        $response = $this->sendRequest('/api/number_pool_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return [
            'status' => (string) $response->status->success,
            'number_pool_id' => (int) $response->status->number_pool_id,
        ];
    }

    public function update($poolId, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($poolId) || $poolId <= 0) {
            throw new ApiException("Invalid number_pool_id");
        }

        $poolParams = [
            'number_pool_id' => $poolId,
        ];

        // Add optional parameters
        $optionalParams = [
            'name',
            'comment',
        ];

        foreach ($optionalParams as $param) {
            if (isset($params[$param])) {
                $poolParams[$param] = $params[$param];
            }
        }

        // Add global username
        $poolParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['number_pool_id'];

        $response = $this->sendRequest('/api/number_pool_update', $poolParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return (string) $response->status->success;
    }

    public function delete($poolId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($poolId) || $poolId <= 0) {
            throw new ApiException("Invalid number_pool_id");
        }

        $params = [
            'number_pool_id' => $poolId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['number_pool_id'];

        $response = $this->sendRequest('/api/number_pool_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return (string) $response->status->success;
    }

    public function createNumbers($poolId, $numbers, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($poolId) || $poolId <= 0) {
            throw new ApiException("Invalid number_pool_id");
        }

        if (empty($numbers)) {
            throw new ApiException("Numbers cannot be empty");
        }

        // If numbers is an array, join them with commas
        if (is_array($numbers)) {
            $numbers = implode(',', $numbers);
        }

        // Validate numbers format (allowed: 0-9 a-z A-Z # %)
        if (!preg_match('/^[0-9a-zA-Z#%,]+$/', $numbers)) {
            throw new ApiException("Invalid numbers format. Allowed characters: 0-9 a-z A-Z # %");
        }

        $params = [
            'number_pool_id' => $poolId,
            'numbers' => $numbers,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['number_pool_id', 'numbers'];

        $response = $this->sendRequest('/api/number_pool_numbers_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return (string) $response->status->success;
    }

    public function deleteNumbers($poolId, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($poolId) || $poolId <= 0) {
            throw new ApiException("Invalid number_pool_id");
        }

        $deleteParams = [
            'number_pool_id' => $poolId,
        ];

        // Handle delete_all parameter
        if (isset($params['delete_all'])) {
            if ($params['delete_all'] !== '1') {
                throw new ApiException("delete_all must be '1'");
            }
            $deleteParams['delete_all'] = '1';
        } else {
            // Validate that at least one deletion method is specified
            if (empty($params['number_ids']) && empty($params['numbers'])) {
                throw new ApiException("Either number_ids, numbers, or delete_all must be specified");
            }

            // Handle number_ids
            if (isset($params['number_ids'])) {
                if (is_array($params['number_ids'])) {
                    $params['number_ids'] = implode(',', $params['number_ids']);
                }
                if (!preg_match('/^\d+(?:,\d+)*$/', $params['number_ids'])) {
                    throw new ApiException("number_ids must be comma-separated numbers");
                }
                $deleteParams['number_ids'] = $params['number_ids'];
            }

            // Handle numbers
            if (isset($params['numbers'])) {
                if (is_array($params['numbers'])) {
                    $params['numbers'] = implode(',', $params['numbers']);
                }
                if (!preg_match('/^[0-9a-zA-Z#%,]+$/', $params['numbers'])) {
                    throw new ApiException("Invalid numbers format. Allowed characters: 0-9 a-z A-Z # %");
                }
                $deleteParams['numbers'] = $params['numbers'];
            }
        }

        // Add global username
        $deleteParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['number_pool_id'];

        $response = $this->sendRequest('/api/number_pool_numbers_delete', $deleteParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return (string) $response->status->success;
    }
} 