## User 

### Create new user
To get all details go to: https://wiki.kolmisoft.com/index.php/MOR_API_user_register
```php
use CODEIQBV\Kolmisoft\Api\User;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $userApi = new User();

    $params = [
        'email' => 'newuser@example.com',
        'id' => 'uniquehash123',
        'device_type' => 'SIP',
        'username' => 'newuser',
        'password' => 'SecurePass123',
        'password2' => 'SecurePass123',
        'country_id' => 10, // Argentina
        // Add other optional parameters as needed
    ];

    $result = $userApi->register($params);

    echo "Registration Status: " . $result['status'] . "\n";
    print_r($result['user_device_settings']);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Get user details
To get all details go to: https://wiki.kolmisoft.com/index.php/MOR_API_user_details_get
```php
use CODEIQBV\Kolmisoft\Api\User;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $userApi = new User();

    $params = [
        'user_id' => 123, // or 'username' => 'exampleuser'
        // Add other optional parameters as needed
    ];

    $details = $userApi->getDetails($params);

    echo "User Details:\n";
    print_r($details);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Get user details RAW
To get all details go to: https://wiki.kolmisoft.com/index.php/MOR_API_user_details_raw_get
```php
use CODEIQBV\Kolmisoft\Api\User;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $userApi = new User();

    $params = [
        'user_id' => 123, // or 'username' => 'exampleuser'
        // Add other optional parameters as needed
    ];

    $rawDetails = $userApi->getRawDetails($params);

    echo "Raw User Details:\n";
    print_r($rawDetails);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Update a user
To check all parameters go to https://wiki.kolmisoft.com/index.php/MOR_API_user_details_update.
```php
use CODEIQBV\Kolmisoft\Api\User;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $userApi = new User();

    $params = [
        'user_id' => 123,
        'u15' => 1, // Example parameter to block the user
        // Add other parameters as needed
    ];

    $result = $userApi->updateDetails($params);

    echo "Update Status: " . $result['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Users get
To check all details go to https://wiki.kolmisoft.com/index.php/MOR_API_users_get.
```php
use CODEIQBV\Kolmisoft\Api\User;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $userApi = new User();

    $params = [
        'u' => 'admin',
        'p' => 'admin1',
        // Add other parameters as needed
    ];

    $users = $userApi->getUsers($params);

    echo "Users List:\n";
    print_r($users);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
### Delete a user
To get all details go to: https://wiki.kolmisoft.com/index.php/MOR_API_user_delete.
