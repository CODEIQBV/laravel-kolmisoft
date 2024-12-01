## Recordings

## Get recordings
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_recordings_get.

```php
use CODEIQBV\Kolmisoft\Api\Recording;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $recordingApi = new Recording();

    $params = [
        'date_from' => new \DateTime('2023-01-01'),
        'date_till' => new \DateTime('2023-12-31'),
        'destination' => '1234567890',
        'source' => '9876543210',
        'user' => 123,
        'device' => 456,
    ];

    $recordings = $recordingApi->get($params);

    foreach ($recordings as $recording) {
        echo "Recording ID: " . $recording['id'] . "\n";
        echo "Date: " . $recording['date'] . "\n";
        echo "Duration: " . $recording['duration'] . " seconds\n";
        echo "Size: " . $recording['size'] . " bytes\n";
        if ($recording['mp3_url']) {
            echo "MP3 URL: " . $recording['mp3_url'] . "\n";
        }
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
### Update recording
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_recording_update.
```php
use CODEIQBV\Kolmisoft\Api\Recording;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $recordingApi = new Recording();

    $recordingId = 82878881;
    $comment = '12346';

    $result = $recordingApi->update($recordingId, $comment);

    echo "Recording Update Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```

### Delete recording
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_recordings_delete.
```php
use CODEIQBV\Kolmisoft\Api\Recording;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $recordingApi = new Recording();

    // Example 1: Delete by recording ID
    $result = $recordingApi->delete(['recording_id' => 45454]);

    // Example 2: Delete by user and date range
    $result = $recordingApi->delete([
        's_user_id' => 5,
        'date_till' => new \DateTime('2019-12-31 23:59:59'),
    ]);

    // Example 3: Delete by user, device, and date range
    $result = $recordingApi->delete([
        's_user_id' => 5,
        's_device_id' => 13,
        'date_from' => new \DateTime('2021-01-01'),
        'date_till' => new \DateTime('2021-01-31 23:59:59'),
    ]);

    echo "Deletion Status: " . $result['status'] . "\n";
    echo "Recordings Deleted: " . $result['amount'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage();
}
```
