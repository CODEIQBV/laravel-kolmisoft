## LCR

### Get LCRs
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_lcrs_get.
```php
use CODEIQBV\Kolmisoft\Api\Lcr;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $lcrApi = new Lcr();

    // Get all LCRs
    $lcrs = $lcrApi->getLcrs();

    // Get LCRs with name filter
    $lcrs = $lcrApi->getLcrs('BLANK');

    foreach ($lcrs as $lcr) {
        echo "ID: " . $lcr['id'] . "\n";
        echo "Name: " . $lcr['name'] . "\n";
        echo "Order: " . $lcr['order'] . "\n";
        echo "First Provider Percent Limit: " . $lcr['first_provider_percent_limit'] . "\n";
        if ($lcr['failover_provider_id']) {
            echo "Failover Provider ID: " . $lcr['failover_provider_id'] . "\n";
        }
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Create LCR
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_lcr_create.
```php
use CODEIQBV\Kolmisoft\Api\Lcr;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $lcrApi = new Lcr();

    // Create LCR with just a name
    $result = $lcrApi->create('test');

    // Create LCR with name and order
    $result = $lcrApi->create(
        name: 'test',
        order: Lcr::ORDER_PRICE
    );

    echo "LCR Creation Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Update LCR
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_lcr_update.
```php
use CODEIQBV\Kolmisoft\Api\Lcr;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $lcrApi = new Lcr();

    // Basic update
    $result = $lcrApi->update(2, [
        'name' => 'test2',
    ]);

    // Advanced update
    $result = $lcrApi->update(2, [
        'name' => 'test2',
        'order' => Lcr::ORDER_PRICE,
        'allow_loss_calls' => 1,
        'no_failover' => 0,
        'minimal_rate_margin_percent' => 5.5,
        'first_provider_percent_limit' => 80,
        'quality_routing_id' => 1,
    ]);

    echo "LCR Update Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete LCR
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_lcr_delete.
```php
use CODEIQBV\Kolmisoft\Api\Lcr;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $lcrApi = new Lcr();

    $result = $lcrApi->delete(2);

    echo "LCR Delete Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Add LCR provider
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_lcr_add_provider.
```php
use CODEIQBV\Kolmisoft\Api\Lcr;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $lcrApi = new Lcr();

    // Add provider normally
    $result = $lcrApi->addProvider(3, 2);

    // Add provider as failover
    $result = $lcrApi->addProvider(
        lcrId: 3,
        providerId: 2,
        failover: true
    );

    echo "Provider Add Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete LCR provider
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_lcr_delete_provider.
```php
use CODEIQBV\Kolmisoft\Api\Lcr;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $lcrApi = new Lcr();

    // Delete provider normally
    $result = $lcrApi->deleteProvider(3, 2);

    // Delete provider as failover
    $result = $lcrApi->deleteProvider(
        lcrId: 3,
        providerId: 2,
        failover: true
    );

    echo "Provider Delete Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
