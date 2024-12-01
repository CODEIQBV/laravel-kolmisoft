## IVR

### IVR Dial Plan Update
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_ivr_dial_plan_update.
```php
use CODEIQBV\Kolmisoft\Api\IvrDialPlan;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $ivrDialPlanApi = new IvrDialPlan();

    // Update with specific time periods
    $result = $ivrDialPlanApi->update(1, [
        'time_period1' => 1,
        'time_period2' => 2,
        'time_period3' => 3,
    ]);

    // Update with some time periods
    $result = $ivrDialPlanApi->update(1, [
        'time_period1' => 1,
        'time_period2' => '', // Clear time period 2
        'time_period3' => 3,
    ]);

    echo "Update Status: " . $result['success'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### IVR Time Period Update
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_ivr_time_period_update.
```php
use CODEIQBV\Kolmisoft\Api\IvrTimePeriod;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $ivrTimePeriodApi = new IvrTimePeriod();

    // Basic update
    $result = $ivrTimePeriodApi->update(1, [
        'name' => 'Test Time Period',
        'start_weekday' => 'mon',
        'end_weekday' => 'wed',
    ]);

    // Advanced update
    $result = $ivrTimePeriodApi->update(1, [
        'name' => 'Work Hours',
        'start_hour' => 9,
        'end_hour' => 17,
        'start_minute' => 0,
        'end_minute' => 30,
        'start_weekday' => 'mon',
        'end_weekday' => 'fri',
        'start_month' => 1,
        'end_month' => 12,
        'start_day' => 1,
        'end_day' => 31,
    ]);

    echo "Update Status: " . $result['message'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
