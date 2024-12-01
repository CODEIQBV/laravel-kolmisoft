<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class CreditNote extends BaseApi
{
    /**
     * Get credit notes using the MOR API
     *
     * @param int|null $userId The ID of the user whose credit notes to retrieve
     * @param int|null $creditNoteId The ID of the specific credit note to retrieve
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function getCreditNotes($userId = null, $creditNoteId = null, $raw = false)
    {
        $params = [
            'u' => config('kolmisoft.username'),
        ];

        if ($creditNoteId !== null) {
            $params['credit_note_id'] = $creditNoteId;
            $hashKeys = ['credit_note_id'];
        } elseif ($userId !== null) {
            $params['user_id'] = $userId;
            $hashKeys = ['user_id'];
        } else {
            $hashKeys = [];
        }

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/credit_notes_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the credit notes
        return json_decode(json_encode($response->credit_notes), true);
    }

    /**
     * Update a credit note using the MOR API
     *
     * @param int $creditNoteId The ID of the credit note to update
     * @param string|null $status The status of the credit note ('paid' or 'unpaid')
     * @param string|null $comment An optional comment for the credit note
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function updateCreditNote($creditNoteId, $status = null, $comment = null, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($creditNoteId) || $creditNoteId <= 0) {
            throw new ApiException("Invalid credit_note_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'credit_note_id' => $creditNoteId,
        ];

        if ($status !== null) {
            $params['status'] = $status;
        }

        if ($comment !== null) {
            $params['comment'] = $comment;
        }

        // Define hash keys for this endpoint
        // No parameters are included in hash, only API_Secret_Key is used
        $hashKeys = [];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/credit_note_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the credit note update status
        return [
            'status' => (string) $response->status,
        ];
    }

    /**
     * Create a credit note using the MOR API
     *
     * @param int $userId The ID of the user for whom the credit note is created
     * @param float $price The price of the credit note in system currency
     * @param int $issueDate The date the credit note was issued (Unix timestamp)
     * @param array $params Additional parameters for credit note creation
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function createCreditNote($userId, $price, $issueDate, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($userId) || $userId <= 0) {
            throw new ApiException("Invalid user_id");
        }

        if (!is_numeric($price) || $price <= 0) {
            throw new ApiException("Invalid price");
        }

        if (!is_numeric($issueDate) || $issueDate <= 0) {
            throw new ApiException("Invalid issue_date");
        }

        $params['u'] = config('kolmisoft.username');
        $params['user_id'] = $userId;
        $params['price'] = $price;
        $params['issue_date'] = $issueDate;

        // Define hash keys for this endpoint
        // user_id, number are included in hash
        $hashKeys = ['user_id', 'number'];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/credit_note_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the credit note creation status
        return [
            'status' => (string) $response->status,
        ];
    }

    /**
     * Delete a credit note using the MOR API
     *
     * @param int $creditNoteId The ID of the credit note to delete
     * @param bool $raw Whether to return raw response
     * @return array|object
     * @throws ApiException
     */
    public function deleteCreditNote($creditNoteId, $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($creditNoteId) || $creditNoteId <= 0) {
            throw new ApiException("Invalid credit_note_id");
        }

        $params = [
            'u' => config('kolmisoft.username'),
            'credit_note_id' => $creditNoteId,
        ];

        // Define hash keys for this endpoint
        // No parameters are included in hash, only API_Secret_Key is used
        $hashKeys = [];

        // Generate hash
        $params['hash'] = $this->generateHash($params, $hashKeys);

        // Send request to the API
        $response = $this->sendRequest('/api/credit_note_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        // Handle errors using the handleError method
        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        // Return the credit note delete status
        return [
            'status' => (string) $response->status,
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
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Credit note was not created':
                throw new ApiException("Credit note was not created.");
            case 'Bad login':
                throw new ApiException("Bad login.");
            case 'Credit note was not found':
                throw new ApiException("Credit note was not found.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 