<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class PbxPool extends BaseApi
{
    public function create($name, $comment = null, $raw = false)
    {
        // Validate required parameters
        if (empty($name)) {
            throw new ApiException("PbxPool must have name");
        }

        $params = [
            'name' => $name,
        ];

        // Add optional comment if provided
        if ($comment !== null) {
            $params['comment'] = $comment;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['name'];

        $response = $this->sendRequest('/api/pbx_pool_create', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return (string) $response->status->success;
    }

    private function handleError($response)
    {
        $error = (string) $response->error;

        switch ($error) {
            case 'PbxPool must have name':
                throw new ApiException("PbxPool must have name.");
            case 'PbxPool name must be unique':
                throw new ApiException("PbxPool name must be unique.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'You are not authorised to use this functionality':
            case 'You are not authorized to use this functionality':
                throw new ApiException("You are not authorized to use this functionality.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 