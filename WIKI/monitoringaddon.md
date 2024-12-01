## Monitoring addon

### Activate monitoring addon
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_ma_activate_api.

```php
use CODEIQBV\Kolmisoft\Api\MonitoringAddon;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $monitoringApi = new MonitoringAddon();

    $result = $monitoringApi->activate(
        monitoringId: 123,
        userIds: [2, 3],  // or "2,3"
        block: true,
        email: true,
        monitoringType: MonitoringAddon::TYPE_INCREASES_MORE_THAN
    );

    echo "Monitoring Status: " . $result['monitoring_found'] . "\n";
    foreach ($result['users'] as $user) {
        echo "User {$user['id']}: {$user['status']}\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
