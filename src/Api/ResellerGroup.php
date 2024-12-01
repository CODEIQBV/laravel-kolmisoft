<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class ResellerGroup extends BaseApi
{
    public function create($name, $params = [], $raw = false)
    {
        $groupParams = [
            'name' => $name,
        ];

        // Add optional parameters
        if (isset($params['description'])) {
            $groupParams['rg_description'] = $params['description'];
        }

        // Add feature flags
        $features = [
            'calling_cards',
            'call_shop',
            'sms_addon',
            'payment_gateways',
            'autodialer',
            'pbx_functions',
        ];

        foreach ($features as $feature) {
            if (isset($params[$feature])) {
                $groupParams[$feature] = $params[$feature] ? '1' : '0';
            }
        }

        // Add global username
        $groupParams['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['name'];

        $response = $this->sendRequest('/api/reseller_group_create', $groupParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    public function getGroups($raw = false)
    {
        $params = [];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['u'];

        $response = $this->sendRequest('/api/reseller_groups_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for groups
        $groups = [];

        // Check if groups exist and is not null
        if (isset($response->reseller_groups) && isset($response->reseller_groups->reseller_group)) {
            // Handle case where there's only one group (not an array)
            if (!is_array($response->reseller_groups->reseller_group)) {
                $group = $response->reseller_groups->reseller_group;
                $groups[] = [
                    'id' => (int) $group->id,
                    'name' => (string) $group->name,
                    'description' => (string) $group->description,
                ];
            } else {
                // Handle multiple groups
                foreach ($response->reseller_groups->reseller_group as $group) {
                    $groups[] = [
                        'id' => (int) $group->id,
                        'name' => (string) $group->name,
                        'description' => (string) $group->description,
                    ];
                }
            }
        }

        return $groups;
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Registration over API is disabled':
                throw new ApiException("Registration over API is disabled.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Reseller Group Name must be specified':
                throw new ApiException("Reseller Group Name must be specified.");
            case 'Group name must be unique':
                throw new ApiException("Group name must be unique.");
            case 'You are not authorized to use this functionality':
                throw new ApiException("You are not authorized to use this functionality.");
            case 'No Reseller Groups found':
                throw new ApiException("No Reseller Groups found.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 