<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class SMS extends BaseApi
{
    public function send($lcrId, $destination, $source, $message, $raw = false)
    {
        $params = [
            'lcr_id' => $lcrId,
            'dst' => $destination,
            'src' => $source,
            'message' => urlencode($message),
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['lcr_id', 'dst', 'src', 'message'];

        $response = $this->sendRequest('/api/sms_send', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->response->status,
            'message_id' => (string) $response->response->message->message_id,
            'sms_status_code_tip' => (string) $response->response->message->sms_status_code_tip,
            'price' => (float) $response->response->message->price,
            'currency' => (string) $response->response->message->currency,
        ];
    }

    public function subscribeUser($userId, $smsTariffId, $smsLcrId = null, $raw = false)
    {
        $params = [
            'user_id' => $userId,
            'sms_tariff_id' => $smsTariffId,
        ];

        if ($smsLcrId !== null) {
            $params['sms_lcr_id'] = $smsLcrId;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['user_id', 'sms_tariff_id'];

        $response = $this->sendRequest('/api/user_sms_service_subscribe', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return (string) $response->status->success;
    }

    public function getUserSMS($params = [], $raw = false)
    {
        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['from', 'till', 'user_id', 'status_code', 'provider_id', 'reseller_id', 'destination', 'number'];

        $response = $this->sendRequest('/api/user_sms_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Convert XML response to array
        $messages = [];
        foreach ($response->sms_messages->sms_message as $message) {
            $messages[] = [
                'id' => (int) $message->id,
                'sending_date' => (string) $message->sending_date,
                'status_code' => (int) $message->status_code,
                'provider_id' => (string) $message->provider_id,
                'provider_rate' => (float) $message->provider_rate,
                'provider_price' => (float) $message->provider_price,
                'user_id' => (int) $message->user_id,
                'user_rate' => (float) $message->user_rate,
                'user_price' => (float) $message->user_price,
                'reseller_id' => (int) $message->reseller_id,
                'reseller_rate' => (float) $message->reseller_rate,
                'reseller_price' => (float) $message->reseller_price,
                'prefix' => (string) $message->prefix,
                'number' => (string) $message->number,
                'clickatell_message_id' => (string) $message->clickatell_message_id,
            ];
        }

        return $messages;
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'There is no message or it is empty':
                throw new ApiException("There is no message or it is empty.");
            case 'Wrong source':
                throw new ApiException("Wrong source number.");
            case 'Wrong destination':
                throw new ApiException("Wrong destination number.");
            case 'There is no such LCR':
                throw new ApiException("LCR ID not found.");
            case 'User is not subscribed to sms service':
                throw new ApiException("User is not subscribed to SMS service.");
            case 'System owner does not have rate for this destination':
                throw new ApiException("System owner does not have rate for this destination.");
            case 'Bad login':
                throw new ApiException("User not found.");
            case 'Access Denied':
                throw new ApiException("Access denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'Insufficient balance':
                throw new ApiException("Insufficient balance to send SMS.");
            case 'SMS Tariff was not found':
                throw new ApiException("SMS Tariff was not found.");
            case 'SMS LCR was not found':
                throw new ApiException("SMS LCR was not found.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'User is already subscribed to SMS service':
                throw new ApiException("User is already subscribed to SMS service.");
            case 'API must have Secret Key':
                throw new ApiException("API must have Secret Key.");
            case 'You are not authorized to use this functionality':
                throw new ApiException("You are not authorized to use this functionality.");
            case 'Reseller does not have SMS Subscription':
                throw new ApiException("Reseller does not have SMS Subscription.");
            case 'API Requests are disabled':
                throw new ApiException("API Requests are disabled.");
            case 'Sms Mesagges were not found':
                throw new ApiException("SMS Messages were not found.");
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 