## Voucher endpoint

### Usage
For more details go to https://wiki.kolmisoft.com/index.php/MOR_API_voucher_use.
```php
use CODEIQBV\Kolmisoft\Api\Voucher;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $voucherApi = new Voucher();

    $voucherNumber = '10007';
    $userId = 2; // Optional

    $result = $voucherApi->useVoucher($voucherNumber, $userId);

    echo "Voucher Use Status: " . $result['status'] . "\n";
    print_r($result);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
