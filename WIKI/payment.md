## Payments

### Create payment
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_payment_create.
```php
use CODEIQBV\Kolmisoft\Api\Payment;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $paymentApi = new Payment();

    // Basic payment creation
    $result = $paymentApi->create(
        userId: 123,
        currency: 'EUR',
        amount: 100
    );

    // Advanced payment creation
    $result = $paymentApi->create(
        userId: 123,
        currency: 'EUR',
        amount: 100,
        params: [
            'paymenttype' => 'Myname',
            'tax_in_amount' => '1',
            'transaction' => '2S5sdf77',
            'payer_email' => 'user@example.com',
            'comments_for_user' => 'Payment for services',
        ]
    );

    echo "Payment Status: " . $result['status'] . "\n";
    echo "Confirmed: " . ($result['confirmed'] ? 'Yes' : 'No') . "\n";
    echo "Payment ID: " . $result['payment']['id'] . "\n";
    echo "Amount: " . $result['payment']['amount'] . " " . $result['payment']['currency'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Get payments
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_payments_get.
```php
use CODEIQBV\Kolmisoft\Api\Payment;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $paymentApi = new Payment();

    // Basic search
    $payments = $paymentApi->getPayments([
        's_user_id' => 123,
    ]);

    // Advanced search
    $payments = $paymentApi->getPayments([
        's_from' => new DateTime('2023-01-01'),
        's_till' => new DateTime('2023-12-31'),
        's_completed' => '1',
        's_paymenttype' => Payment::TYPE_PAYPAL,
        's_amount_min' => 100,
        's_amount_max' => 1000,
        's_currency' => 'USD',
    ]);

    foreach ($payments as $payment) {
        echo "User: " . $payment['user'] . "\n";
        echo "Amount: " . $payment['amount'] . " " . $payment['currency'] . "\n";
        echo "Type: " . $payment['type'] . "\n";
        echo "Status: " . ($payment['completed'] ? 'Completed' : 'Pending') . "\n";
        echo "Date: " . $payment['date'] . "\n";
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
