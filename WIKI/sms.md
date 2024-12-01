## SMS
NOTE: SMS addon is need to have these APIs (SMS add-on is no longer supported, new functionality will not be developed)

### Send SMS
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_sms_send.

```php
use CODEIQBV\Kolmisoft\Api\SMS;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $smsApi = new SMS();

    $lcrId = 1;
    $destination = '937567337911';
    $source = '9375783123767';
    $message = 'hi';

    $result = $smsApi->send($lcrId, $destination, $source, $message);

    echo "SMS Status: " . $result['status'] . "\n";
    echo "Message ID: " . $result['message_id'] . "\n";
    echo "Status Tip: " . $result['sms_status_code_tip'] . "\n";
    echo "Price: " . $result['price'] . " " . $result['currency'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### User SMS service subscribe
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_user_sms_service_subscribe

```php
use CODEIQBV\Kolmisoft\Api\SMS;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $smsApi = new SMS();

    $userId = 2;
    $smsTariffId = 1;
    $smsLcrId = 1; // Optional, required for admin

    $result = $smsApi->subscribeUser($userId, $smsTariffId, $smsLcrId);

    echo "Subscription Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### User SMS get
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_user_sms_get.

```php
use CODEIQBV\Kolmisoft\Api\SMS;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $smsApi = new SMS();

    $params = [
        'from' => strtotime('2007-05-05 23:59:59'),
        'till' => strtotime('2016-02-17 23:59:59'),
        'status_code' => 301,
        'provider_id' => 1,
        'user_id' => 5,
        'reseller_id' => 3,
        'prefix' => '353',
        'number' => '353863520065',
    ];

    $messages = $smsApi->getUserSMS($params);

    foreach ($messages as $message) {
        echo "SMS ID: " . $message['id'] . "\n";
        echo "Sending Date: " . $message['sending_date'] . "\n";
        echo "Number: " . $message['number'] . "\n";
        echo "Status Code: " . $message['status_code'] . "\n";
        echo "User Price: " . $message['user_price'] . "\n";
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
