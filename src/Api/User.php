<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class User extends BaseApi
{
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

    private function handleError($response)
    {
        // Handle any general API errors
        if (isset($response->error)) {
            $error = (string) $response->error;
            switch ($error) {
                case 'Incorrect hash':
                    throw ApiException::incorrectHash();
                case 'API Requests are disabled':
                    throw new ApiException("API requests are disabled.");
                default:
                    throw new ApiException("An unknown error occurred: $error");
            }
        }

        // Handle login-specific errors
        if (isset($response->action) && $response->action->status === 'failed') {
            throw new ApiException(
                isset($response->action->status_message)
                    ? (string) $response->action->status_message
                    : "Login failed"
            );
        }
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