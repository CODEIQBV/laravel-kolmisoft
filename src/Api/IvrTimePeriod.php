<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class IvrTimePeriod extends BaseApi
{
    // Constants for valid values
    const WEEKDAYS = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];

    /**
     * Update an IVR time period
     *
     * @param int $timePeriodId Time period ID to update
     * @param array $params Update parameters
     * @param bool $raw Whether to return raw response
     * @return array{message: string}|object
     * @throws ApiException
     */
    public function update($timePeriodId, array $params = [], $raw = false)
    {
        // Validate time period ID
        if (!is_numeric($timePeriodId) || $timePeriodId <= 0) {
            throw new ApiException("Invalid ivr_time_period_id");
        }

        $updateParams = [
            'ivr_time_period_id' => $timePeriodId,
        ];

        // Validate and add update parameters
        $this->validateAndAddUpdateParams($updateParams, $params);

        // Add global username
        $updateParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: Both username and ivr_time_period_id are included in hash
        $hashKeys = ['u', 'ivr_time_period_id'];

        $response = $this->sendRequest('/api/ivr_time_period_update', $updateParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        return [
            'message' => (string) $response->status->message,
        ];
    }

    /**
     * Validate and add update parameters
     *
     * @param array &$params Reference to parameters array
     * @param array $updates Parameters to validate and add
     * @throws ApiException
     */
    private function validateAndAddUpdateParams(&$params, array $updates)
    {
        // Validate name if provided
        if (isset($updates['name'])) {
            if (empty($updates['name'])) {
                throw new ApiException("name value cannot be blank");
            }
            $params['name'] = $updates['name'];
        }

        // Validate hour parameters
        if (isset($updates['start_hour'])) {
            if (!is_numeric($updates['start_hour']) || $updates['start_hour'] < 0 || $updates['start_hour'] > 23) {
                throw new ApiException("start_hour must be between 0 and 23");
            }
            $params['start_hour'] = $updates['start_hour'];
        }

        if (isset($updates['end_hour'])) {
            if (!is_numeric($updates['end_hour']) || $updates['end_hour'] < 0 || $updates['end_hour'] > 23) {
                throw new ApiException("end_hour must be between 0 and 23");
            }
            $params['end_hour'] = $updates['end_hour'];
        }

        // Validate minute parameters
        if (isset($updates['start_minute'])) {
            if (!is_numeric($updates['start_minute']) || $updates['start_minute'] < 0 || $updates['start_minute'] > 59) {
                throw new ApiException("start_minute must be between 0 and 59");
            }
            $params['start_minute'] = $updates['start_minute'];
        }

        if (isset($updates['end_minute'])) {
            if (!is_numeric($updates['end_minute']) || $updates['end_minute'] < 0 || $updates['end_minute'] > 59) {
                throw new ApiException("end_minute must be between 0 and 59");
            }
            $params['end_minute'] = $updates['end_minute'];
        }

        // Validate weekday parameters
        if (isset($updates['start_weekday'])) {
            if ($updates['start_weekday'] !== '' && !in_array($updates['start_weekday'], self::WEEKDAYS)) {
                throw new ApiException("Valid start_weekday values are " . implode(', ', self::WEEKDAYS));
            }
            $params['start_weekday'] = $updates['start_weekday'];
        }

        if (isset($updates['end_weekday'])) {
            if ($updates['end_weekday'] !== '' && !in_array($updates['end_weekday'], self::WEEKDAYS)) {
                throw new ApiException("Valid end_weekday values are " . implode(', ', self::WEEKDAYS));
            }
            $params['end_weekday'] = $updates['end_weekday'];
        }

        // Validate month parameters
        if (isset($updates['start_month'])) {
            if ($updates['start_month'] !== '' && (!is_numeric($updates['start_month']) || 
                $updates['start_month'] < 1 || $updates['start_month'] > 12)) {
                throw new ApiException("start_month must be between 1 and 12");
            }
            $params['start_month'] = $updates['start_month'];
        }

        if (isset($updates['end_month'])) {
            if ($updates['end_month'] !== '' && (!is_numeric($updates['end_month']) || 
                $updates['end_month'] < 1 || $updates['end_month'] > 12)) {
                throw new ApiException("end_month must be between 1 and 12");
            }
            $params['end_month'] = $updates['end_month'];
        }

        // Validate day parameters
        if (isset($updates['start_day'])) {
            if ($updates['start_day'] !== '' && (!is_numeric($updates['start_day']) || 
                $updates['start_day'] < 1 || $updates['start_day'] > 31)) {
                throw new ApiException("start_day must be between 1 and 31");
            }
            $params['start_day'] = $updates['start_day'];
        }

        if (isset($updates['end_day'])) {
            if ($updates['end_day'] !== '' && (!is_numeric($updates['end_day']) || 
                $updates['end_day'] < 1 || $updates['end_day'] > 31)) {
                throw new ApiException("end_day must be between 1 and 31");
            }
            $params['end_day'] = $updates['end_day'];
        }
    }

    /**
     * Handle error responses
     *
     * @param string $error The error message
     * @throws ApiException
     */
    private function handleError($error)
    {
        switch ($error) {
            case 'Time period was not found':
                throw new ApiException("Time period was not found or access denied.");
            case 'Time period was not updated':
                throw new ApiException("Time period was not updated.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 