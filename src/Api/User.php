<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class User extends BaseApi
{
    public function register($params = [], $raw = false)
    {
        // Ensure required parameters are set
        $requiredParams = ['email', 'id', 'device_type', 'username', 'password', 'password2', 'country_id'];
        foreach ($requiredParams as $param) {
            if (!isset($params[$param])) {
                throw new ApiException("Missing required parameter: $param");
            }
        }

        // Add global username and password
        $params['u'] = config('kolmisoft.username');
        $params['p'] = config('kolmisoft.password');

        // Specify the keys to include in the hash
        $hashKeys = ['email', 'id', 'device_type', 'username'];

        $response = $this->sendRequest('/api/user_register', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status->success,
            'user_device_settings' => json_decode(json_encode($response->user_device_settings), true),
        ];
    }

    public function getDetails($params = [], $raw = false)
    {
        // Ensure at least one identifier is provided
        if (!isset($params['user_id']) && !isset($params['username'])) {
            throw new ApiException("Either user_id or username must be provided.");
        }

        // Add global username and password
        $params['u'] = config('kolmisoft.username');
        $params['p'] = config('kolmisoft.password');

        // Specify the keys to include in the hash
        $hashKeys = ['user_id', 'username'];

        $response = $this->sendRequest('/api/user_details_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Return the user details as an array
        return json_decode(json_encode($response->details), true);
    }

    public function getRawDetails($params = [], $raw = false)
    {
        // Ensure at least one identifier is provided
        if (!isset($params['user_id']) && !isset($params['username'])) {
            throw new ApiException("Either user_id or username must be provided.");
        }

        // Add global username and password
        $params['u'] = config('kolmisoft.username');
        $params['p'] = config('kolmisoft.password');

        // Specify the keys to include in the hash
        $hashKeys = ['user_id', 'username'];

        $response = $this->sendRequest('/api/user_details_raw_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Return the raw user details as an array
        return json_decode(json_encode($response), true);
    }

    public function updateDetails($params = [], $raw = false)
    {
        // Ensure user_id is provided
        if (!isset($params['user_id'])) {
            throw new ApiException("user_id is required.");
        }

        // Add global username and password
        $params['u'] = config('kolmisoft.username');
        $params['p'] = config('kolmisoft.password');

        // Specify the keys to include in the hash
        $hashKeys = ['user_id'];

        $response = $this->sendRequest('/api/user_details_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status,
        ];
    }

    public function getUsers($params = [], $raw = false)
    {
        // Add global username and password
        $params['u'] = config('kolmisoft.username');
        $params['p'] = config('kolmisoft.password');

        // Specify the keys to include in the hash
        $hashKeys = ['u', 'p'];

        $response = $this->sendRequest('/api/users_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Return the list of users as an array
        return json_decode(json_encode($response->status->users), true);
    }

    public function deleteUser($params = [], $raw = false)
    {
        // Ensure user_id is provided
        if (!isset($params['user_id'])) {
            throw new ApiException("user_id is required.");
        }

        // Add global username and password
        $params['u'] = config('kolmisoft.username');
        $params['p'] = config('kolmisoft.password');

        // Specify the keys to include in the hash
        $hashKeys = ['user_id'];

        $response = $this->sendRequest('/api/user_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status,
        ];
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
            case 'Please enter username':
                throw new ApiException("Please enter username.");
            case 'Enter device type':
                throw new ApiException("Enter device type.");
            case 'Such username is already taken':
                throw new ApiException("Such username is already taken.");
            case 'Passwords do not match':
                throw new ApiException("Passwords do not match.");
            case 'Password must be longer than (...) characters':
                throw new ApiException("Password must be longer than (...) characters.");
            case 'Please enter first name':
                throw new ApiException("Please enter first name.");
            case 'Please enter last name':
                throw new ApiException("Please enter last name.");
            case 'Please select country':
                throw new ApiException("Please select country.");
            case 'Please enter email':
                throw new ApiException("Please enter email.");
            case 'This email address is already in use':
                throw new ApiException("This email address is already in use.");
            case 'User with mobile phone already exists':
                throw new ApiException("User with mobile phone already exists.");
            case 'User with phone already exists':
                throw new ApiException("User with phone already exists.");
            case 'User with fax already exists':
                throw new ApiException("User with fax already exists.");
            case 'Default user is not present':
                throw new ApiException("Default user is not present.");
            case 'LCR was not found':
                throw new ApiException("LCR was not found.");
            case 'Location was not found':
                throw new ApiException("Location was not found.");
            case 'Password must be longer than 7 characters or short passwords in Devices should be allowed':
                throw new ApiException("Password must be longer than 7 characters or short passwords in Devices should be allowed.");
            case 'Password must contain at least one numeric value, capital and lowercase symbol':
                throw new ApiException("Password must contain at least one numeric value, capital and lowercase symbol.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'User id must be present':
                throw new ApiException("User id must be present.");
            case 'User id must be non negative integer':
                throw new ApiException("User id must be non negative integer.");
            case 'You have no editing permission':
                throw new ApiException("You have no editing permission.");
            case 'Tariff not found':
                throw new ApiException("Tariff not found.");
            case 'User was not updated':
                throw new ApiException("User was not updated.");
            case 'Responsible accountant is not valid':
                throw new ApiException("Responsible accountant is not valid.");
            case 'API Requests are disabled':
                throw new ApiException("API Requests are disabled.");
            case 'GET Requests are disabled':
                throw new ApiException("GET Requests are disabled.");
            case 'You are not authorized to view this page':
                throw new ApiException("You are not authorized to view this page.");
            case 'Cannot delete user - it has DIDs':
                throw new ApiException("Cannot delete user - it has DIDs.");
            case 'Cannot delete user - he has some calls':
                throw new ApiException("Cannot delete user - he has some calls.");
            case 'Cannot delete User - he has Invoices':
                throw new ApiException("Cannot delete User - he has Invoices.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }

    /**
     * Login user to MOR system
     *
     * @param string $username Username or email (if email login is enabled)
     * @param string $password User's password
     * @param bool $raw Whether to return raw response
     * @return array{
     *     name: string,
     *     status: string,
     *     user_id: ?int,
     *     status_message: string
     * }|object
     * @throws ApiException
     */
    public function login($username, $password, $raw = false)
    {
        if (empty($username)) {
            throw new ApiException("Username is required");
        }

        if (empty($password)) {
            throw new ApiException("Password is required");
        }

        $params = [
            'u' => $username,
            'p' => $password,
        ];

        // Define hash keys for this endpoint
        // Note: Both username and password are included in hash
        $hashKeys = ['u', 'p'];

        $response = $this->sendRequest('/api/user_login', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (!isset($response->action)) {
            throw new ApiException("Invalid response format");
        }

        return $this->formatLoginResponse($response->action);
    }

    /**
     * Format the login response into a consistent structure
     *
     * @param object $action The action node from the response
     * @return array The formatted response
     */
    private function formatLoginResponse($action)
    {
        $response = [
            'name' => (string) $action->name,
            'status' => (string) $action->status,
            'status_message' => isset($action->status_message)
                ? (string) $action->status_message
                : ($action->status === 'ok' ? 'Successfully logged in' : 'Login failed'),
        ];

        // User ID is only included in successful logins from MOR11 onwards
        if (isset($action->user_id)) {
            $response['user_id'] = (int) $action->user_id;
        }

        // Validate login status
        if ($response['status'] === 'failed') {
            throw new ApiException($response['status_message']);
        }

        return $response;
    }

    /**
     * Check if a string looks like an email address
     *
     * @param string $username
     * @return bool
     */
    private function isEmail($username)
    {
        return filter_var($username, FILTER_VALIDATE_EMAIL) !== false;
    }


    /**
     * Logout user from MOR system
     *
     * @param bool $raw Whether to return raw response
     * @return array{name: string, status: string}|object
     * @throws ApiException
     */
    public function logout($raw = false)
    {
        $params = [
            'u' => config('kolmisoft.username'),
        ];

        // Define hash keys for this endpoint
        // Note: No parameters are included in hash for logout
        $hashKeys = [];

        $response = $this->sendRequest('/api/user_logout', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (!isset($response->action)) {
            throw new ApiException("Invalid response format");
        }

        return $this->formatLogoutResponse($response->action);
    }

    /**
     * Format the logout response into a consistent structure
     *
     * @param object $action The action node from the response
     * @return array The formatted response
     * @throws ApiException
     */
    private function formatLogoutResponse($action)
    {
        $response = [
            'name' => (string) $action->name,
            'status' => (string) $action->status,
        ];

        // Throw exception on failed logout
        if ($response['status'] === 'failed') {
            throw new ApiException("Logout failed");
        }

        return $response;
    }

}
