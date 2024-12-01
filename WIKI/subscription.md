## Subscriptions

### Delete a subscription
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_subscription_delete.
```php
use CODEIQBV\Kolmisoft\Api\Subscription;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $subscriptionApi = new Subscription();

    $subscriptionId = 123;
    $deleteAction = 3; // Delete with whole money return

    $result = $subscriptionApi->delete($subscriptionId, $deleteAction);

    echo "Subscription Delete Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Get subscriptions
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Subscriptions_get.

```php
use CODEIQBV\Kolmisoft\Api\Subscription;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $subscriptionApi = new Subscription();

    $params = [
        'service_id' => 2,
        'subscription_activation_start' => 1307167264,
        'subscription_activation_end' => 1307167265,
        'subscription_memo' => 'Subscriptions memo',
        'subscription_until_canceled' => 1,
        'user_id' => 2,
    ];

    $subscriptions = $subscriptionApi->getSubscriptions($params);

    echo "Subscriptions List:\n";
    print_r($subscriptions);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Create subscription
For more details go to https://wiki.kolmisoft.com/index.php/MOR_API_subscription_create.

```php
use CODEIQBV\Kolmisoft\Api\Subscription;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $subscriptionApi = new Subscription();

    $params = [
        'user_id' => 2,
        'service_id' => 10,
        'subscription_memo' => 'acc_one',
        // Add other optional parameters as needed
    ];

    $result = $subscriptionApi->createSubscription($params);

    echo "Subscription Create Status: " . $result['status'] . "\n";
    echo "Subscription ID: " . $result['id'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Create subscriptions in bulk
For more details go to https://wiki.kolmisoft.com/index.php/MOR_API_subscription_create_bulk.

```php
use CODEIQBV\Kolmisoft\Api\Subscription;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $subscriptionApi = new Subscription();

    $params = [
        'user_id' => 2,
        'service_id' => '1,1,1,2,3', // Multiple service IDs
        'subscription_memo' => 'acc_one',
        // Add other optional parameters as needed
    ];

    $result = $subscriptionApi->createSubscriptionBulk($params);

    echo "Subscription Bulk Create Status: " . $result['status'] . "\n";
    echo "Subscription IDs: " . implode(', ', $result['ids']) . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Update subscription
For more details go to https://wiki.kolmisoft.com/index.php/MOR_API_subscription_update.

```php
use CODEIQBV\Kolmisoft\Api\Subscription;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $subscriptionApi = new Subscription();

    $params = [
        'subscription_id' => 10,
        'subscription_activation_start' => 1255132800,
        'subscription_memo' => 'acc_one',
        // Add other optional parameters as needed
    ];

    $result = $subscriptionApi->updateSubscription($params);

    echo "Subscription Update Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Subscription Flat Rate Number Status Get
For more details go to https://wiki.kolmisoft.com/index.php/MOR_API_Subscription_Flat_Rate_Number_Status_Get

```php
use CODEIQBV\Kolmisoft\Api\Subscription;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $subscriptionApi = new Subscription();

    $userId = 45;
    $number = '370666888666';

    $result = $subscriptionApi->getFlatRateNumberStatus($userId, $number);

    echo "Number: " . $result['number'] . "\n";
    echo "Status: " . $result['status'] . "\n";
    if ($result['prefix']) {
        echo "Prefix: " . $result['prefix'] . "\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
