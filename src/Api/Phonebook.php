<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Phonebook extends BaseApi
{
    public function getPhonebooks($userId, $raw = false)
    {
        if (!is_numeric($userId) || $userId <= 0) {
            throw new ApiException("Invalid user_id");
        }

        $params = [
            'user_id' => $userId,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['user_id'];

        $response = $this->sendRequest('/api/phonebooks_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for phonebooks
        $phonebooks = [];

        // Check if phonebooks exist and is not null
        if (isset($response->phonebooks) && isset($response->phonebooks->phonebook)) {
            // Handle case where there's only one phonebook entry (not an array)
            if (!is_array($response->phonebooks->phonebook)) {
                $phonebooks[] = $this->formatPhonebook($response->phonebooks->phonebook);
            } else {
                // Handle multiple phonebook entries
                foreach ($response->phonebooks->phonebook as $phonebook) {
                    $phonebooks[] = $this->formatPhonebook($phonebook);
                }
            }
        }

        return $phonebooks;
    }

    public function update($phonebookId, $params = [], $raw = false)
    {
        if (!is_numeric($phonebookId) || $phonebookId <= 0) {
            throw new ApiException("Invalid phonebook_id");
        }

        $phonebookParams = [
            'phonebook_id' => $phonebookId,
        ];

        // Add optional parameters
        $optionalParams = [
            'name',
            'number',
            'speeddial',
        ];

        foreach ($optionalParams as $param) {
            if (isset($params[$param])) {
                $phonebookParams[$param] = $params[$param];
            }
        }

        // Add global username
        $phonebookParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['phonebook_id'];

        $response = $this->sendRequest('/api/phonebook_edit', $phonebookParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return (string) $response->status;
    }

    public function create($userId, $name, $number, $speeddial, $raw = false)
    {
        // Validate required parameters
        if (empty($name)) {
            throw new ApiException("Name must be provided");
        }

        if (empty($number)) {
            throw new ApiException("Record number should have at least one digit");
        }

        if (empty($speeddial)) {
            throw new ApiException("Speed Dial name must be provided");
        }

        if (!is_numeric($number)) {
            throw new ApiException("Record number must be numeric");
        }

        if (!is_numeric($speeddial)) {
            throw new ApiException("Speed Dial must be numeric");
        }

        if (strlen($speeddial) < 2) {
            throw new ApiException("Speed Dial should have at least two digits");
        }

        $params = [
            'user_id' => $userId,
            'name' => $name,
            'number' => $number,
            'speeddial' => $speeddial,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['user_id', 'number', 'name', 'speeddial'];

        $response = $this->sendRequest('/api/phonebook_record_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return (string) $response->status->success;
    }

    private function formatPhonebook($phonebook)
    {
        return [
            'id' => (int) $phonebook->id,
            'name' => (string) $phonebook->name,
            'number' => (string) $phonebook->number,
            'speeddial' => (string) $phonebook->speeddial,
        ];
    }

    private function handleError($response)
    {
        $error = (string) $response->error;
        $message = isset($response->message) ? (string) $response->message : null;

        switch ($error) {
            case 'No Phonebooks':
                throw new ApiException("User does not have phonebooks.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Phonebook was not found':
                throw new ApiException("Phonebook was not found.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Phonebook was not saved':
                $errorMessage = $message 
                    ? "Failed to save phonebook: $message"
                    : "Failed to save phonebook.";
                throw new ApiException($errorMessage);
            case 'Speed Dial name must be provided':
                throw new ApiException("Speed Dial name must be provided.");
            case 'Speed Dial should have at least two digits':
                throw new ApiException("Speed Dial should have at least two digits.");
            case 'Record number should have at least one digit':
                throw new ApiException("Record number should have at least one digit.");
            case 'Speed Dial must be numeric':
                throw new ApiException("Speed Dial must be numeric.");
            case 'Record number must be numeric':
                throw new ApiException("Record number must be numeric.");
            case 'Speed dial must be unique':
                throw new ApiException("Speed dial must be unique.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 