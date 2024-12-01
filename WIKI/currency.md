## Currency

### Usage
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Exchange_rate_update.
```php
use CODEIQBV\Kolmisoft\Api\ExchangeRate;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $exchangeRateApi = new ExchangeRate();

    $currency = 'AMD';
    $rate = 45.5;

    $response = $exchangeRateApi->updateExchangeRate($currency, $rate);

    echo "Exchange Rate Update Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
