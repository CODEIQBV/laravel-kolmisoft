## Number pools

### Get number pools
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_number_pools_get.
```php
use CODEIQBV\Kolmisoft\Api\NumberPool;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $numberPoolApi = new NumberPool();

    // Get all pools
    $pools = $numberPoolApi->getPools();

    // Get specific pool
    $pools = $numberPoolApi->getPools([
        'number_pool_id' => 1,
    ]);

    // Get pools with numbers and user assignments
    $pools = $numberPoolApi->getPools([
        'show_numbers' => '1',
        'number_limit_row_count' => 100,
        'number_limit_offset' => 0,
        'user_ids' => 'all',
    ]);

    foreach ($pools as $pool) {
        echo "Pool ID: " . $pool['id'] . "\n";
        echo "Name: " . $pool['name'] . "\n";
        if (!empty($pool['comment'])) {
            echo "Comment: " . $pool['comment'] . "\n";
        }
        
        if (isset($pool['numbers'])) {
            echo "Numbers:\n";
            foreach ($pool['numbers'] as $number) {
                echo "  " . $number['number'] . "\n";
                if (isset($number['users'])) {
                    foreach ($number['users'] as $user) {
                        echo "    " . $user['username'] . " (" . $user['list_type'] . ")\n";
                    }
                }
            }
        }
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Create number pool
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_number_pool_create.
```php
use CODEIQBV\Kolmisoft\Api\NumberPool;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $numberPoolApi = new NumberPool();

    // Basic creation
    $result = $numberPoolApi->create('test2');

    // Creation with comment
    $result = $numberPoolApi->create(
        name: 'test2',
        comment: 'test_comment'
    );

    echo "Number Pool Created Successfully!\n";
    echo "Status: " . $result['status'] . "\n";
    echo "Pool ID: " . $result['number_pool_id'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Update number pool
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_number_pool_update.
```php
use CODEIQBV\Kolmisoft\Api\NumberPool;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $numberPoolApi = new NumberPool();

    // Basic update
    $result = $numberPoolApi->update(2, [
        'name' => 'test_test2',
    ]);

    // Update with all optional parameters
    $result = $numberPoolApi->update(2, [
        'name' => 'test_test2',
        'comment' => 'test_comment2',
    ]);

    echo "Update Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete number pool
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_number_pool_delete.
```php
use CODEIQBV\Kolmisoft\Api\NumberPool;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $numberPoolApi = new NumberPool();

    $result = $numberPoolApi->delete(2);

    echo "Delete Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Create number pool number
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_number_pool_numbers_create.
```php
use CODEIQBV\Kolmisoft\Api\NumberPool;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $numberPoolApi = new NumberPool();

    // Create numbers using string
    $result = $numberPoolApi->createNumbers(3, '44%,#14,13');

    // Or create numbers using array
    $result = $numberPoolApi->createNumbers(3, [
        '44%',
        '#14',
        '13',
    ]);

    echo "Creation Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete number pool number
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_number_pool_numbers_delete.
```php
use CODEIQBV\Kolmisoft\Api\NumberPool;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $numberPoolApi = new NumberPool();

    // Delete all numbers in pool
    $result = $numberPoolApi->deleteNumbers(3, [
        'delete_all' => '1',
    ]);

    // Delete specific numbers by IDs
    $result = $numberPoolApi->deleteNumbers(3, [
        'number_ids' => '1,3,4',
    ]);

    // Delete specific numbers by value
    $result = $numberPoolApi->deleteNumbers(3, [
        'numbers' => '#44,370,44%',
    ]);

    // Delete using both IDs and numbers
    $result = $numberPoolApi->deleteNumbers(3, [
        'number_ids' => '1,3,4',
        'numbers' => '#44,370',
    ]);

    echo "Delete Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
