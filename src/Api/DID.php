<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class DID extends BaseApi
{
    /**
     * Get DIDs using the MOR API
     *
     * @param array $params Parameters for the request
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getDIDs($params = [], $raw = false)
    {
        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Only include parameters that are part of the hash
        $hashKeys = ['search_status']; // Adjust this list based on actual parameters needed

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/dids_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Return the DIDs as an array
        return json_decode(json_encode($response->dids), true);
    }

    /**
     * Create a DID using the MOR API
     *
     * @param int $providerId Provider ID for the DID
     * @param string $did DID number to be created
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function createDID($providerId, $did, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($providerId) || $providerId <= 0) {
            throw new ApiException("Invalid provider_id");
        }

        if (!is_numeric($did)) {
            throw new ApiException("Invalid DID specified");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'provider_id' => $providerId,
            'did' => $did,
        ];

        // Define hash keys for this endpoint
        // provider_id and did are included in hash
        $hashKeys = ['provider_id', 'did'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID creation status and details
        return [
            'success' => (string) $response->status->success,
            'did_id' => (int) $response->did_details->id,
        ];
    }

    /**
     * Assign a device to a DID using the MOR API
     *
     * @param int $deviceId Device ID to be assigned
     * @param string $did DID number to which the device will be assigned
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function assignDeviceToDID($deviceId, $did, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Invalid device_id");
        }

        if (!is_numeric($did)) {
            throw new ApiException("Invalid DID specified");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'device_id' => $deviceId,
            'did' => $did,
        ];

        // Define hash keys for this endpoint
        // device_id and did are included in hash
        $hashKeys = ['device_id', 'did'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_device_assign', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device assignment status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Assign a trunk device to a DID using the MOR API
     *
     * @param int $deviceId Trunk Device ID to be assigned
     * @param string $did DID number to which the trunk device will be assigned
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function assignTrunkDeviceToDID($deviceId, $did, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($deviceId) || $deviceId <= 0) {
            throw new ApiException("Invalid device_id");
        }

        if (!is_numeric($did)) {
            throw new ApiException("Invalid DID specified");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'device_id' => $deviceId,
            'did' => $did,
        ];

        // Define hash keys for this endpoint
        // device_id and did are included in hash
        $hashKeys = ['device_id', 'did'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_trunk_device_assign', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the trunk device assignment status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Unassign a device from a DID using the MOR API
     *
     * @param string $did DID number from which the device will be unassigned
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function unassignDeviceFromDID($did, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($did)) {
            throw new ApiException("Invalid DID specified");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'did' => $did,
        ];

        // Define hash keys for this endpoint
        // did is included in hash
        $hashKeys = ['did'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_device_unassign', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the device unassignment status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Update details of a DID using the MOR API
     *
     * @param int $didId The ID of the DID to be updated
     * @param array $params Parameters for the update
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function updateDIDDetails($didId, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($didId) || $didId <= 0) {
            throw new ApiException("Invalid did_id");
        }

        $params['u'] = config('kolmisoft.username');
        $params['did_id'] = $didId;

        // Convert dates to timestamps if provided as DateTime objects
        if (isset($params['active_from']) && $params['active_from'] instanceof \DateTime) {
            $params['active_from'] = $params['active_from']->getTimestamp();
        }

        if (isset($params['active_till']) && $params['active_till'] instanceof \DateTime) {
            $params['active_till'] = $params['active_till']->getTimestamp();
        }

        // Define hash keys for this endpoint
        // did_id is included in hash
        $hashKeys = ['did_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_details_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID update status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Stop a DID subscription using the MOR API
     *
     * @param int $didId The ID of the DID to stop the subscription
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function stopDIDSubscription($didId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($didId) || $didId <= 0) {
            throw new ApiException("Invalid did_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'did_id' => $didId,
        ];

        // Define hash keys for this endpoint
        // did_id is included in hash
        $hashKeys = ['did_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_subscription_stop', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID subscription stop status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Terminate a DID using the MOR API
     *
     * @param int $didsId The ID of the DID to terminate
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function terminateDID($didsId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($didsId) || $didsId <= 0) {
            throw new ApiException("Invalid dids_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'dids_id' => $didsId,
        ];

        // Define hash keys for this endpoint
        // dids_id is included in hash
        $hashKeys = ['dids_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_terminate', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID termination status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Make a DID free using the MOR API
     *
     * @param int $didsId The ID of the DID to make free
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function makeDIDFree($didsId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($didsId) || $didsId <= 0) {
            throw new ApiException("Invalid dids_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'dids_id' => $didsId,
        ];

        // Define hash keys for this endpoint
        // dids_id is included in hash
        $hashKeys = ['dids_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_make_free', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID make free status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Update DID rates using the MOR API
     *
     * @param string $did The DID number to update rates for
     * @param array $params Parameters for the rate update
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function updateDIDRates($did, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($did)) {
            throw new ApiException("Invalid DID specified");
        }

        $params['u'] = config('kolmisoft.username');
        $params['did'] = $did;

        // Define hash keys for this endpoint
        // did is included in hash
        $hashKeys = ['did'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_rates_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID rates update status
        return [
            'updated_rates' => (int) $response->updated_rates,
            'rates' => json_decode(json_encode($response->rates), true),
        ];
    }

    /**
     * Get DID rates using the MOR API
     *
     * @param string $did The DID number to get rates for
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getDIDRates($did, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($did)) {
            throw new ApiException("Invalid DID specified");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'did' => $did,
        ];

        // Define hash keys for this endpoint
        // did is included in hash
        $hashKeys = ['did'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_rates_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID rates
        return json_decode(json_encode($response->rates), true);
    }

    /**
     * Close a DID using the MOR API
     *
     * @param int $didsId The ID of the DID to close
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function closeDID($didsId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($didsId) || $didsId <= 0) {
            throw new ApiException("Invalid dids_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'dids_id' => $didsId,
        ];

        // Define hash keys for this endpoint
        // dids_id is included in hash
        $hashKeys = ['dids_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_close', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID close status
        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Delete a DID using the MOR API
     *
     * @param int $didsId The ID of the DID to delete
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function deleteDID($didsId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($didsId) || $didsId <= 0) {
            throw new ApiException("Invalid dids_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'dids_id' => $didsId,
        ];

        // Define hash keys for this endpoint
        // dids_id is included in hash
        $hashKeys = ['dids_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID delete status
        return [
            'status' => (string) $response->status->status,
        ];
    }

    /**
     * Get DID rates details using the MOR API
     *
     * @param int $didId The ID of the DID to get rates details for
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getDIDRatesDetails($didId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($didId) || $didId <= 0) {
            throw new ApiException("Invalid did_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'did_id' => $didId,
        ];

        // Define hash keys for this endpoint
        // did_id is included in hash
        $hashKeys = ['did_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_rates_details_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID rates details
        return json_decode(json_encode($response->status->did), true);
    }

    /**
     * Update DID rates details using the MOR API
     *
     * @param int $didId The ID of the DID to update rates details for
     * @param array $params Parameters for the update
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function updateDIDRatesDetails($didId, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($didId) || $didId <= 0) {
            throw new ApiException("Invalid did_id");
        }

        $params['u'] = config('kolmisoft.username');
        $params['did_id'] = $didId;

        // Define hash keys for this endpoint
        // did_id is included in hash
        $hashKeys = ['did_id'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/did_rates_details_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the DID rates details update status
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
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'DID was not found':
                throw new ApiException("DID was not found.");
            case 'Rates were not found':
                throw new ApiException("Rates were not found.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 