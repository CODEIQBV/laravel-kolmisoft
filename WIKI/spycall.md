## Spy call

### Usage
For more details go to https://wiki.kolmisoft.com/index.php/MOR_API_spy_call.

```php
use CODEIQBV\Kolmisoft\Api\Spy;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $spyApi = new Spy();

    $activeCallId = 1;
    $result = $spyApi->initiateCall($activeCallId);

    echo "Spy Call Status: " . $result['status'] . "\n";
    echo "Spy Device: " . $result['spy_device'] . "\n";
    echo "Channel: " . $result['channel'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
