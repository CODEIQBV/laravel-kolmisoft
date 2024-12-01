## PBX Pool

### Create PBX pool
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_pbx_pool_create.
```php
use CODEIQBV\Kolmisoft\Api\PbxPool;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $pbxPoolApi = new PbxPool();

    // Basic creation
    $result = $pbxPoolApi->create('pbx_pool_test');

    // Creation with comment
    $result = $pbxPoolApi->create(
        name: 'pbx_pool_test',
        comment: 'Test PBX Pool'
    );

    echo "Creation Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
