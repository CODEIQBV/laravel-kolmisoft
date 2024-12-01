## Login and Logout

### Login
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_user_login.
```php
use CODEIQBV\Kolmisoft\Api\User;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $userApi = new User();

    // Login with username
    $result = $userApi->login('username', 'password');

    // Or login with email (if enabled)
    $result = $userApi->login('user@example.com', 'password');

    echo "Login Status: " . $result['status'] . "\n";
    echo "Message: " . $result['status_message'] . "\n";
    if (isset($result['user_id'])) {
        echo "User ID: " . $result['user_id'] . "\n";
    }
} catch (ApiException $e) {
    echo "Login Error: " . $e->getMessage() . "\n";
}
```

### Logout
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_user_logout.
```php
use CODEIQBV\Kolmisoft\Api\User;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $userApi = new User();
    $result = $userApi->logout();

    echo "Logout Status: " . $result['status'] . "\n";
} catch (ApiException $e) {
    echo "Logout Error: " . $e->getMessage() . "\n";
}
```
