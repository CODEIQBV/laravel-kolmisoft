## Statistics

### Usage
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_quickstats_get.

```php
use CODEIQBV\Kolmisoft\Api\Stats;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $statsApi = new Stats();

    $stats = $statsApi->getQuickStats();

    echo "Today's Statistics:\n";
    echo "Calls: " . $stats['today']['calls'] . "\n";
    echo "Duration: " . $stats['today']['duration'] . " seconds\n";
    echo "Revenue: " . $stats['today']['revenue'] . "\n";
    echo "Self Cost: " . $stats['today']['self_cost'] . "\n";
    echo "Profit: " . $stats['today']['profit'] . "\n";
    echo "Margin: " . $stats['today']['margin'] . "\n\n";

    echo "Active Calls:\n";
    echo "Total: " . $stats['active_calls']['total'] . "\n";
    echo "Answered: " . $stats['active_calls']['answered_calls'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
