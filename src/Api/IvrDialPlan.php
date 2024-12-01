<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class IvrDialPlan extends BaseApi
{
    /**
     * Update an IVR dial plan
     *
     * @param int $dialPlanId Dial plan ID to update
     * @param array $timePeriods Optional time period IDs [time_period1, time_period2, time_period3]
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function update($dialPlanId, array $timePeriods = [], $raw = false)
    {
        // Validate dial plan ID
        if (!is_numeric($dialPlanId) || $dialPlanId <= 0) {
            throw new ApiException("Invalid ivr_dial_plan_id");
        }

        $params = [
            'ivr_dial_plan_id' => $dialPlanId,
        ];

        // Validate and add time periods
        $this->validateAndAddTimePeriods($params, $timePeriods);

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: Both username and ivr_dial_plan_id are included in hash
        $hashKeys = ['u', 'ivr_dial_plan_id'];

        $response = $this->sendRequest('/api/ivr_dial_plan_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Validate and add time period parameters
     *
     * @param array &$params Reference to parameters array
     * @param array $timePeriods Time period IDs to validate and add
     * @throws ApiException
     */
    private function validateAndAddTimePeriods(&$params, array $timePeriods)
    {
        // Map of parameter names
        $periodParams = [
            'time_period1',
            'time_period2',
            'time_period3',
        ];

        foreach ($periodParams as $index => $param) {
            if (isset($timePeriods[$param])) {
                $value = $timePeriods[$param];
                
                // Allow empty values
                if ($value !== '') {
                    if (!is_numeric($value) || $value <= 0) {
                        throw new ApiException("Invalid $param value");
                    }
                }
                
                $params[$param] = $value;
            }
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
            case 'Dial plan was not found':
                throw new ApiException("Dial plan was not found or access denied.");
            case 'time_period1 was not found':
                throw new ApiException("Time period 1 was not found or access denied.");
            case 'time_period2 was not found':
                throw new ApiException("Time period 2 was not found or access denied.");
            case 'time_period3 was not found':
                throw new ApiException("Time period 3 was not found or access denied.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 