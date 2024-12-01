## System information

### Usage
```php
use CODEIQBV\Kolmisoft\Api\System;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $systemApi = new System();

    $version = $systemApi->getVersion();

    echo "MOR System Version: " . $version . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
