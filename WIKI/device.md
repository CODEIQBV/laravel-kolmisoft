## Devices

### Create device
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_create
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $userId = 2;
    $params = [
        'description' => 'description1',
        'pin' => 3,
        'type' => 'SIP',
        // Add other optional parameters as needed
    ];

    $response = $deviceApi->createDevice($userId, $params);

    echo "Device Creation Status: " . $response['status'] . "\n";
    echo "Device ID: " . $response['id'] . "\n";
    echo "Device Username: " . $response['username'] . "\n";
    echo "Device Password: " . $response['password'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Update device
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_update
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 2;
    $params = [
        'new_location_id' => 7,
        'authentication' => 1,
        'host' => '192.168.5.20',
        'port' => 5060,
        // Add other optional parameters as needed
    ];

    $response = $deviceApi->updateDevice($deviceId, $params);

    echo "Device Update Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete device
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_delete
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 123;

    $response = $deviceApi->deleteDevice($deviceId);

    echo "Device Delete Status: " . $response['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Get devices
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_devices_get
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $userId = 100;

    $response = $deviceApi->getDevices($userId);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Get device details
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_details_get
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 5;

    $response = $deviceApi->getDeviceDetails($deviceId);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Device callflow get
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_callflow_get
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 100;

    $response = $deviceApi->getDeviceCallFlow($deviceId);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Device callflow update
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_callflow_update
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 4;
    $state = 'before_call';
    $callflowAction = 'forward';
    $params = [
        'external_number' => '37062699289',
        'custom' => 'caller',
    ];

    $response = $deviceApi->updateDeviceCallFlow($deviceId, $state, $callflowAction, $params);

    echo "Device Call Flow Update Status: " . $response['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Device CLIs get
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_clis_get
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 6;
    $userId = 3;

    $response = $deviceApi->getDeviceCLIs($deviceId, $userId);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Device rules get
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_rules_get
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 4;

    $response = $deviceApi->getDeviceRules($deviceId);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Device rule delete
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_rule_delete
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceRuleId = 2;

    $response = $deviceApi->deleteDeviceRule($deviceRuleId);

    echo "Device Rule Delete Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Create device rule
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_device_rule_create
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 2;
    $name = 'myrule';
    $cut = '353';
    $add = '0';

    $response = $deviceApi->createDeviceRule($deviceId, $name, $cut, $add);

    echo "Device Rule Create Status: " . $response['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

## CLI

### CLI info get
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_CLI_info_get
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $cli = '12345';

    $response = $deviceApi->getCLIInfo($cli);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```


### CLI Delete
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_CLI_delete
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $cliNumber = '12345';

    $response = $deviceApi->deleteCLI($cliNumber);

    echo "CLI Delete Status: " . $response['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### CLI Add
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_CLI_add
```php
use CODEIQBV\Kolmisoft\Api\Device;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $deviceApi = new Device();

    $deviceId = 2;
    $cliNumber = '1234678';
    $params = [
        'cli_description' => 'description',
        'comment' => 'comment',
        'ivr_id' => 2,
        'banned' => 1,
    ];

    $response = $deviceApi->addCLI($deviceId, $cliNumber, $params);

    echo "CLI Add Status: " . $response['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
