<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;
use DateTime;

class Payment extends BaseApi
{
    // Payment type constants
    const TYPE_CARD = 'Card';
    const TYPE_PAYPAL = 'paypal';
    const TYPE_WEBMONEY = 'webmoney';
    const TYPE_OUROBOROS = 'ouroboros';
    const TYPE_MANUAL = 'manual';
    const TYPE_GATEWAY_PAYPAL = 'gateways_paypal';
    const TYPE_GATEWAY_AUTHORIZE_NET = 'gateways_authorize_net';
    const TYPE_INTEGRATION_MONEYBOOKER = 'integrations_moneybooker';
    const TYPE_INTEGRATION_TWO_CHECKOUT = 'integrations_two_checkout';

    public function create($userId, $currency, $amount, $params = [], $raw = false)
    {
        // Validate required parameters
        if (!is_numeric($userId) || $userId <= 0) {
            throw new ApiException("Invalid user_id");
        }

        if (empty($currency)) {
            throw new ApiException("No currency");
        }

        if (!is_numeric($amount) || $amount <= 0) {
            throw new ApiException("Invalid amount");
        }

        $paymentParams = [
            'user_id' => $userId,
            'p_currency' => $currency,
            'amount' => $amount,
        ];

        // Add optional parameters
        $optionalParams = [
            'paymenttype',
            'tax_in_amount',
            'transaction',
            'payer_email',
            'comments_for_user',
        ];

        foreach ($optionalParams as $param) {
            if (isset($params[$param])) {
                $paymentParams[$param] = $params[$param];
            }
        }

        // Validate tax_in_amount if provided
        if (isset($paymentParams['tax_in_amount']) && !in_array($paymentParams['tax_in_amount'], ['0', '1'])) {
            throw new ApiException("tax_in_amount must be 0 or 1");
        }

        // Add global username
        $paymentParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['user_id', 'p_currency', 'amount'];

        $response = $this->sendRequest('/api/payment_create', $paymentParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        return [
            'status' => (string) $response->response->status,
            'confirmed' => (string) $response->response->confirmed === 'Yes',
            'payment' => [
                'id' => (int) $response->response->payment->payment_id,
                'currency' => (string) $response->response->payment['currency'],
                'tax' => (float) $response->response->payment->tax,
                'amount' => (float) $response->response->payment->amount,
                'gross' => (float) $response->response->payment->gross,
            ],
        ];
    }

    public function getPayments($params = [], $raw = false)
    {
        // Convert DateTime objects to timestamps if provided
        if (isset($params['s_from']) && $params['s_from'] instanceof DateTime) {
            $params['s_from'] = $params['s_from']->getTimestamp();
        }
        if (isset($params['s_till']) && $params['s_till'] instanceof DateTime) {
            $params['s_till'] = $params['s_till']->getTimestamp();
        }

        // Validate completed status if provided
        if (isset($params['s_completed']) && !in_array($params['s_completed'], ['0', '1'])) {
            throw new ApiException("s_completed must be 0 or 1");
        }

        // Validate payment type if provided
        if (isset($params['s_paymenttype'])) {
            $validTypes = [
                self::TYPE_CARD,
                self::TYPE_PAYPAL,
                self::TYPE_WEBMONEY,
                self::TYPE_OUROBOROS,
                self::TYPE_MANUAL,
                self::TYPE_GATEWAY_PAYPAL,
                self::TYPE_GATEWAY_AUTHORIZE_NET,
                self::TYPE_INTEGRATION_MONEYBOOKER,
                self::TYPE_INTEGRATION_TWO_CHECKOUT,
            ];
            if (!in_array($params['s_paymenttype'], $validTypes)) {
                throw new ApiException("Invalid payment type");
            }
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // No parameters included in hash for this endpoint
        $hashKeys = [];

        $response = $this->sendRequest('/api/payments_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response);
        }

        // Initialize empty array for payments
        $payments = [];

        // Check if payments exist and is not null
        if (isset($response->payments) && isset($response->payments->payment)) {
            // Handle case where there's only one payment (not an array)
            if (!is_array($response->payments->payment)) {
                $payments[] = $this->formatPayment($response->payments->payment);
            } else {
                // Handle multiple payments
                foreach ($response->payments->payment as $payment) {
                    $payments[] = $this->formatPayment($payment);
                }
            }
        }

        return $payments;
    }

    private function formatPayment($payment)
    {
        return [
            'user' => (string) $payment->user,
            'payer' => (string) $payment->payer,
            'transaction_id' => (string) $payment->transaction_id,
            'date' => (string) $payment->date,
            'confirm_date' => (string) $payment->confirm_date,
            'type' => (string) $payment->type,
            'amount' => (float) $payment->amount,
            'fee' => (float) $payment->fee,
            'amount_with_tax' => (float) $payment->amount_with_tax,
            'currency' => (string) $payment->currency,
            'completed' => (bool) $payment->completed,
            'confirmed_by_admin' => (bool) $payment->confirmed_by_admin,
            'comments_for_user' => (string) $payment->comments_for_user,
            'user_balance_before_payment' => (float) $payment->user_balance_before_payment,
            'user_balance_after_payment' => (float) $payment->user_balance_after_payment,
        ];
    }

    private function handleError($response)
    {
        $error = (string) $response->error;
        $message = isset($response->message) ? (string) $response->message : null;

        switch ($error) {
            case 'Bad login':
                throw new ApiException("Bad login credentials.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            case 'No currency':
                throw new ApiException("Currency not found in system.");
            case 'Access Denied':
                throw new ApiException("Access Denied - user not found or not authorized.");
            case 'Payment was not saved':
                $errorMessage = $message 
                    ? "Payment was not saved: $message"
                    : "Payment was not saved.";
                throw new ApiException($errorMessage);
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 