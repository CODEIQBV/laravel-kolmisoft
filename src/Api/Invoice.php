<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;
use DateTime;

class Invoice extends BaseApi
{
    /**
     * Get list of invoices for a time period
     *
     * @param DateTime|int $from Start date (DateTime or Unix timestamp)
     * @param DateTime|int $till End date (DateTime or Unix timestamp)
     * @param string|null $lang Language code for invoice details
     * @param bool $raw Whether to return raw response
     * @return array List of formatted invoices
     * @throws ApiException
     */
    public function getInvoices($from, $till, $lang = null, $raw = false)
    {
        // Convert DateTime objects to timestamps if needed
        if ($from instanceof DateTime) {
            $from = $from->getTimestamp();
        }
        if ($till instanceof DateTime) {
            $till = $till->getTimestamp();
        }

        // Validate timestamps
        if (!is_numeric($from) || $from < 0) {
            throw new ApiException("Invalid from timestamp");
        }
        if (!is_numeric($till) || $till < 0) {
            throw new ApiException("Invalid till timestamp");
        }
        if ($till < $from) {
            throw new ApiException("End date must be after start date");
        }

        $params = [
            'from' => $from,
            'till' => $till,
        ];

        // Add language if provided
        if ($lang !== null) {
            $params['lang'] = $lang;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: username must be included in hash
        $hashKeys = ['u'];

        $response = $this->sendRequest('/api/invoices_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->Error)) {
            $this->handleError($response->Error);
        }

        return $this->formatInvoicesResponse($response);
    }

    /**
     * Format the invoices response
     *
     * @param object $response The API response
     * @return array Formatted invoices data
     */
    private function formatInvoicesResponse($response)
    {
        $result = [
            'from' => (string) $response->Invoices['from'],
            'till' => (string) $response->Invoices['till'],
            'invoices' => [],
        ];

        if (!isset($response->Invoices->Invoice)) {
            return $result;
        }

        // Handle case where there's only one invoice (not an array)
        if (!is_array($response->Invoices->Invoice)) {
            $result['invoices'][] = $this->formatInvoice($response->Invoices->Invoice);
        } else {
            foreach ($response->Invoices->Invoice as $invoice) {
                $result['invoices'][] = $this->formatInvoice($invoice);
            }
        }

        return $result;
    }

    /**
     * Format a single invoice
     *
     * @param object $invoice The invoice object from response
     * @return array Formatted invoice data
     */
    private function formatInvoice($invoice)
    {
        $formatted = [
            'id' => (int) $invoice->id,
            'user_id' => (int) $invoice['user_id'],
            'agreement_number' => (string) $invoice['agreementnumber'],
            'client_id' => (string) $invoice['clientid'],
            'number' => (string) $invoice['number'],
            'paid' => (bool) $invoice->paid,
            'total_time' => (string) $invoice->Total_time,
            'products' => [],
        ];

        // Format products if they exist
        if (isset($invoice->Product)) {
            // Handle case where there's only one product
            if (!is_array($invoice->Product)) {
                $formatted['products'][] = $this->formatProduct($invoice->Product);
            } else {
                foreach ($invoice->Product as $product) {
                    $formatted['products'][] = $this->formatProduct($product);
                }
            }
        }

        return $formatted;
    }

    /**
     * Format a single product
     *
     * @param object $product The product object from response
     * @return array Formatted product data
     */
    private function formatProduct($product)
    {
        return [
            'name' => (string) $product->Name,
            'quantity' => (float) $product->Quantity,
            'price' => (float) $product->Price,
            'discount' => (float) $product->Discount,
            'sum' => (float) $product->Sum,
            'date_added' => (string) $product->Date_added,
            'issue_date' => (string) $product->Issue_date,
            'service_id' => (int) $product->Service_id,
            'prefix' => (string) $product->Prefix,
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
        switch ((string) $error) {
            case 'user not found':
                throw new ApiException("User not found.");
            case 'no invoices found':
                throw new ApiException("No invoices found for the specified period.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }

    /**
     * Update an invoice
     *
     * @param int $invoiceId Invoice ID to update
     * @param array $params Update parameters
     * @param bool $raw Whether to return raw response
     * @return array{success: string}|object
     * @throws ApiException
     */
    public function update($invoiceId, array $params = [], $raw = false)
    {
        // Validate invoice ID
        if (!is_numeric($invoiceId) || $invoiceId <= 0) {
            throw new ApiException("Invalid invoice id");
        }

        $updateParams = [
            'id' => $invoiceId,
        ];

        // Validate and add optional parameters
        $this->validateAndAddUpdateParams($updateParams, $params);

        // Add global username
        $updateParams['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        // Note: Only invoice ID is included in hash
        $hashKeys = ['id'];

        $response = $this->sendRequest('/api/invoice_update', $updateParams, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->status->error)) {
            $this->handleError($response->status->error);
        }

        return [
            'success' => (string) $response->status->success,
        ];
    }

    /**
     * Validate and add update parameters
     *
     * @param array &$params Reference to parameters array
     * @param array $updates Parameters to validate and add
     */
    private function validateAndAddUpdateParams(&$params, array $updates)
    {
        // List of valid update parameters
        $validParams = [
            'address',
            'city',
            'postcode',
            'state',
            'country_id',
            'phone',
            'tax_reg_number',
            'comment',
            'name',
        ];

        // Add valid parameters if provided
        foreach ($validParams as $param) {
            if (isset($updates[$param])) {
                $params[$param] = $updates[$param];
            }
        }

        // Handle boolean parameters
        $booleanParams = [
            'invoice_sent_manually',
            'pay',
        ];

        foreach ($booleanParams as $param) {
            if (isset($updates[$param])) {
                // Only set to '1' if true, otherwise don't include
                if ($updates[$param]) {
                    $params[$param] = '1';
                }
            }
        }

        // Validate country_id if provided
        if (isset($params['country_id']) && (!is_numeric($params['country_id']) || $params['country_id'] <= 0)) {
            throw new ApiException("Invalid country_id");
        }
    }

    /**
     * Handle error responses
     *
     * @param string $error The error message
     * @throws ApiException
     */
    private function handleError($error)
    {
        switch ((string) $error) {
            case 'user not found':
                throw new ApiException("User not found.");
            case 'no invoices found':
                throw new ApiException("No invoices found for the specified period.");
            case 'Invoice was not found':
                throw new ApiException("Invoice was not found.");
            case 'Access denied':
                throw new ApiException("Access denied.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }
} 