<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class MonitoringAddon extends BaseApi
{
    const TYPE_INCREASES_MORE_THAN = 1;
    const TYPE_DROPS_BELOW_THAN = 2;

    /**
     * Activate monitoring actions for specified users
     *
     * @param int|string $monitoringId Single monitoring ID
     * @param array|string $userIds Array of user IDs or comma-separated string
     * @param bool $block Whether to block the users
     * @param bool $email Whether to send email notifications
     * @param int $monitoringType Type of monitoring (1: Increases more than, 2: Drops below than)
     * @param bool $raw Whether to return raw response
     * @return array|object Array of activation results or raw response
     * @throws ApiException
     */
    public function activate($monitoringId, $userIds, $block, $email, $monitoringType, $raw = false)
    {
        // Validate monitoring type
        if (!in_array($monitoringType, [self::TYPE_INCREASES_MORE_THAN, self::TYPE_DROPS_BELOW_THAN])) {
            throw new ApiException("Invalid monitoring type. Must be 1 (Increases more than) or 2 (Drops below than)");
        }

        // Convert userIds to string if array
        if (is_array($userIds)) {
            $userIds = implode(',', $userIds);
        }

        // Validate userIds format
        if (!preg_match('/^\d+(?:,\d+)*$/', $userIds)) {
            throw new ApiException("users must be comma-separated numbers");
        }

        $params = [
            'monitoring_id' => $monitoringId,
            'users' => $userIds,
            'block' => $block ? 'true' : 'false',
            'email' => $email ? 'true' : 'false',
            'mtype' => $monitoringType,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: Based on the example, it seems no parameters are included in the hash
        $hashKeys = [];

        $response = $this->sendRequest('/api/monitoring_addon_activate', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return $this->formatResponse($response->status);
    }

    private function formatResponse($status)
    {
        $result = [
            'monitoring_found' => (string) $status->monitoring_found,
            'users' => [],
        ];

        if (isset($status->users) && isset($status->users->user)) {
            // Handle case where there's only one user (not an array)
            if (!is_array($status->users->user)) {
                $result['users'][] = $this->formatUserStatus($status->users->user);
            } else {
                foreach ($status->users->user as $user) {
                    $result['users'][] = $this->formatUserStatus($user);
                }
            }
        }

        return $result;
    }

    private function formatUserStatus($user)
    {
        return [
            'id' => (int) $user->id,
            'status' => trim((string) $user->status),
        ];
    }

    private function handleError($response)
    {
        $error = (string) $response->error;

        switch ($error) {
            case 'Such monitoring was not found. Verify master-slave database integrity.':
                throw new ApiException("Invalid monitoring settings.");
            case 'You must supply these params: monitoring_id, users, block, email, mtype':
                throw new ApiException("Missing required parameters.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 