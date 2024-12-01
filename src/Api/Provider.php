<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Provider extends BaseApi
{
    // Constants for valid values
    const TECH_TYPES = ['dahdi', 'SIP', 'IAX2', 'H323'];
    const DTMF_MODES = ['inband', 'info', 'rfc2833', 'auto'];
    const DTMF_MODES_CCL_SIP = ['rfc2833', 'auto'];
    const NETWORK_TYPES = ['hostname', 'ip', 'dynamic'];

    public function getProviders($providerId = null, $raw = false)
    {
        $params = [];

        if ($providerId !== null) {
            $params['provider_id'] = $providerId;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: username is not included in the hash
        $hashKeys = [];

        $response = $this->sendRequest('/api/providers_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for providers
        $providers = [];

        // Check if providers exist and is not null
        if (isset($response->status->providers) && isset($response->status->providers->provider)) {
            // Handle case where there's only one provider (not an array)
            if (!is_array($response->status->providers->provider)) {
                $providers[] = $this->formatProvider($response->status->providers->provider);
            } else {
                // Handle multiple providers
                foreach ($response->status->providers->provider as $provider) {
                    $providers[] = $this->formatProvider($provider);
                }
            }
        }

        return $providers;
    }

    public function create($name, $tech, $tariffId, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!in_array($tech, self::TECH_TYPES)) {
            throw new ApiException("Invalid tech type. Must be one of: " . implode(', ', self::TECH_TYPES));
        }

        $providerParams = [
            'name' => $name,
            'tech' => $tech,
            'tariff_id' => $tariffId,
        ];

        // Add optional parameters
        $optionalParams = [
            'server_ids',
            'active',
            'dtmfmode',
            'location_id',
            'timeout',
            'max_timeout',
            'call_limit',
            'balance_limit',
            'login',
            'password',
            'register',
            'cid_name',
            'cid_number',
            'network_type',
            'server_ip',
            'ipaddr',
            'port',
            'fromdomain',
            'fromuser',
        ];

        foreach ($optionalParams as $param) {
            if (isset($params[$param])) {
                $providerParams[$param] = $params[$param];
            }
        }

        // Validate parameters
        $this->validateParameters($providerParams);

        // Add global username
        $providerParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['name', 'tech', 'tariff_id'];

        $response = $this->sendRequest('/api/provider_create', $providerParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status->success,
            'provider_id' => (int) $response->status->provider_id,
        ];
    }

    public function update($providerId, $params = [], $raw = false)
    {
        $providerParams = [
            'provider_id' => $providerId,
        ];

        // List of allowed update parameters
        $allowedParams = [
            'active',
            'name',
            'dtmfmode',
            'location_id',
            'timeout',
            'max_timeout',
            'call_limit',
            'balance_limit',
            'tariff_id',
            'server_ids',
            'login',
            'password',
            'register',
            'cid_name',
            'cid_number',
            'network_type',
            'server_ip',
            'ipaddr',
            'port',
            'fromdomain',
        ];

        // Add provided parameters to the request
        foreach ($allowedParams as $param) {
            if (isset($params[$param])) {
                $providerParams[$param] = $params[$param];
            }
        }

        // Validate parameters
        $this->validateParameters($providerParams);

        // Add global username
        $providerParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['provider_id'];

        $response = $this->sendRequest('/api/provider_update', $providerParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    public function delete($providerId, $raw = false)
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

        $response = $this->sendRequest('/api/provider_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    private function validateParameters($params)
    {
        // Validate login
        if (isset($params['login']) && in_array(strtolower($params['login']), ['anonymous', 'unknown'])) {
            throw new ApiException("Login cannot be 'anonymous' or 'unknown'");
        }

        // Validate dtmfmode
        if (isset($params['dtmfmode'])) {
            $validModes = ($params['tech'] === 'SIP') ? self::DTMF_MODES_CCL_SIP : self::DTMF_MODES;
            if (!in_array($params['dtmfmode'], $validModes)) {
                throw new ApiException("Invalid dtmfmode. Must be one of: " . implode(', ', $validModes));
            }
        }

        // Validate network_type
        if (isset($params['network_type'])) {
            if (!in_array($params['network_type'], self::NETWORK_TYPES)) {
                throw new ApiException("Invalid network_type. Must be one of: " . implode(', ', self::NETWORK_TYPES));
            }

            // Handle dynamic network type
            if ($params['network_type'] === 'dynamic') {
                $params['register'] = 0;
                $params['server_ip'] = 'dynamic';
                $params['port'] = 0;
            }
        }

        // Validate numeric values
        if (isset($params['timeout']) && $params['timeout'] < 30) {
            throw new ApiException("Timeout must be 30 or higher");
        }

        if (isset($params['max_timeout']) && $params['max_timeout'] < 0) {
            throw new ApiException("Max timeout must be 0 or higher");
        }

        if (isset($params['call_limit']) && $params['call_limit'] < 0) {
            throw new ApiException("Call limit must be 0 or higher");
        }

        if (isset($params['port']) && $params['port'] <= 0) {
            throw new ApiException("Port must be higher than 0");
        }

        // Additional validation for update-specific parameters
        if (isset($params['provider_id']) && (!is_numeric($params['provider_id']) || $params['provider_id'] <= 0)) {
            throw new ApiException("Invalid provider_id");
        }

        if (isset($params['active']) && !in_array($params['active'], [0, 1])) {
            throw new ApiException("Active must be 0 or 1");
        }

        if (isset($params['server_ids'])) {
            // Validate server_ids format (comma-separated numbers)
            if (!preg_match('/^\d+(?:,\d+)*$/', $params['server_ids'])) {
                throw new ApiException("server_ids must be in format '1,3,4'");
            }
        }
    }

    private function formatProvider($provider)
    {
        return [
            'id' => (int) $provider->id,
            'name' => (string) $provider->name,
            'tech' => (string) $provider->tech,
            'channel' => (string) $provider->channel,
            'login' => (string) $provider->login,
            'password' => (string) $provider->password,
            'server_ip' => (string) $provider->server_ip,
            'port' => (int) $provider->port,
            'priority' => (int) $provider->priority,
            'quality' => (int) $provider->quality,
            'tariff_id' => (int) $provider->tariff_id,
            'cut_a' => (int) $provider->cut_a,
            'cut_b' => (int) $provider->cut_b,
            'add_a' => (string) $provider->add_a,
            'add_b' => (string) $provider->add_b,
            'device_id' => (int) $provider->device_id,
            'ani' => (int) $provider->ani,
            'timeout' => (int) $provider->timeout,
            'call_limit' => (int) $provider->call_limit,
            'interpret_noanswer_as_failed' => (bool) $provider->interpret_noanswer_as_failed,
            'interpret_busy_as_failed' => (bool) $provider->interpret_busy_as_failed,
            'register' => (bool) $provider->register,
            'reg_extension' => (string) $provider->reg_extension,
            'terminator_id' => (int) $provider->terminator_id,
            'reg_line' => (string) $provider->reg_line,
            'hidden' => (bool) $provider->hidden,
            'use_p_asserted_identity' => (bool) $provider->use_p_asserted_identity,
            'user_id' => (int) $provider->user_id,
            'common_use' => (bool) $provider->common_use,
            'balance' => (float) $provider->balance,
            'balance_limit' => !empty($provider->balance_limit) ? (float) $provider->balance_limit : null,
            'cps_call_limit' => (int) $provider->cps_call_limit,
            'cps_period' => (int) $provider->cps_period,
            'alive' => (bool) $provider->alive,
            'periodic_check' => (bool) $provider->periodic_check,
            'active' => (bool) $provider->active,
            'contact_info_id' => (int) $provider->contact_info_id,
            'contact_info_partners_id' => (int) $provider->contact_info_partners_id,
            'contact_info_noc_id' => (int) $provider->contact_info_noc_id,
            'contact_info_rates_provisioning_id' => (int) $provider->contact_info_rates_provisioning_id,
            'contact_info_billing_provisioning_id' => (int) $provider->contact_info_billing_provisioning_id,
            'tech_details_info' => (string) $provider->tech_details_info,
            'privacy_from_domain' => (string) $provider->privacy_from_domain,
            'privacy_callerid' => (string) $provider->privacy_callerid,
            'playback_before_dial' => (bool) $provider->playback_before_dial,
            'playback_before_dial_mode' => (string) $provider->playback_before_dial_mode,
            'prov_enable_static_source_list' => (string) $provider->prov_enable_static_source_list,
            'prov_static_source_list_id' => (string) $provider->prov_static_source_list_id,
            'enable_mnp_tags' => (bool) $provider->enable_mnp_tags,
            'responsible_accountant_id' => (int) $provider->responsible_accountant_id,
            'sip_request_uri' => (string) $provider->sip_request_uri,
            'sip_to_uri' => (string) $provider->sip_to_uri,
            'prov_enable_static_destination_list' => (string) $provider->prov_enable_static_destination_list,
            'prov_static_destination_list_id' => (string) $provider->prov_static_destination_list_id,
            'use_tariffs_by_clis' => (bool) $provider->use_tariffs_by_clis,
            'use_default_tariff_if_by_cli_not_found' => (bool) $provider->use_default_tariff_if_by_cli_not_found,
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Provider was not found':
                throw new ApiException("Provider was not found.");
            case 'Provider name must be unique':
                throw new ApiException("Provider name must be unique.");
            case 'Provider name cannot be empty':
                throw new ApiException("Provider name cannot be empty.");
            case 'Invalid tariff':
                throw new ApiException("Invalid tariff ID provided.");
            case 'Cannot delete provider':
                throw new ApiException("Cannot delete provider - it may have associated data.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 