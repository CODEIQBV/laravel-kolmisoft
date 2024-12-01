## Conflines Update

### Usage
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_conflines_update
```php
use CODEIQBV\Kolmisoft\Api\Conflines;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $conflinesApi = new Conflines();

    $params = [
        'api_secret_key' => '987654321',
        'default_user_tariff_id' => 2,
        'default_device_location_id' => 3,
        // Add other parameters as needed
    ];

    $response = $conflinesApi->updateConflines($params);

    echo "Conflines Update Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
