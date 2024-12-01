<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Quickforward extends BaseApi
{
    public function getDids($raw = false)
    {
        $params = [];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: username is not included in the hash for this endpoint
        $hashKeys = [];

        $response = $this->sendRequest('/api/quickforwards_dids_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for DIDs
        $dids = [];

        // Check if quick_forward_did exists and is not null
        if (isset($response->quick_forward_did)) {
            // Handle case where there's only one DID (not an array)
            if (!is_array($response->quick_forward_did)) {
                $dids[] = $this->formatDid($response->quick_forward_did);
            } else {
                // Handle multiple DIDs
                foreach ($response->quick_forward_did as $did) {
                    $dids[] = $this->formatDid($did);
                }
            }
        }

        return $dids;
    }

    public function updateDid($did, $forwardTo = null, $description = null, $raw = false)
    {
        $params = [
            'did' => $did,
        ];

        // Add optional parameters if provided
        if ($forwardTo !== null) {
            $params['forward_to'] = $forwardTo;
        }

        if ($description !== null) {
            $params['description'] = $description;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['did', 'forward_to', 'description'];

        $response = $this->sendRequest('/api/quickforwards_did_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Format and return the updated DID information
        return $this->formatDid($response->new_quickforward_did);
    }

    private function formatDid($did)
    {
        return [
            'did' => (string) $did->did,
            'forward_to' => (string) $did->forward_to,
            'description' => (string) $did->description,
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Quickforwards list is empty':
                throw new ApiException("Quickforwards list is empty.");
            case 'You are not authorized to use Quickforwards':
                throw new ApiException("You are not authorized to use Quickforwards.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'DID was not found':
                throw new ApiException("DID was not found.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 