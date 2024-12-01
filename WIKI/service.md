## Service

### Service create
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Service_create.
```php
use CODEIQBV\Kolmisoft\Api\Service;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $serviceApi = new Service();

    $params = [
        'sell_price' => 10.00,
        'self_cost' => 5.00,
        'minutes_per_month' => 5,
        'owner_id' => 1,
    ];

    $result = $serviceApi->create(
        'My New Service',
        Service::TYPE_FLAT_RATE,
        $params
    );

    echo "Service Creation Status: " . $result['status'] . "\n";
    echo "Service ID: " . $result['id'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Service delete
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Service_delete
```php
use CODEIQBV\Kolmisoft\Api\Service;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $serviceApi = new Service();

    $serviceId = 10;
    $result = $serviceApi->delete($serviceId);

    echo "Service Deletion Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Services get
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Services_get
```php
use CODEIQBV\Kolmisoft\Api\Service;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $serviceApi = new Service();

    $services = $serviceApi->getServices();

    foreach ($services as $service) {
        echo "Service ID: " . $service['id'] . "\n";
        echo "Name: " . $service['name'] . "\n";
        echo "Type: " . $service['type'] . "\n";
        echo "Price: " . $service['price'] . " " . $service['currency'] . "\n";
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Service update
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Service_update

```php
use CODEIQBV\Kolmisoft\Api\Service;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $serviceApi = new Service();

    $serviceId = 19;
    $params = [
        'name' => 'Updated Service Name',
        'type' => Service::TYPE_PERIODIC_FEE,
        'sell_price' => 29.99,
        'self_cost' => 19.99,
        'period' => Service::PERIOD_MONTH,
        'minutes_per_month' => 100,
    ];

    $result = $serviceApi->update($serviceId, $params);

    echo "Service Update Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

