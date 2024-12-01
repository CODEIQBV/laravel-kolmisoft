## Provider

### Get providers
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_providers_get.
```php
use CODEIQBV\Kolmisoft\Api\Provider;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $providerApi = new Provider();

    // Get all providers
    $providers = $providerApi->getProviders();

    // Or get a specific provider
    $provider = $providerApi->getProviders(2);

    foreach ($providers as $provider) {
        echo "ID: " . $provider['id'] . "\n";
        echo "Name: " . $provider['name'] . "\n";
        echo "Tech: " . $provider['tech'] . "\n";
        echo "Server IP: " . $provider['server_ip'] . "\n";
        echo "Port: " . $provider['port'] . "\n";
        echo "Active: " . ($provider['active'] ? 'Yes' : 'No') . "\n";
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Provider create
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_provider_create.
```php
use CODEIQBV\Kolmisoft\Api\Provider;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $providerApi = new Provider();

    // Basic provider creation
    $result = $providerApi->create(
        name: 'ApiProvider',
        tech: 'SIP',
        tariffId: 1,
        params: [
            'server_ids' => '1,3,4',
            'active' => 1,
        ]
    );

    // Advanced provider creation
    $result = $providerApi->create(
        name: 'AdvancedProvider',
        tech: 'SIP',
        tariffId: 1,
        params: [
            'server_ids' => '1',
            'active' => 1,
            'dtmfmode' => 'rfc2833',
            'location_id' => 1,
            'timeout' => 60,
            'call_limit' => 100,
            'balance_limit' => '1000.00',
            'network_type' => 'ip',
            'server_ip' => '192.168.1.1',
            'port' => 5060,
        ]
    );

    echo "Provider Created Successfully!\n";
    echo "Provider ID: " . $result['provider_id'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Update provider
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_provider_update.
```php
use CODEIQBV\Kolmisoft\Api\Provider;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $providerApi = new Provider();

    // Basic update
    $result = $providerApi->update(7, [
        'name' => 'Test3221',
    ]);

    // Advanced update
    $result = $providerApi->update(7, [
        'name' => 'Updated Provider',
        'active' => 1,
        'dtmfmode' => 'rfc2833',
        'timeout' => 60,
        'call_limit' => 100,
        'balance_limit' => '1000.00',
        'server_ids' => '1,3,4',
        'network_type' => 'ip',
        'server_ip' => '192.168.1.1',
        'port' => 5060,
    ]);

    echo "Provider Updated Successfully: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete provider
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_provider_delete.
```php
use CODEIQBV\Kolmisoft\Api\Provider;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $providerApi = new Provider();

    $result = $providerApi->delete(7);

    echo "Provider Delete Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## Provider Rules

### Get provider rules
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_provider_rules_get.
```php
use CODEIQBV\Kolmisoft\Api\ProviderRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $providerRuleApi = new ProviderRule();

    $rules = $providerRuleApi->getRules(5);

    foreach ($rules as $rule) {
        echo "Rule ID: " . $rule['id'] . "\n";
        echo "Name: " . $rule['name'] . "\n";
        echo "Enabled: " . ($rule['enabled'] ? 'Yes' : 'No') . "\n";
        echo "Cut: " . $rule['cut'] . "\n";
        echo "Add: " . $rule['add'] . "\n";
        echo "Type: " . $rule['pr_type'] . "\n";
        if ($rule['tariff_id']) {
            echo "Tariff ID: " . $rule['tariff_id'] . "\n";
        }
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete provider rules
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_provider_rule_delete.
```php
use CODEIQBV\Kolmisoft\Api\ProviderRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $providerRuleApi = new ProviderRule();

    $result = $providerRuleApi->delete(1);

    echo "Delete Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Create provider rules
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_provider_rule_create.
```php
use CODEIQBV\Kolmisoft\Api\ProviderRule;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $providerRuleApi = new ProviderRule();

    // Basic rule creation
    $result = $providerRuleApi->create(
        providerId: 5,
        name: 'myrule',
        cut: '353',
        add: '0'
    );

    // Advanced rule creation
    $result = $providerRuleApi->create(
        providerId: 5,
        name: 'Advanced Rule',
        cut: '353',
        add: '0',
        params: [
            'minlen' => 3,
            'maxlen' => 15,
            'pr_type' => ProviderRule::TYPE_DST,
            'tariff_id' => 1,
            'set_pai' => 1,
            'suffix' => '123',
            'change_callerid_name' => 1,
        ]
    );

    echo "Rule Creation Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
