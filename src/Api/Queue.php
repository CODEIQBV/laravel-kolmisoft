<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;
use DateTime;

class Queue extends BaseApi
{
    public function getLogs($params = [], $raw = false)
    {
        // Convert DateTime objects to timestamps if provided
        if (isset($params['from']) && $params['from'] instanceof DateTime) {
            $params['from'] = $params['from']->getTimestamp();
        }
        if (isset($params['till']) && $params['till'] instanceof DateTime) {
            $params['till'] = $params['till']->getTimestamp();
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: username is not included in the hash
        $hashKeys = [];

        $response = $this->sendRequest('/api/queue_log_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for queue logs
        $logs = [];

        // Check if queues exist and is not null
        if (isset($response->queues) && isset($response->queues->queue)) {
            // Handle case where there's only one log entry (not an array)
            if (!is_array($response->queues->queue)) {
                $logs[] = $this->formatQueueLog($response->queues->queue);
            } else {
                // Handle multiple log entries
                foreach ($response->queues->queue as $queue) {
                    $logs[] = $this->formatQueueLog($queue);
                }
            }
        }

        return $logs;
    }

    private function formatQueueLog($queue)
    {
        return [
            'id' => (int) $queue->id,
            'call_id' => (string) $queue->call_id,
            'queue_name' => (string) $queue->queue_name,
            'agent' => (string) $queue->agent,
            'event' => (string) $queue->event,
            'data1' => (string) $queue->data1,
            'data2' => (string) $queue->data2,
            'data3' => (string) $queue->data3,
            'data4' => (string) $queue->data4,
            'data5' => (string) $queue->data5,
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'No data found':
                throw new ApiException("No queue log data found.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 