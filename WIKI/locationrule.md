## Location rules

### Create location rule
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_location_rule_create.
```php
use CODEIQBV\Kolmisoft\Api\LocationRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $locationRuleApi = new LocationRule();

    // Create a basic destination rule
    $result = $locationRuleApi->create(
        locationId: 4,
        name: 'Basic Rule',
        params: [
            'cut' => '86',
            'add' => '3706',
            'minlen' => 1,
            'maxlen' => 25,
        ]
    );

    // Create a combined rule
    $result = $locationRuleApi->create(
        locationId: 4,
        name: 'Combined Rule',
        params: [
            'lr_type' => LocationRule::TYPE_COMBINED,
            'cut' => '86',
            'add' => '3706',
            'src_cut' => '1',
            'src_add' => '371',
            'minlen' => 1,
            'maxlen' => 25,
            'src_minlen' => 1,
            'src_maxlen' => 15,
            'tariff_id' => 1,
            'location_group_id' => 2,
            'change_callerid_name' => true,
        ]
    );

    echo "Rule Created Successfully!\n";
    echo "Rule ID: " . $result['rule_id'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Update location role
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_location_rule_update.
```php
use CODEIQBV\Kolmisoft\Api\LocationRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $locationRuleApi = new LocationRule();

    // Basic update
    $result = $locationRuleApi->update(19, [
        'cut' => '8683',
        'add' => '370683',
        'minlen' => 1,
        'maxlen' => 97,
    ]);

    // Complex update
    $result = $locationRuleApi->update(19, [
        'enabled' => 1,
        'name' => 'Updated Rule',
        'cut' => '8683',
        'add' => '370683',
        'src_cut' => '1',
        'src_add' => '371',
        'minlen' => 1,
        'maxlen' => 97,
        'src_minlen' => 1,
        'src_maxlen' => 15,
        'tariff_id' => 1,
        'lcr_id' => -1, // Unassign LCR
        'change_callerid_name' => true,
        'location_group_id' => 2,
    ]);

    echo "Rule Updated Successfully!\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Get location rules
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_location_rules_get.
```php
use CODEIQBV\Kolmisoft\Api\LocationRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $locationRuleApi = new LocationRule();

    // Get rules for specific location
    $result = $locationRuleApi->getRules(1);

    // Get all rules with pagination
    $result = $locationRuleApi->getRules('all', [
        'from' => 0,
        'max_results' => 50,
    ]);

    foreach ($result['location']['location_rule'] as $rule) {
        echo "Rule ID: " . $rule['id'] . "\n";
        echo "Name: " . $rule['name'] . "\n";
        echo "Type: " . $rule['lr_type'] . "\n";
        echo "Enabled: " . ($rule['enabled'] ? 'Yes' : 'No') . "\n";
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Get location rule
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_location_rule_get.
```php
use CODEIQBV\Kolmisoft\Api\LocationRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $locationRuleApi = new LocationRule();

    $rule = $locationRuleApi->getRule(2);

    echo "Rule ID: " . $rule['id'] . "\n";
    echo "Name: " . $rule['name'] . "\n";
    echo "Type: " . $rule['lr_type'] . "\n";
    echo "Enabled: " . ($rule['enabled'] ? 'Yes' : 'No') . "\n";
    echo "Cut: " . $rule['cut'] . "\n";
    echo "Add: " . $rule['add'] . "\n";
    
    if ($rule['lcr_id']) {
        echo "LCR ID: " . $rule['lcr_id'] . "\n";
    }
    if ($rule['tariff_id']) {
        echo "Tariff ID: " . $rule['tariff_id'] . "\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Copy location rule
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_location_rule_copy.
```php
use CODEIQBV\Kolmisoft\Api\LocationRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $locationRuleApi = new LocationRule();

    // Copy rule 8 to location 2
    $result = $locationRuleApi->copyRule(
        ruleId: 8,
        locationId: 2
    );

    echo "Rule Copy Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete location rule
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_location_rule_delete.

```php
use CODEIQBV\Kolmisoft\Api\LocationRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $locationRuleApi = new LocationRule();

    $result = $locationRuleApi->deleteRule(19);

    echo "Delete Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
