## Financial Statement

### Usage
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_financial_statements_get
```php
use CODEIQBV\Kolmisoft\Api\FinancialStatement;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $financialApi = new FinancialStatement();

    $params = [
        'user_id' => 123, // Optional: specify the user ID
        'date_from' => '2023-01-01', // Required: start date for filtering
        'date_till' => '2023-12-31', // Required: end date for filtering
        'status' => 'paid', // Optional: filter by status
    ];

    $financialStatements = $financialApi->getFinancialStatements($params);

    // Output the financial statements
    print_r($financialStatements);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
