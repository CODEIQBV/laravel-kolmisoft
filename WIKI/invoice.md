## Invoices

### Get invoices
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_invoices_get.
```php
use CODEIQBV\Kolmisoft\Api\Invoice;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;
use DateTime;

try {
    $invoiceApi = new Invoice();

    // Get invoices using DateTime objects
    $result = $invoiceApi->getInvoices(
        from: new DateTime('2019-05-01'),
        till: new DateTime('2019-05-31'),
        lang: 'en'
    );

    // Get invoices using timestamps
    $result = $invoiceApi->getInvoices(
        from: 1188604800,
        till: 1191196799,
        lang: 'en'
    );

    foreach ($result['invoices'] as $invoice) {
        echo "Invoice #{$invoice['number']}\n";
        echo "User ID: {$invoice['user_id']}\n";
        echo "Paid: " . ($invoice['paid'] ? 'Yes' : 'No') . "\n";
        
        foreach ($invoice['products'] as $product) {
            echo "  Product: {$product['name']}\n";
            echo "  Price: {$product['price']}\n";
            echo "  Sum: {$product['sum']}\n";
            echo "  Issue Date: {$product['issue_date']}\n";
            echo "  ---\n";
        }
        echo "================\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Update invoice
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_invoice_update.
```php
use CODEIQBV\Kolmisoft\Api\Invoice;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $invoiceApi = new Invoice();

    // Basic update
    $result = $invoiceApi->update(2, [
        'comment' => 'Updated via API',
    ]);

    // Advanced update
    $result = $invoiceApi->update(2, [
        'name' => 'John Doe',
        'address' => '123 Main St',
        'city' => 'New York',
        'postcode' => '10001',
        'state' => 'NY',
        'country_id' => 1,
        'phone' => '+1234567890',
        'tax_reg_number' => 'TAX123',
        'comment' => 'Updated via API',
        'invoice_sent_manually' => true,
        'pay' => true,
    ]);

    echo "Update Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
