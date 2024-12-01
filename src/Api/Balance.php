<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Balance extends BaseApi
{
    /**
     * @throws ApiException
     */
    public function get($username, $currency = null, $raw = false)
    {
        $params = [
            'username' => $username,
        ];

        if ($currency) {
            $params['currency'] = $currency;
        }

        // Define the hash keys in the expected order
        $hashKeys = ['username', 'currency'];

        $response = $this->sendRequest('/api/user_balance_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (float) $response->balance;
    }

    public function update($userId, $balance, $raw = false)
    {
        $params = [
            'user_id' => $userId,
            'balance' => $balance,
        ];

        $response = $this->sendRequest('/api/user_balance_update', $params, $raw);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status,
            'username' => (string) $response->user->username,
            'id' => (int) $response->user->id,
            'balance' => (float) $response->user->balance,
        ];
    }

    public function getSimpleBalance($uniqueHash, $currency = null, $raw = false)
    {
        $params = [
            'id' => $uniqueHash,
        ];

        if ($currency) {
            $params['currency'] = $currency;
        }

        $hashKeys = ['id', 'currency']; // Ensure 'currency' is conditionally included

        $response = $this->sendRequest('/api/user_simple_balance_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (float) $response;
    }

    public function getBalanceByPassword($password, $raw = false)
    {
        $response = $this->sendRequest('/api/user_balance_get_by_psw/' . $password, [], $raw);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Return the balance directly as a float
        return (float) $response;
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'User was not found':
                throw ApiException::userNotFound();
            case 'Feature disabled':
                throw ApiException::featureDisabled();
            case 'Bad login':
                throw new ApiException("Bad login credentials.");
            case 'User balance not updated':
                throw new ApiException("User balance not updated.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
}
