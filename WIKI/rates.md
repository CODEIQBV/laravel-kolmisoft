## Rates

### Rate get
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_rate_get.
```php
use CODEIQBV\Kolmisoft\Api\Rate;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $rateApi = new Rate();

    // Get rate for exact prefix
    $result = $rateApi->get('admin', '370');

    echo "Rate: " . $result['rate'] . "\n";
    echo "Destination: " . $result['destination'] . "\n";
    echo "Prefix: " . $result['prefix'] . "\n";

    // Get rate for full number
    $result = $rateApi->get('admin', '37061234567', true);

    echo "Rate: " . $result['rate'] . "\n";
    echo "Destination: " . $result['destination'] . "\n";
    echo "Prefix: " . $result['prefix'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Tariff rates get
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_tariff_rates_get.
```php
use CODEIQBV\Kolmisoft\Api\Tariff;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $tariffApi = new Tariff();

    // Get tariff rates by tariff ID
    $result = $tariffApi->getRates([
        'tariff_id' => 100,
        'additional_retail_info' => 1,
    ]);

    // Or get tariff rates by user ID
    $result = $tariffApi->getRates([
        'user_id' => 5,
    ]);

    // Print tariff information
    echo "Tariff Name: " . $result['name'] . "\n";
    echo "Type: " . $result['type'] . "\n";
    echo "Currency: " . $result['currency'] . "\n";

    // Print rates based on tariff type
    if (isset($result['destinations'])) {
        // Retail tariff
        foreach ($result['destinations'] as $destination) {
            echo "\nDestination: " . $destination['name'] . "\n";
            foreach ($destination['rates'] as $rate) {
                echo "Rate: " . $rate['rate'] . "\n";
            }
        }
    } else {
        // Wholesale tariff
        foreach ($result['rates'] as $rate) {
            echo "\nPrefix: " . $rate['prefix'] . "\n";
            echo "Rate: " . $rate['rate'] . "\n";
        }
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Tariff retail import
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_tariff_retail_import.
```php
use CODEIQBV\Kolmisoft\Api\Tariff;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $tariffApi = new Tariff();

    $xml = <<<XML
<tariff>
    <name>Test Tariff</name>
    <id>2</id>
    <destinations>
        <destination>
            <direction/>
            <destination_group_name>Afghanistan</destination_group_name>
            <destination_group_type>FIX</destination_group_type>
            <rates>
                <rate_price>0.02</rate_price>
                <rate_round_by>1</rate_round_by>
                <rate_type>minute</rate_type>
                <rate_start_time>00:00:00</rate_start_time>
                <rate_end_time>23:59:59</rate_end_time>
                <rate_duration>300</rate_duration>
                <day_type>WD</day_type>
            </rates>
        </destination>
    </destinations>
</tariff>
XML;

    $result = $tariffApi->importRetail($xml);

    echo "Import successful!\n";
    echo "Tariff ID: " . $result['tariff_id'] . "\n";
    echo "Tariff Name: " . $result['tariff_name'] . "\n";
} catch (ApiException $e) {
    echo "Import Error: " . $e->getMessage() . "\n";
}
```

### Tariff wholesale import
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_tariff_wholesale_update.
```php
use CODEIQBV\Kolmisoft\Api\Tariff;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $tariffApi = new Tariff();

    // Create new wholesale tariff
    $result = $tariffApi->updateWholesale(
        name: 'New Wholesale Tariff',
        currency: 'EUR'
    );

    if ($result['status'] === 'created') {
        echo "New tariff created with ID: " . $result['tariff_id'] . "\n";
    }

    // Update existing wholesale tariff
    $result = $tariffApi->updateWholesale(
        name: 'Updated Wholesale Tariff',
        currency: 'USD',
        id: 8
    );

    if ($result['status'] === 'updated') {
        echo "Tariff updated successfully\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Tariffs get
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_tariffs_get.
```php
use CODEIQBV\Kolmisoft\Api\Tariff;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $tariffApi = new Tariff();

    $tariffs = $tariffApi->getTariffs();

    foreach ($tariffs as $tariff) {
        echo "ID: " . $tariff['id'] . "\n";
        echo "Name: " . $tariff['name'] . "\n";
        echo "Purpose: " . $tariff['purpose'] . "\n";
        echo "Currency: " . $tariff['currency'] . "\n";
        
        if ($tariff['last_update_date']) {
            echo "Last Update: " . $tariff['last_update_date'] . "\n";
        }
        
        if (isset($tariff['delta_value'])) {
            echo "Delta Value: " . $tariff['delta_value'] . "\n";
        }
        if (isset($tariff['delta_percent'])) {
            echo "Delta Percent: " . $tariff['delta_percent'] . "%\n";
        }
        
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
