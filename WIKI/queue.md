## Queue log

### Get queue logs
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_queue_log_get.
```php
use CODEIQBV\Kolmisoft\Api\Queue;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $queueApi = new Queue();

    // Example with filters
    $params = [
        'from' => new DateTime('2023-01-01'),
        'till' => new DateTime('2023-12-31'),
        'queuename' => 'queue_mano',
        'agent' => 'Local/1001@mor_local',
        'event' => 'CONNECT',
    ];

    $logs = $queueApi->getLogs($params);

    foreach ($logs as $log) {
        echo "ID: " . $log['id'] . "\n";
        echo "Queue: " . $log['queue_name'] . "\n";
        echo "Agent: " . $log['agent'] . "\n";
        echo "Event: " . $log['event'] . "\n";
        echo "Call ID: " . $log['call_id'] . "\n";
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
