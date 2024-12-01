<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Service extends BaseApi
{
    const TYPE_PERIODIC_FEE = 'periodic_fee';
    const TYPE_ONE_TIME_FEE = 'one_time_fee';
    const TYPE_DYNAMIC_FLAT_RATE = 'dynamic_flat_rate';
    const TYPE_FLAT_RATE = 'flat_rate';

    const PERIOD_MONTH = 'month';
    const PERIOD_DAY = 'day';

    public function create($name, $type, $params = [], $raw = false)
    {
        $serviceParams = [
            'new_service_name' => $name,
            'new_service_type' => $type,
        ];

        // Add optional parameters
        if (isset($params['sell_price'])) {
            $serviceParams['service_sell_price'] = $params['sell_price'];
        }

        if (isset($params['self_cost'])) {
            $serviceParams['service_self_cost'] = $params['self_cost'];
        }

        if (isset($params['period'])) {
            $serviceParams['service_period'] = $params['period'];
        }

        if (isset($params['minutes_per_month'])) {
            $serviceParams['service_minutes_per_month'] = $params['minutes_per_month'];
        }

        if (isset($params['owner_id'])) {
            $serviceParams['new_owner_id'] = $params['owner_id'];
        }

        // Add global username
        $serviceParams['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['new_service_name', 'new_service_type'];

        $response = $this->sendRequest('/api/service_create', $serviceParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status->success,
            'id' => (int) $response->status->service->id,
        ];
    }

    public function delete($serviceId, $raw = false)
    {
        $params = [
            'service_id' => $serviceId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['service_id'];

        $response = $this->sendRequest('/api/service_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    public function getServices($raw = false)
    {
        $params = [];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // No parameters to include in hash for this endpoint
        $hashKeys = [];

        $response = $this->sendRequest('/api/services_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for services
        $services = [];

        // Check if services exist and is not null
        if (isset($response->services) && isset($response->services->service)) {
            // Handle case where there's only one service (not an array)
            if (!is_array($response->services->service)) {
                $service = $response->services->service;
                $services[] = [
                    'id' => (int) $service->id,
                    'name' => (string) $service->name,
                    'memo' => (string) $service->memo,
                    'type' => (string) $service->type,
                    'period' => (string) $service->period,
                    'price' => (float) $service->price,
                    'self_cost' => (float) $service->self_cost,
                    'currency' => (string) $service->currency,
                    'quantity' => isset($service->quantity) ? (int) $service->quantity : null,
                ];
            } else {
                // Handle multiple services
                foreach ($response->services->service as $service) {
                    $services[] = [
                        'id' => (int) $service->id,
                        'name' => (string) $service->name,
                        'memo' => (string) $service->memo,
                        'type' => (string) $service->type,
                        'period' => (string) $service->period,
                        'price' => (float) $service->price,
                        'self_cost' => (float) $service->self_cost,
                        'currency' => (string) $service->currency,
                        'quantity' => isset($service->quantity) ? (int) $service->quantity : null,
                    ];
                }
            }
        }

        return $services;
    }

    public function update($serviceId, $params = [], $raw = false)
    {
        $serviceParams = [
            'service_id' => $serviceId,
        ];

        // Add optional parameters
        if (isset($params['name'])) {
            $serviceParams['service_name'] = $params['name'];
        }

        if (isset($params['type'])) {
            $serviceParams['service_type'] = $params['type'];
        }

        if (isset($params['sell_price'])) {
            $serviceParams['service_sell_price'] = $params['sell_price'];
        }

        if (isset($params['self_cost'])) {
            $serviceParams['service_self_cost'] = $params['self_cost'];
        }

        if (isset($params['period'])) {
            $serviceParams['service_period'] = $params['period'];
        }

        if (isset($params['minutes_per_month'])) {
            $serviceParams['service_minutes_per_month'] = $params['minutes_per_month'];
        }

        // Add global username
        $serviceParams['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['service_id'];

        $response = $this->sendRequest('/api/service_update', $serviceParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Access Denied':
                throw new ApiException("Access Denied - only admin, reseller or accountant can use this API method.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Service must have service type':
                throw new ApiException("Service type must be one of periodic_fee, one_time_fee, dynamic_flat_rate or flat_rate.");
            case 'Flat Rate Service must have quantity':
                throw new ApiException("Flat-Rate Service must have Minutes/month provided.");
            case 'Quantity must be numeric':
                throw new ApiException("Minutes/month must be a number.");
            case 'Quantity must be greater than zero':
                throw new ApiException("Quantity must be greater than zero.");
            case 'Service Price must be numeric':
                throw new ApiException("Service Price must be numeric.");
            case 'You are not authorized to use this functionality':
                throw new ApiException("User has no permissions to manage Services.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'Service was not found':
                throw new ApiException("Service was not found.");
            case 'You are not authorized to manage Services':
                throw new ApiException("You are not authorized to manage Services.");
            case 'Cannot delete - some subscriptions to this service exist':
                throw new ApiException("Cannot delete - some subscriptions to this service exist.");
            case 'No Services found':
                throw new ApiException("No Services found.");
            case 'Service Type is invalid':
                throw new ApiException("Service type is invalid. It should be periodic_fee, one_time_fee, or flat-rate.");
            case 'Service was not selected':
                throw new ApiException("Service ID must be provided.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 