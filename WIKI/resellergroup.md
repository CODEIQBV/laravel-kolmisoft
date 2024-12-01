## Reseller groups

### Reseller group create
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_reseller_group_create.
```php
use CODEIQBV\Kolmisoft\Api\ResellerGroup;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $resellerGroupApi = new ResellerGroup();

    $params = [
        'description' => 'for test purposes',
        'call_shop' => true,
        'calling_cards' => false,
        'sms_addon' => true,
        'payment_gateways' => true,
        'autodialer' => false,
        'pbx_functions' => true,
    ];

    $result = $resellerGroupApi->create('Another Reseller Group', $params);

    echo "Reseller Group Creation Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Reseller group get
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_reseller_groups_get
```php
use CODEIQBV\Kolmisoft\Api\ResellerGroup;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $resellerGroupApi = new ResellerGroup();

    $groups = $resellerGroupApi->getGroups();

    foreach ($groups as $group) {
        echo "Group ID: " . $group['id'] . "\n";
        echo "Name: " . $group['name'] . "\n";
        echo "Description: " . $group['description'] . "\n";
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
