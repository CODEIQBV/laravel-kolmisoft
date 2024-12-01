<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Subscription extends BaseApi
{
    public function delete($subscriptionId, $deleteAction, $raw = false)
    {
        $params = [
            'subscription_id' => $subscriptionId,
            'subscription_delete_action' => $deleteAction,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['subscription_id', 'subscription_delete_action'];

        $response = $this->sendRequest('/api/subscription_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    public function getSubscriptions($params = [], $raw = false)
    {
        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['service_id', 'subscription_activation_start', 'subscription_activation_end', 'subscription_memo', 'subscription_until_canceled', 'user_id'];

        $response = $this->sendRequest('/api/subscriptions_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Return the list of subscriptions as an array
        return json_decode(json_encode($response->status->subscriptions), true);
    }

    public function createSubscription($params = [], $raw = false)
    {
        // Ensure required parameters are set
        $requiredParams = ['user_id', 'service_id'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new ApiException("Missing required parameter: $param");
            }
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['user_id', 'service_id'];

        $response = $this->sendRequest('/api/subscription_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status->success,
            'id' => (int) $response->status->id,
        ];
    }

    public function createSubscriptionBulk($params = [], $raw = false)
    {
        // Ensure required parameters are set
        $requiredParams = ['user_id', 'service_id'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new ApiException("Missing required parameter: $param");
            }
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['user_id', 'service_id'];

        $response = $this->sendRequest('/api/subscription_create_bulk', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status->success,
            'ids' => explode(',', (string) $response->status->ids),
        ];
    }

    public function updateSubscription($params = [], $raw = false)
    {
        // Ensure required parameters are set
        $requiredParams = ['subscription_id'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new ApiException("Missing required parameter: $param");
            }
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['subscription_id'];

        $response = $this->sendRequest('/api/subscription_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    public function getFlatRateNumberStatus($userId, $number, $raw = false)
    {
        $params = [
            'user_id' => $userId,
            'number' => $number,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['user_id', 'number'];

        $response = $this->sendRequest('/api/subscription_flat_rate_number_status_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'number' => (string) $response->status->number,
            'status' => (string) $response->status->status,
            'prefix' => isset($response->status->prefix) ? (string) $response->status->prefix : null,
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'You are not authorised to use this functionality':
                throw new ApiException("You are not authorised to use this functionality.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Subscription disabled':
                throw new ApiException("Subscription disabled.");
            case 'Subscription was not found':
                throw new ApiException("Subscription was not found.");
            case 'Subscription delete action was not found':
                throw new ApiException("Subscription delete action was not found.");
            case 'No Subscriptions found':
                throw new ApiException("No Subscriptions found.");
            case 'You are not authorized to manage Subscriptions':
                throw new ApiException("You are not authorized to manage Subscriptions.");
            case 'User has insufficient balance':
                throw new ApiException("User has insufficient balance.");
            case 'Service was not found':
                throw new ApiException("Service was not found.");
            case 'One or more Service was not found':
                throw new ApiException("One or more Service was not found.");
            case 'Subscription activation end date must be valid timestamp':
                throw new ApiException("Subscription activation end date must be valid timestamp.");
            case 'Subscription activation start date must be valid timestamp':
                throw new ApiException("Subscription activation start date must be valid timestamp.");
            case 'Activation start date must be earlier than end date':
                throw new ApiException("Activation start date must be earlier than end date.");
            case 'Service is not flat rate':
                throw new ApiException("Service is not flat rate.");
            case 'user_id must be non negative integer':
                throw new ApiException("user_id must be non negative integer.");
            case 'user_id must be provided':
                throw new ApiException("user_id must be provided.");
            case 'Number must be provided':
                throw new ApiException("Number must be provided.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'User does not have any Flat-Rate subscriptions':
                throw new ApiException("User does not have any Flat-Rate subscriptions.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 