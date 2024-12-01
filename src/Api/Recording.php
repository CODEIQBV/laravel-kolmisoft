<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Recording extends BaseApi
{
    public function get($params = [], $raw = false)
    {
        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Convert dates to timestamps if provided as DateTime objects
        if (isset($params['date_from']) && $params['date_from'] instanceof \DateTime) {
            $params['date_from'] = $params['date_from']->getTimestamp();
        }

        if (isset($params['date_till']) && $params['date_till'] instanceof \DateTime) {
            $params['date_till'] = $params['date_till']->getTimestamp();
        }

        // Validate date range if both dates are provided
        if (isset($params['date_from'], $params['date_till'])) {
            if ($params['date_from'] > $params['date_till']) {
                throw new ApiException("Date from is greater than date till.");
            }
        }

        // Define hash keys for this endpoint (even if empty)
        $hashKeys = [];

        $response = $this->sendRequest('/api/recordings_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for recordings
        $recordings = [];

        // Check if recordings exist and is not null
        if (isset($response->status->recordings) && isset($response->status->recordings->recording)) {
            // Handle case where there's only one recording (not an array)
            if (!is_array($response->status->recordings->recording)) {
                $recording = $response->status->recordings->recording;
                $recordings[] = $this->formatRecording($recording);
            } else {
                // Handle multiple recordings
                foreach ($response->status->recordings->recording as $recording) {
                    $recordings[] = $this->formatRecording($recording);
                }
            }
        }

        return $recordings;
    }

    public function update($recordingId, $comment = null, $raw = false)
    {
        $params = [
            'recording_id' => $recordingId,
        ];

        if ($comment !== null) {
            $params['comment'] = $comment;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['recording_id'];

        $response = $this->sendRequest('/api/recording_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    public function delete($params = [], $raw = false)
    {
        // Validate date range if both dates are provided
        if (isset($params['date_from'], $params['date_till'])) {
            if ($params['date_from'] > $params['date_till']) {
                throw new ApiException("Date from is greater than date till.");
            }
        }

        // Validate device_id requires user_id
        if (isset($params['s_device_id']) && !isset($params['s_user_id'])) {
            throw new ApiException("s_user_id must be present when using s_device_id.");
        }

        // Convert dates to timestamps if provided as DateTime objects
        if (isset($params['date_from']) && $params['date_from'] instanceof \DateTime) {
            $params['date_from'] = $params['date_from']->getTimestamp();
        }

        if (isset($params['date_till']) && $params['date_till'] instanceof \DateTime) {
            $params['date_till'] = $params['date_till']->getTimestamp();
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint in the correct order
        $hashKeys = ['recording_id', 's_user_id', 's_device_id', 'date_from', 'date_till'];

        $response = $this->sendRequest('/api/recordings_delete', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status->success,
            'amount' => (int) $response->status->amount,
        ];
    }

    private function formatRecording($recording)
    {
        return [
            'id' => (int) $recording->id,
            'user_id' => (int) $recording->user_id,
            'dst_user_id' => (int) $recording->dst_user_id,
            'src_device_id' => (int) $recording->src_device_id,
            'dst_device_id' => (int) $recording->dst_device_id,
            'date' => (string) $recording->date,
            'comment' => (string) $recording->comment,
            'duration' => (int) $recording->duration,
            'destination' => (string) $recording->destination,
            'size' => (int) $recording->size,
            'mp3_url' => (string) $recording->mp3_url,
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'API Requests are disabled':
                throw new ApiException("API Requests are disabled.");
            case 'GET Requests are disabled':
                throw new ApiException("GET Requests are disabled.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'No Recordings found':
                throw new ApiException("No Recordings found.");
            case 'Date from is incorrect format':
                throw new ApiException("Date from must be used in Unix time stamp format.");
            case 'Date till is incorrect format':
                throw new ApiException("Date till must be used in Unix time stamp format.");
            case 'Date from is greater than date till':
                throw new ApiException("Date from cannot be greater than date till.");
            case 'Recording was not found':
                throw new ApiException("Recording was not found.");
            case 'Recordings were not found':
                throw new ApiException("Recordings were not found.");
            case 's_user_id must be present':
                throw new ApiException("s_user_id must be present when using s_device_id.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 