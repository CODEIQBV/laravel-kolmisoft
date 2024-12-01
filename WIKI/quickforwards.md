## Quick forwards

### Get quickforwards
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Quickforwards_get.
```php
use CODEIQBV\Kolmisoft\Api\Quickforward;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $quickforwardApi = new Quickforward();

    $dids = $quickforwardApi->getDids();

    foreach ($dids as $did) {
        echo "DID: " . $did['did'] . "\n";
        if ($did['forward_to']) {
            echo "Forward To: " . $did['forward_to'] . "\n";
        }
        if ($did['description']) {
            echo "Description: " . $did['description'] . "\n";
        }
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Update quickforward
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Quickforwards_update.
```php
use CODEIQBV\Kolmisoft\Api\Quickforward;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $quickforwardApi = new Quickforward();

    $result = $quickforwardApi->updateDid(
        did: '37060503666',
        forwardTo: '37066603777',
        description: 'abyss'
    );

    echo "DID Updated Successfully:\n";
    echo "DID: " . $result['did'] . "\n";
    echo "Forward To: " . $result['forward_to'] . "\n";
    echo "Description: " . $result['description'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete quickforward
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_Quickforwards_delete.
```php

```
