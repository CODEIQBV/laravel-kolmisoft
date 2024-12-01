<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Device extends BaseApi
{
    /**
     * Create a device using the MOR API
     *
     * @param int $userId User ID for which the device should be created
     * @param array $params Additional parameters for device creation
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function createDevice($userId, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($userId) || $userId <= 0) {
            throw new ApiException("Invalid user_id");
        }

        $params['u'] = config('kolmisoft.username');
        $params['user_id'] = $userId;

        // Define hash keys for this endpoint
        // user_id, description, pin, type, devicegroup_id, caller_id are included in hash
        $hashKeys = ['user_id', 'description', 'pin', 'type', 'devicegroup_id', 'caller_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Return the device creation status and details
        return [
            'status' => (string) $response->status,
            'id' => (int) $response->id,
            'username' => (string) $response->username,
            'password' => (string) $response->password,
        ];
    }

    /**
     * Update a device using the MOR API
     *
     * @param int $deviceId The ID of the device to update
     * @param array $params Parameters for the update
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function updateDevice($deviceId, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Invalid device_id");
        }

        $params['u'] = config('kolmisoft.username');
        $params['device'] = $deviceId;

        // Define hash keys for this endpoint
        // device, authentication, username, host, port are included in hash
        $hashKeys = ['device', 'authentication', 'username', 'host', 'port'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device update status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Delete a device using the MOR API
     *
     * @param int $deviceId The ID of the device to delete
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function deleteDevice($deviceId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Invalid device_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'device' => $deviceId,
        ];

        // Define hash keys for this endpoint
        // device is included in hash
        $hashKeys = ['device'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device delete status
        return [
            'status' => (string) $response->status,
        ];
    }

    /**
     * Get a list of devices using the MOR API
     *
     * @param int $userId The ID of the user whose devices to retrieve
     * @param bool $showHiddenDevices Whether to show hidden devices
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getDevices($userId, $showHiddenDevices = true, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($userId) || $userId <= 0) {
            throw new ApiException("Invalid user_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'user_id' => $userId,
            'show_hidden_devices' => $showHiddenDevices ? 1 : 0,
        ];

        // Define hash keys for this endpoint
        // user_id is included in hash
        $hashKeys = ['user_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/devices_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the list of devices
        return json_decode(json_encode($response->devices), true);
    }

    /**
     * Get device details using the MOR API
     *
     * @param int|null $deviceId The ID of the device to get details for
     * @param string|null $deviceUsername The username of the device to get details for
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getDeviceDetails($deviceId = null, $deviceUsername = null, $raw = false)
    {
        // Validate required parameters
        if ($deviceId === null && $deviceUsername === null) {
            throw new ApiException("Either device_id or device_u must be provided.");
        }

        $params = [
            'u' => config('kolmisoft.username'),
        ];

        if ($deviceId !== null) {
            $params['device_id'] = $deviceId;
            $hashKeys = ['device_id'];
        } else {
            $params['device_u'] = $deviceUsername;
            $hashKeys = ['device_u'];
        }

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_details_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device details
        return json_decode(json_encode($response), true);
    }

    /**
     * Get device call flow using the MOR API
     *
     * @param int $deviceId The ID of the device to get call flow for
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getDeviceCallFlow($deviceId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Invalid device_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'device_id' => $deviceId,
        ];

        // Define hash keys for this endpoint
        // device_id is included in hash
        $hashKeys = ['device_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_callflow_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device call flow
        return json_decode(json_encode($response), true);
    }

    /**
     * Update device call flow using the MOR API
     *
     * @param int $deviceId The ID of the device to update call flow for
     * @param string $state The call flow state to edit
     * @param string $callflowAction The action to take at the specified state
     * @param array $params Additional parameters for call flow update
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function updateDeviceCallFlow($deviceId, $state, $callflowAction, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Invalid device_id");
        }

        if (empty($state) || empty($callflowAction)) {
            throw new ApiException("State and callflow_action are required.");
        }

        $params['u'] = config('kolmisoft.username');
        $params['device_id'] = $deviceId;
        $params['state'] = $state;
        $params['callflow_action'] = $callflowAction;

        // Define hash keys for this endpoint
        // device_id, state, callflow_action are included in hash
        $hashKeys = ['device_id', 'state', 'callflow_action'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_callflow_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device call flow update status
        return [
            'status' => (string) $response->status,
        ];
    }

    /**
     * Get CLI info using the MOR API
     *
     * @param string $cli The CLI number to get info for
     * @param string|null $domain The CLI domain to get info for
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getCLIInfo($cli, $domain = null, $raw = false)
    {
        // Validate required parameters
        if (empty($cli)) {
            throw new ApiException("CLI is empty.");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'cli' => $cli,
        ];

        if ($domain !== null) {
            $params['domain'] = $domain;
        }

        // Define hash keys for this endpoint
        // No parameters are included in hash, only API_Secret_Key is used
        $hashKeys = [];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/cli_info_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the CLI info
        return json_decode(json_encode($response->cli), true);
    }

    /**
     * Delete CLI using the MOR API
     *
     * @param string $cliNumber The CLI number to delete
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function deleteCLI($cliNumber, $raw = false)
    {
        // Validate required parameters
        if (empty($cliNumber)) {
            throw new ApiException("CLI number is empty.");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'cli_number' => $cliNumber,
        ];

        // Define hash keys for this endpoint
        // cli_number is included in hash
        $hashKeys = ['cli_number'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/cli_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the CLI delete status
        return [
            'status' => (string) $response->status,
        ];
    }

    /**
     * Add CLI using the MOR API
     *
     * @param int $deviceId The ID of the device to assign the CLI to
     * @param string|null $cliNumber The CLI number to add
     * @param array $params Additional parameters for CLI creation
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function addCLI($deviceId, $cliNumber = null, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Device ID cannot be empty.");
        }

        if (empty($cliNumber) && empty($params['cli_domain'])) {
            throw new ApiException("CLI Number cannot be empty.");
        }

        $params['u'] = config('kolmisoft.username');
        $params['device_id'] = $deviceId;
        if ($cliNumber !== null) {
            $params['cli_number'] = $cliNumber;
        }

        // Define hash keys for this endpoint
        // device_id, cli_number are included in hash
        $hashKeys = ['device_id', 'cli_number'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/cli_add', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the CLI add status
        return [
            'status' => (string) $response->status,
        ];
    }

    /**
     * Get device CLIs using the MOR API
     *
     * @param int|null $deviceId The ID of the device to get CLIs for
     * @param int|null $userId The ID of the user whose CLIs to get
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getDeviceCLIs($deviceId = null, $userId = null, $raw = false)
    {
        $params = [
            'u' => config('kolmisoft.username'),
        ];

        if ($deviceId !== null) {
            $params['devices_id'] = $deviceId;
        }

        if ($userId !== null) {
            $params['users_id'] = $userId;
        }

        // Define hash keys for this endpoint
        // No parameters are included in hash, only API_Secret_Key is used
        $hashKeys = [];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_clis_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device CLIs
        return json_decode(json_encode($response->status), true);
    }

    /**
     * Get device rules using the MOR API
     *
     * @param int $deviceId The ID of the device to get rules for
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getDeviceRules($deviceId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Invalid device_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'device_id' => $deviceId,
        ];

        // Define hash keys for this endpoint
        // device_id is included in hash
        $hashKeys = ['device_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_rules_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device rules
        return json_decode(json_encode($response->status->device_rules), true);
    }

    /**
     * Delete a device rule using the MOR API
     *
     * @param int $deviceRuleId The ID of the device rule to delete
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function deleteDeviceRule($deviceRuleId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceRuleId) || $deviceRuleId <= 0) {
            throw new ApiException("Invalid device_rule_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'device_rule_id' => $deviceRuleId,
        ];

        // Define hash keys for this endpoint
        // device_rule_id is included in hash
        $hashKeys = ['device_rule_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_rule_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device rule delete status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Create a device rule using the MOR API
     *
     * @param int $deviceId The ID of the device to create the rule for
     * @param string $name The name of the rule
     * @param string|null $cut The cut pattern
     * @param string|null $add The add pattern
     * @param array $params Additional parameters for rule creation
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function createDeviceRule($deviceId, $name, $cut = null, $add = null, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Invalid device_id");
        }

        if (empty($name)) {
            throw new ApiException("Name cannot be blank.");
        }

        if (empty($cut) && empty($add)) {
            throw new ApiException("Both add and cut cannot be blank.");
        }

        $params['u'] = config('kolmisoft.username');
        $params['device_id'] = $deviceId;
        $params['name'] = $name;
        $params['cut'] = $cut;
        $params['add'] = $add;

        // Define hash keys for this endpoint
        // device_id, name, cut, add are included in hash
        $hashKeys = ['device_id', 'name', 'cut', 'add'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/device_rule_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device rule creation status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Handle error responses
     *
     * @param string $error The error message
     * @throws ApiException
     */
    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Device was not found':
                throw new ApiException("Device was not found.");
            case 'CLIs were not found':
                throw new ApiException("CLIs were not found.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'You are not authorized to use this functionality':
                throw new ApiException("You are not authorized to use this functionality.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Device rule was not found':
                throw new ApiException("Device rule was not found.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Add failed':
                throw new ApiException("Add failed.");
            case 'name cannot be blank':
                throw new ApiException("Name cannot be blank.");
            case 'both add and cut cannot be blank':
                throw new ApiException("Both add and cut cannot be blank.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 