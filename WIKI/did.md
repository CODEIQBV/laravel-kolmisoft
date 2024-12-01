## DIDs

### Get DIDs
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_dids_get
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $params = [
        'search_status' => 'free', // Optional: filter by status
        // Add other optional parameters as needed
    ];

    $response = $didApi->getDIDs($params);

    // Output the retrieved DIDs
    foreach ($response['dids'] as $did) {
        echo "DID: " . $did['did'] . "\n";
        echo "Provider: " . $did['provider'] . "\n";
        echo "Status: " . $did['status'] . "\n";
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Create DID
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_create.
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $providerId = 1;
    $didNumber = '123456789';

    $response = $didApi->createDID($providerId, $didNumber);

    echo "DID Creation Status: " . $response['success'] . "\n";
    echo "DID ID: " . $response['did_id'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID assign to device
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_device_assign
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $deviceId = 1;
    $didNumber = '123456789';

    $response = $didApi->assignDeviceToDID($deviceId, $didNumber);

    echo "Device Assignment Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Trunk Device Assign
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_trunk_device_assign
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $deviceId = 1;
    $didNumber = '123456789';

    $response = $didApi->assignTrunkDeviceToDID($deviceId, $didNumber);

    echo "Trunk Device Assignment Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Device Unassign
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_device_unassign
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didNumber = '123456789';

    $response = $didApi->unassignDeviceFromDID($didNumber);

    echo "Device Unassignment Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Details Update
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_details_update
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didId = 6511;
    $params = [
        'call_limit' => 999,
        'active_from' => new DateTime('2023-01-01'),
        'active_till' => new DateTime('2023-12-31'),
        // Add other optional parameters as needed
    ];

    $response = $didApi->updateDIDDetails($didId, $params);

    echo "DID Update Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Subscription Stop
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_subscription_stop
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didId = 6511;

    $response = $didApi->stopDIDSubscription($didId);

    echo "DID Subscription Stop Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Terminate
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_terminate
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didsId = 6511;

    $response = $didApi->terminateDID($didsId);

    echo "DID Termination Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Make Free
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_make_free
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didsId = 6511;

    $response = $didApi->makeDIDFree($didsId);

    echo "DID Make Free Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Rates Update
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_rates_update
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didNumber = '370123456789';
    $params = [
        's_rate_type' => 'provider',
        'rate' => 0.5,
        // Add other optional parameters as needed
    ];

    $response = $didApi->updateDIDRates($didNumber, $params);

    echo "Updated Rates: " . $response['updated_rates'] . "\n";
    print_r($response['rates']);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Rates Get
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_rates_get
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didNumber = '370123456789';

    $response = $didApi->getDIDRates($didNumber);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Close
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_close
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didsId = 1;

    $response = $didApi->closeDID($didsId);

    echo "DID Close Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Delete
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_delete
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didsId = 12;

    $response = $didApi->deleteDID($didsId);

    echo "DID Delete Status: " . $response['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Rates Details Get
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_rates_details_get
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didId = 3;

    $response = $didApi->getDIDRatesDetails($didId);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### DID Rates Details Update
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_did_rates_details_update
```php
use CODEIQBV\Kolmisoft\Api\DID;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $didApi = new DID();

    $didId = 3;
    $params = [
        'provider_tariff_id' => 3,
        'allow_call_reject_provider' => 1,
        // Add other optional parameters as needed
    ];

    $response = $didApi->updateDIDRatesDetails($didId, $params);

    echo "DID Rates Details Update Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
