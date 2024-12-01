## Emailing

### Send email
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_email_send
```php
use CODEIQBV\Kolmisoft\Api\Email;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $emailApi = new Email();

    $params = [
        'email_name' => 'registration_confirmation_for_user', // Required
        'email_to_user_id' => 142, // Optional: User ID to receive the email
        'login_username' => 'new_first_name', // Email template parameters
        'login_url' => 'www.kolmisoft.com',
        'server_ip' => '127.0.0.2',
    ];

    $response = $emailApi->sendEmail($params);

    // Output the email sending status
    echo "Email Sending Status: " . $response['email_sending_status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
