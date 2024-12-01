<?php

namespace CODEIQBV\Kolmisoft\Api;

use CODEIQBV\Kolmisoft\Exceptions\ApiException;

class Tariff extends BaseApi
{
    public function getRates($params = [], $raw = false)
    {
        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = [];
        if (isset($params['tariff_id'])) {
            $hashKeys[] = 'tariff_id';
        }
        if (isset($params['user_id'])) {
            $hashKeys[] = 'user_id';
        }

        $response = $this->sendRequest('/api/tariff_rates_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Check if it's a retail or wholesale tariff
        if (isset($response->rates->destination)) {
            return $this->formatRetailTariff($response);
        }

        return $this->formatWholesaleTariff($response);
    }

    public function importRetail($xml, $raw = false)
    {
        $params = [
            'xml' => $xml,
        ];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // No hash keys needed for XML content
        $hashKeys = [];

        $response = $this->sendRequest('/api/tariff_retail_import', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleImportError($response);
        }

        return [
            'tariff_id' => isset($response->tariff_id) ? (int) $response->tariff_id : null,
            'tariff_name' => isset($response->tariff_name) ? (string) $response->tariff_name : null,
        ];
    }

    public function updateWholesale($name, $currency, $id = null, $raw = false)
    {
        $params = [
            'name' => $name,
            'currency' => $currency,
        ];

        if ($id !== null) {
            $params['id'] = $id;
        }

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['id', 'name', 'currency'];

        $response = $this->sendRequest('/api/tariff_wholesale_update', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleWholesaleError($response);
        }

        // Check if this is a new tariff creation response
        if (isset($response->tariff_id)) {
            return [
                'status' => 'created',
                'tariff_id' => (int) $response->tariff_id,
            ];
        }

        // Update response
        return [
            'status' => 'updated',
        ];
    }

    public function getTariffs($raw = false)
    {
        $params = [];

        // Add global username
        $params['u'] = config('kolmisoft.username');

        // Define hash keys for this endpoint
        $hashKeys = ['u'];

        $response = $this->sendRequest('/api/tariffs_get', $params, $raw, $hashKeys);

        if ($raw) {
            return $response;
        }

        if (isset($response->error)) {
            $this->handleError($response->error);
        }

        // Initialize empty array for tariffs
        $tariffs = [];

        // Check if tariffs exist and is not null
        if (isset($response->tariffs) && isset($response->tariffs->tariff)) {
            // Handle case where there's only one tariff (not an array)
            if (!is_array($response->tariffs->tariff)) {
                $tariffs[] = $this->formatTariffSummary($response->tariffs->tariff);
            } else {
                // Handle multiple tariffs
                foreach ($response->tariffs->tariff as $tariff) {
                    $tariffs[] = $this->formatTariffSummary($tariff);
                }
            }
        }

        return $tariffs;
    }

    private function formatRetailTariff($response)
    {
        $tariff = [
            'name' => (string) $response->tariff_name,
            'type' => (string) $response->purpose,
            'currency' => (string) $response->currency,
            'destinations' => [],
        ];

        foreach ($response->rates->destination as $destination) {
            $destinationData = [
                'name' => (string) $destination->destination_group_name,
                'rates' => [],
            ];

            // Add destinations info if available
            if (isset($destination->destinations)) {
                $destinationData['destinations'] = [];
                foreach ($destination->destinations as $dest) {
                    $destinationData['destinations'][] = [
                        'name' => (string) $dest->destination_name,
                        'prefix' => (string) $dest->prefix,
                    ];
                }
            }

            // Add rates
            foreach ($destination->rate as $rate) {
                $destinationData['rates'][] = [
                    'duration' => (int) $rate->duration,
                    'type' => (string) $rate->type,
                    'round_by' => (int) $rate->round_by,
                    'rate' => (float) $rate->tariff_rate,
                    'start_time' => (string) $rate->start_time,
                    'end_time' => (string) $rate->end_time,
                    'from' => (string) $rate->from,
                    'daytype' => (string) $rate->daytype,
                ];
            }

            $tariff['destinations'][] = $destinationData;
        }

        return $tariff;
    }

    private function formatWholesaleTariff($response)
    {
        $tariff = [
            'name' => (string) $response->tariff_name,
            'type' => (string) $response->purpose,
            'currency' => (string) $response->currency,
            'rates' => [],
        ];

        foreach ($response->rates->rate as $rate) {
            $tariff['rates'][] = [
                'direction' => (string) $rate->direction,
                'destination' => (string) $rate->destination,
                'prefix' => (string) $rate->prefix,
                'code' => (string) $rate->code,
                'rate' => (float) $rate->tariff_rate,
                'connection_fee' => (float) $rate->con_fee,
                'increment' => (int) $rate->increment,
                'min_time' => (int) $rate->min_time,
                'start_time' => (string) $rate->start_time,
                'end_time' => (string) $rate->end_time,
                'daytype' => (string) $rate->daytype,
                'effective_from' => (string) $rate->effective_from,
            ];
        }

        return $tariff;
    }

    private function formatTariffSummary($tariff)
    {
        $formatted = [
            'id' => (int) $tariff->id,
            'name' => (string) $tariff->name,
            'purpose' => (string) $tariff->purpose,
            'currency' => (string) $tariff->currency,
            'last_update_date' => !empty($tariff->last_update_date) ? (string) $tariff->last_update_date : null,
        ];

        // Add optional delta values if they exist
        if (isset($tariff->delta_value)) {
            $formatted['delta_value'] = !empty($tariff->delta_value) ? (float) $tariff->delta_value : null;
        }
        if (isset($tariff->delta_percent)) {
            $formatted['delta_percent'] = !empty($tariff->delta_percent) ? (float) $tariff->delta_percent : null;
        }

        return $formatted;
    }

    private function handleError($error)
    {
        switch ((string)$error) {
            case 'You are not authorized to use this functionality':
                throw new ApiException("You are not authorized to use this functionality.");
            case 'Access Denied':
                throw new ApiException("Access Denied.");
            case 'No Tariffs found':
                throw new ApiException("No Tariffs found.");
            case 'Incorrect hash':
                throw ApiException::incorrectHash();
            default:
                throw new ApiException("An unknown error occurred: $error");
        }
    }

    private function handleImportError($response)
    {
        if (isset($response->error)) {
            $error = (string) $response->error;
            switch ($error) {
                case 'File does not exist':
                case 'Bad XML data':
                    throw new ApiException("Invalid XML format: " . $error);

                case (preg_match('/^TARIFF NAME WITH THIS ID DO NOT MATCH !!!FOUND (.+)$/', $error, $matches) ? true : false):
                    throw new ApiException("Tariff name does not match with existing tariff: " . $matches[1]);

                case (preg_match('/^TARIFF with same name exists, ID:(\d+)!!! CHANGE NAME OR ID$/', $error, $matches) ? true : false):
                    throw new ApiException("Tariff with same name exists (ID: " . $matches[1] . "). Change name or ID.");

                case 'Tariff belongs to other user!':
                    throw new ApiException("Cannot update tariff: belongs to another user.");

                case 'No destinations!':
                    throw new ApiException("No destinations found in XML.");

                default:
                    throw new ApiException("Import error: " . $error);
            }
        }

        // Handle structured error responses
        $errorDetails = [];

        if (isset($response->bad_destinations)) {
            foreach ($response->bad_destinations as $dest) {
                $errorDetails[] = sprintf(
                    "Invalid destination: %s (%s)",
                    (string) $dest->destination_group_name,
                    (string) $dest->destination_group_type
                );
            }
        }

        if (isset($response->destination_with_bad_rates)) {
            foreach ($response->destination_with_bad_rates as $dest) {
                $errorDetails[] = sprintf(
                    "Invalid rate for destination %s (%s): Price: %s, Round: %s, Duration: %s, Time: %s-%s",
                    (string) $dest->destination_group_name,
                    (string) $dest->destination_group_type,
                    (string) $dest->rate_price,
                    (string) $dest->rate_round_by,
                    (string) $dest->rate_duration,
                    (string) $dest->rate_start_time,
                    (string) $dest->rate_end_time
                );
            }
        }

        if (isset($response->destination_with_time_collisions_in_xml)) {
            foreach ($response->destination_with_time_collisions_in_xml->collision_in_time_range as $collision) {
                $errorDetails[] = "Time collision in XML: " . (string) $collision;
            }
        }

        if (isset($response->destination_with_time_collisions_in_db)) {
            foreach ($response->destination_with_time_collisions_in_db->collision_in_time_range as $collision) {
                $errorDetails[] = "Time collision with existing rates: " . (string) $collision;
            }
        }

        if (!empty($errorDetails)) {
            throw new ApiException("Import failed:\n" . implode("\n", $errorDetails));
        }

        throw new ApiException("Unknown import error occurred");
    }

    private function handleWholesaleError($response)
    {
        // Handle error message in response format
        if (isset($response->error->message)) {
            throw new ApiException("Wholesale tariff error: " . (string) $response->error->message);
        }

        // Handle direct error string
        if (isset($response->error)) {
            $error = (string) $response->error;
            switch ($error) {
                case 'Tariff not found':
                    throw new ApiException("Tariff not found.");
                case 'Bad login':
                    throw new ApiException("Bad login credentials.");
                case 'Incorrect hash':
                    throw ApiException::incorrectHash();
                default:
                    throw new ApiException("An unknown error occurred: " . $error);
            }
        }

        throw new ApiException("Unknown wholesale tariff error occurred");
    }
} 