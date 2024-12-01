<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class ProviderRule extends BaseApi
{
    const TYPE_SRC = 'src';
    const TYPE_DST = 'dst';

    public function getRules($providerId, $raw = false)
    {
        if (!is_numeric($providerId) || $providerId <= 0) {
            throw new ApiException("Invalid provider_id");
        }

        $params = [
            'provider_id' => $providerId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['provider_id'];

        $response = $this->sendRequest('/api/provider_rules_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for rules
        $rules = [];

        // Check if rules exist and is not null
        if (isset($response->status->provider_rules) && isset($response->status->provider_rules->provider_rule)) {
            // Handle case where there's only one rule (not an array)
            if (!is_array($response->status->provider_rules->provider_rule)) {
                $rules[] = $this->formatRule($response->status->provider_rules->provider_rule);
            } else {
                // Handle multiple rules
                foreach ($response->status->provider_rules->provider_rule as $rule) {
                    $rules[] = $this->formatRule($rule);
                }
            }
        }

        return $rules;
    }

    public function delete($ruleId, $raw = false)
    {
        if (!is_numeric($ruleId) || $ruleId <= 0) {
            throw new ApiException("Invalid provider_rule_id");
        }

        $params = [
            'provider_rule_id' => $ruleId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['provider_rule_id'];

        $response = $this->sendRequest('/api/provider_rule_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    public function create($providerId, $name, $cut, $add, $params = [], $raw = false)
    {
        // Validate required parameters
        if (empty($name)) {
            throw new ApiException("name cannot be blank");
        }

        if (empty($cut) && empty($add)) {
            throw new ApiException("both add and cut cannot be blank");
        }

        $ruleParams = [
            'provider_id' => $providerId,
            'name' => $name,
            'cut' => $cut,
            'add' => $add,
        ];

        // Add optional parameters with defaults
        $ruleParams['minlen'] = $params['minlen'] ?? 1;
        $ruleParams['maxlen'] = $params['maxlen'] ?? 100;
        $ruleParams['pr_type'] = $params['pr_type'] ?? self::TYPE_DST;
        $ruleParams['change_callerid_name'] = $params['change_callerid_name'] ?? 0;

        // Add other optional parameters
        $optionalParams = [
            'tariff_id',
            'set_pai',
            'suffix',
        ];

        foreach ($optionalParams as $param) {
            if (isset($params[$param])) {
                $ruleParams[$param] = $params[$param];
            }
        }

        // Validate parameters
        $this->validateCreateParameters($ruleParams);

        // Add global username
        $ruleParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['provider_id', 'name', 'cut', 'add'];

        $response = $this->sendRequest('/api/provider_rule_create', $ruleParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    private function validateCreateParameters($params)
    {
        // Validate provider_id
        if (!is_numeric($params['provider_id']) || $params['provider_id'] <= 0) {
            throw new ApiException("Invalid provider_id");
        }

        // Validate minlen and maxlen
        if ($params['minlen'] < 1) {
            throw new ApiException("minlen must be greater than 0");
        }

        if ($params['maxlen'] < $params['minlen']) {
            throw new ApiException("maxlen must be greater than or equal to minlen");
        }

        // Validate pr_type
        if (!in_array($params['pr_type'], [self::TYPE_SRC, self::TYPE_DST])) {
            throw new ApiException("pr_type must be either 'src' or 'dst'");
        }

        // Validate boolean parameters
        foreach (['change_callerid_name', 'set_pai'] as $param) {
            if (isset($params[$param]) && !in_array($params[$param], [0, 1])) {
                throw new ApiException("$param must be either 0 or 1");
            }
        }

        // Validate suffix is only set for dst type
        if (isset($params['suffix']) && $params['pr_type'] !== self::TYPE_DST) {
            throw new ApiException("suffix can only be set when pr_type is 'dst'");
        }

        // Validate tariff_id if provided
        if (isset($params['tariff_id']) && (!is_numeric($params['tariff_id']) || $params['tariff_id'] <= 0)) {
            throw new ApiException("Invalid tariff_id");
        }
    }

    private function formatRule($rule)
    {
        return [
            'id' => (int) $rule->id,
            'provider_id' => (int) $rule->provider_id,
            'name' => (string) $rule->name,
            'enabled' => (bool) $rule->enabled,
            'cut' => (string) $rule->cut,
            'add' => (string) $rule->add,
            'minlen' => (int) $rule->minlen,
            'maxlen' => (int) $rule->maxlen,
            'pr_type' => (string) $rule->pr_type,
            'change_callerid_name' => (bool) $rule->change_callerid_name,
            'tariff_id' => !empty($rule->tariff_id) ? (int) $rule->tariff_id : null,
            'set_pai' => (bool) $rule->set_pai,
            'suffix' => (string) $rule->suffix,
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Provider was not found':
                throw new ApiException("Provider was not found.");
            case 'Provider has no rules':
                throw new ApiException("Provider has no rules.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Provider rule was not found':
                throw new ApiException("Provider rule was not found or you don't have permission to manage this rule.");
            case 'Add failed':
                throw new ApiException("Failed to create rule.");
            case 'name cannot be blank':
                throw new ApiException("Rule name cannot be blank.");
            case 'both add and cut cannot be blank':
                throw new ApiException("Both add and cut patterns cannot be blank.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 