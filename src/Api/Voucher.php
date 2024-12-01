<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Voucher extends BaseApi
{
    public function useVoucher($voucherNumber, $userId = null, $raw = false)
    {
        $params = [
            'voucher_number' => $voucherNumber,
        ];

        if ($userId) {
            $params['user_id'] = $userId;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Specify the keys to include in the hash
        $hashKeys = ['voucher_number'];

        $response = $this->sendRequest('/api/voucher_use', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        return [
            'status' => (string) $response->status->status,
            'voucher_number' => (string) $response->status->voucher_number,
            'voucher_id' => (int) $response->status->voucher_id,
            'credit_with_tax' => (float) $response->status->credit_with_tax,
            'credit_without_tax' => (float) $response->status->credit_without_tax,
            'currency' => (string) $response->status->currency,
            'credit_in_default_currency' => (float) $response->status->credit_in_default_currency,
            'user_id' => (int) $response->status->user_id,
            'balance_after_voucher_use' => (float) $response->status->balance_after_voucher_use,
            'payment_id' => (int) $response->status->payment_id,
        ];
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'Voucher was not found':
                throw new ApiException("Voucher was not found.");
            case 'Vouchers Disabled':
                throw new ApiException("Vouchers are disabled.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'User was not found':
                throw new ApiException("User was not found.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 