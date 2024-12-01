## Phonebooks

### Get phonebooks
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_phonebooks_get.
```php
use CODEIQBV\Kolmisoft\Api\Phonebook;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $phonebookApi = new Phonebook();

    $phonebooks = $phonebookApi->getPhonebooks(123);

    foreach ($phonebooks as $entry) {
        echo "ID: " . $entry['id'] . "\n";
        echo "Name: " . $entry['name'] . "\n";
        echo "Number: " . $entry['number'] . "\n";
        if ($entry['speeddial']) {
            echo "Speed Dial: " . $entry['speeddial'] . "\n";
        }
        echo "-------------------\n";
    }
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Phonebooks edit
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_phonebook_edit.
```php
use CODEIQBV\Kolmisoft\Api\Phonebook;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $phonebookApi = new Phonebook();

    $result = $phonebookApi->update(123, [
        'name' => 'phonebook',
        'number' => '920355666',
        'speeddial' => '666',
    ]);

    echo "Update Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Phonebook record create
For more information go to https://wiki.kolmisoft.com/index.php/MOR_API_phonebook_record_create.
```php
use CODEIQBV\Kolmisoft\Api\Phonebook;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $phonebookApi = new Phonebook();

    $result = $phonebookApi->create(
        userId: 2,
        name: 'phonebook',
        number: '920355666',
        speeddial: '666'
    );

    echo "Creation Status: " . $result . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
