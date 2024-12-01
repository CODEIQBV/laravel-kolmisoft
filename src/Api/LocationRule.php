<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class LocationRule extends BaseApi
{
    // Rule type constants
    const TYPE_SOURCE = 'src';
    const TYPE_DESTINATION = 'dst';
    const TYPE_COMBINED = 'combined';

    /**
     * Create a new location rule
     *
     * @param int $locationId Location ID where rule will be created
     * @param string $name Rule name
     * @param array $params Optional parameters
     * @param bool $raw Whether to return raw response
     * @return array{success: string, rule_id: int}|object
     * @throws ApiException
     */
    public function create($locationId, $name, array $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($locationId) || $locationId <= 0) {
            throw new ApiException("Invalid location_id");
        }

        if (empty($name)) {
            throw new ApiException("Rule must have name");
        }

        $ruleParams = [
            'location_id' => $locationId,
            'name' => $name,
        ];

        // Validate and add optional parameters
        $this->validateAndAddOptionalParams($ruleParams, $params);

        // Add global username
        $ruleParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['location_id'];

        $response = $this->sendRequest('/api/location_rule_create', $ruleParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        return [
            'success' => (string) $response->status->success,
            'rule_id' => (int) $response->status->rule_id,
        ];
    }

    /**
     * Validate and add optional parameters to the request
     *
     * @param array &$ruleParams Reference to rule parameters array
     * @param array $params Optional parameters to process
     * @throws ApiException
     */
    private function validateAndAddOptionalParams(&$ruleParams, $params)
    {
        // Validate rule type if provided
        if (isset($params['lr_type'])) {
            if (!in_array($params['lr_type'], [self::TYPE_SOURCE, self::TYPE_DESTINATION, self::TYPE_COMBINED])) {
                throw new ApiException("Invalid rule type. Must be src, dst, or combined");
            }
            $ruleParams['lr_type'] = $params['lr_type'];
        }

        // Validate numeric parameters
        $numericParams = [
            'minlen' => 1,
            'maxlen' => 99,
            'src_minlen' => 1,
            'src_maxlen' => 99,
            'tariff_id' => null,
            'lcr_id' => null,
            'did_id' => null,
            'device_id' => null,
            'location_group_id' => null,
            'dst_locationgroup_id' => null,
        ];

        foreach ($numericParams as $param => $default) {
            if (isset($params[$param])) {
                if (!is_numeric($params[$param]) || $params[$param] < 0) {
                    throw new ApiException("Invalid $param value");
                }
                $ruleParams[$param] = $params[$param];
            } elseif ($default !== null) {
                $ruleParams[$param] = $default;
            }
        }

        // Validate boolean parameters
        if (isset($params['change_callerid_name'])) {
            $ruleParams['change_callerid_name'] = $params['change_callerid_name'] ? '1' : '0';
        }

        // Add pattern parameters
        $patternParams = ['cut', 'add', 'src_cut', 'src_add'];
        foreach ($patternParams as $param) {
            if (isset($params[$param])) {
                $ruleParams[$param] = $params[$param];
            }
        }

        // Validate that at least one pattern is set
        if (empty($params['cut']) && empty($params['add']) && 
            empty($params['src_cut']) && empty($params['src_add'])) {
            throw new ApiException("Cut and Add cannot be empty");
        }
    }

    /**
     * Update an existing location rule
     *
     * @param int $ruleId Location rule ID to update
     * @param array $params Update parameters
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function update($ruleId, array $params = [], $raw = false)
    {
        // Validate required parameter
        if (!is_numeric($ruleId) || $ruleId <= 0) {
            throw new ApiException("Invalid location_rule_id");
        }

        $ruleParams = [
            'location_rule_id' => $ruleId,
        ];

        // Validate and add update parameters
        $this->validateAndAddUpdateParams($ruleParams, $params);

        // Add global username
        $ruleParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['location_rule_id'];

        $response = $this->sendRequest('/api/location_rule_update', $ruleParams, $raw, $hashKeys);

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
     * Validate and add update parameters to the request
     *
     * @param array &$ruleParams Reference to rule parameters array
     * @param array $params Parameters to process
     * @throws ApiException
     */
    private function validateAndAddUpdateParams(&$ruleParams, $params)
    {
        // Validate enabled parameter
        if (isset($params['enabled'])) {
            if (!in_array($params['enabled'], ['0', '1', 0, 1], true)) {
                throw new ApiException("enabled must be 0 or 1");
            }
            $ruleParams['enabled'] = (string) $params['enabled'];
        }

        // Validate name if provided
        if (isset($params['name'])) {
            if (empty($params['name'])) {
                throw new ApiException("Rule must have name");
            }
            $ruleParams['name'] = $params['name'];
        }

        // Validate numeric parameters (including -1 for unassign)
        $numericParams = [
            'minlen' => 1,
            'maxlen' => 99,
            'src_minlen' => 1,
            'src_maxlen' => 99,
            'tariff_id' => null,
            'lcr_id' => null,
            'did_id' => null,
            'device_id' => null,
            'location_group_id' => null,
            'dst_locationgroup_id' => null,
        ];

        foreach ($numericParams as $param => $default) {
            if (isset($params[$param])) {
                if (!is_numeric($params[$param]) || ($params[$param] < -1)) {
                    throw new ApiException("Invalid $param value");
                }
                $ruleParams[$param] = $params[$param];
            } elseif ($default !== null) {
                $ruleParams[$param] = $default;
            }
        }

        // Validate boolean parameters
        if (isset($params['change_callerid_name'])) {
            $ruleParams['change_callerid_name'] = $params['change_callerid_name'] ? '1' : '0';
        }

        // Add pattern parameters
        $patternParams = ['cut', 'add', 'src_cut', 'src_add'];
        foreach ($patternParams as $param) {
            if (isset($params[$param])) {
                $ruleParams[$param] = $params[$param];
            }
        }

        // Only validate patterns if any are being updated
        $hasPatternUpdate = false;
        foreach ($patternParams as $param) {
            if (isset($params[$param])) {
                $hasPatternUpdate = true;
                break;
            }
        }

        if ($hasPatternUpdate && empty($params['cut']) && empty($params['add']) && 
            empty($params['src_cut']) && empty($params['src_add'])) {
            throw new ApiException("Cut and Add cannot be empty");
        }
    }

    private function handleError($error)
    {
        $error = (string) $error;
        switch ($error) {
            case 'Rule must be unique':
                throw new ApiException("Rule must be unique.");
            case 'Rule must have name':
                throw new ApiException("Rule must have name.");
            case 'Cannot assign device':
                throw new ApiException("Cannot assign device - device ID is not suitable.");
            case 'Cut and Add cannot be empty':
                throw new ApiException("Cut and Add cannot be empty.");
            case 'Device not found':
                throw new ApiException("Device not found.");
            case 'Location error':
                throw new ApiException("Location does not belong to Admin/Reseller.");
            case 'Location not found':
                throw new ApiException("Location with location_id does not exist.");
            case 'LCR was not found':
                throw new ApiException("LCR with lcr_id does not exist.");
            case 'Cannot assign lcr':
                throw new ApiException("LCR ID was used in Reseller account.");
            case 'Cannot assign did':
                throw new ApiException("DID ID was used in Reseller account.");
            case 'DID was not found':
                throw new ApiException("DID with did_id does not exist.");
            case 'Tariff was not found':
                throw new ApiException("Tariff does not exist or is not accessible.");
            case 'Location Group was not found':
                throw new ApiException("Location group with specified ID does not exist.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }

    /**
     * Get location rules
     *
     * @param int|string $locationId Location ID or 'all' for all locations
     * @param array $params Optional parameters (from, max_results)
     * @param bool $raw Whether to return raw response
     * @return array{location: array{id: int, name: string, location_rule: array[]}}|object
     * @throws ApiException
     */
    public function getRules($locationId, array $params = [], $raw = false)
    {
        // Validate location_id
        if ($locationId !== 'all' && (!is_numeric($locationId) || $locationId <= 0)) {
            throw new ApiException("Invalid location_id");
        }

        $requestParams = [
            'location_id' => $locationId,
        ];

        // Add pagination parameters if provided
        if (isset($params['from'])) {
            if (!is_numeric($params['from']) || $params['from'] < 0) {
                throw new ApiException("Invalid from value");
            }
            $requestParams['from'] = $params['from'];
        }

        if (isset($params['max_results'])) {
            if (!is_numeric($params['max_results']) || $params['max_results'] <= 0) {
                throw new ApiException("Invalid max_results value");
            }
            $requestParams['max_results'] = $params['max_results'];
        }

        // Add global username
        $requestParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['location_id'];

        $response = $this->sendRequest('/api/location_rules_get', $requestParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return $this->formatRulesResponse($response->status);
    }

    /**
     * Format the rules response into a consistent structure
     *
     * @param object $status The status node from the response
     * @return array The formatted response
     */
    private function formatRulesResponse($status)
    {
        if (!isset($status->location)) {
            return ['location' => []];
        }

        $location = $status->location;
        $formatted = [
            'location' => [
                'id' => (int) $location->id,
                'name' => (string) $location->name,
                'location_rule' => [],
            ],
        ];

        // Handle location rules
        if (isset($location->location_rule)) {
            // Handle case where there's only one rule (not an array)
            if (!is_array($location->location_rule)) {
                $formatted['location']['location_rule'][] = $this->formatRuleDetails($location->location_rule);
            } else {
                foreach ($location->location_rule as $rule) {
                    $formatted['location']['location_rule'][] = $this->formatRuleDetails($rule);
                }
            }
        }

        return $formatted;
    }

    /**
     * Format individual rule details
     *
     * @param object $rule The rule object from the response
     * @return array The formatted rule
     */
    private function formatRuleDetails($rule)
    {
        return [
            'id' => (int) $rule->id,
            'location_id' => (int) $rule->location_id,
            'name' => (string) $rule->name,
            'enabled' => (bool) $rule->enabled,
            'cut' => (string) $rule->cut,
            'add' => (string) $rule->add,
            'minlen' => (int) $rule->minlen,
            'maxlen' => (int) $rule->maxlen,
            'lr_type' => (string) $rule->lr_type,
            'lcr_id' => !empty($rule->lcr_id) ? (int) $rule->lcr_id : null,
            'tariff_id' => !empty($rule->tariff_id) ? (int) $rule->tariff_id : null,
            'did_id' => !empty($rule->did_id) ? (int) $rule->did_id : null,
            'device_id' => !empty($rule->device_id) ? (int) $rule->device_id : null,
            'change_callerid_name' => (bool) $rule->change_callerid_name,
            'src_cut' => (string) $rule->src_cut,
            'src_add' => (string) $rule->src_add,
            'src_minlen' => (int) $rule->src_minlen,
            'src_maxlen' => (int) $rule->src_maxlen,
            'locationgroup_id' => (int) $rule->locationgroup_id,
        ];
    }

    /**
     * Get a single location rule by ID
     *
     * @param int $ruleId Location rule ID
     * @param bool $raw Whether to return raw response
     * @return array The formatted rule details
     * @throws ApiException
     */
    public function getRule($ruleId, $raw = false)
    {
        // Validate rule ID
        if (!is_numeric($ruleId) || $ruleId <= 0) {
            throw new ApiException("Invalid location_rule_id");
        }

        $params = [
            'location_rule_id' => $ruleId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['location_rule_id'];

        $response = $this->sendRequest('/api/location_rule_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        if (!isset($response->status->location_rule)) {
            throw new ApiException("Invalid response format");
        }

        return $this->formatRuleDetails($response->status->location_rule);
    }

    /**
     * Copy a location rule to another location
     *
     * @param int $ruleId Location rule ID to copy
     * @param int $locationId Destination location ID
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function copyRule($ruleId, $locationId, $raw = false)
    {
        // Validate rule ID
        if (!is_numeric($ruleId) || $ruleId <= 0) {
            throw new ApiException("Invalid location_rule_id");
        }

        // Validate location ID
        if (!is_numeric($locationId) || $locationId <= 0) {
            throw new ApiException("Invalid location_id");
        }

        $params = [
            'location_rule_id' => $ruleId,
            'location_id' => $locationId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: Parameters must be in correct order: location_rule_id, location_id
        $hashKeys = ['location_rule_id', 'location_id'];

        $response = $this->sendRequest('/api/location_rule_copy', $params, $raw, $hashKeys);

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
     * Delete a location rule
     *
     * @param int $ruleId Location rule ID to delete
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function deleteRule($ruleId, $raw = false)
    {
        // Validate rule ID
        if (!is_numeric($ruleId) || $ruleId <= 0) {
            throw new ApiException("Invalid location_rule_id");
        }

        $params = [
            'location_rule_id' => $ruleId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['location_rule_id'];

        $response = $this->sendRequest('/api/location_rule_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'success' => (string) $response->status->success,
        ];
    }
} 