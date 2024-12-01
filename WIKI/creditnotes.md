## Credit notes

### Get credit notes
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_credit_notes_get
```php
use CODEIQBV\Kolmisoft\Api\CreditNote;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $creditNoteApi = new CreditNote();

    $userId = 123;
    $creditNoteId = 345;

    $response = $creditNoteApi->getCreditNotes($userId, $creditNoteId);

    print_r($response);
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Update credit note
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_credit_note_update
```php
use CODEIQBV\Kolmisoft\Api\CreditNote;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $creditNoteApi = new CreditNote();

    $creditNoteId = 123;
    $status = 'paid';
    $comment = 'Payment received';

    $response = $creditNoteApi->updateCreditNote($creditNoteId, $status, $comment);

    echo "Credit Note Update Status: " . $response['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Create credit note
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_credit_notes_create
```php
use CODEIQBV\Kolmisoft\Api\CreditNote;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $creditNoteApi = new CreditNote();

    $userId = 123;
    $price = 100;
    $issueDate = 214543;
    $params = [
        'number' => 'CN-001',
        'comment' => 'Credit note for services',
    ];

    $response = $creditNoteApi->createCreditNote($userId, $price, $issueDate, $params);

    echo "Credit Note Creation Status: " . $response['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

### Delete credit note
For more information go to: https://wiki.kolmisoft.com/index.php/MOR_API_credit_notes_delete
```php
use CODEIQBV\Kolmisoft\Api\CreditNote;
use CODEIQBV\Kolmisoft\Exceptions\ApiException;

try {
    $creditNoteApi = new CreditNote();

    $creditNoteId = 123;

    $response = $creditNoteApi->deleteCreditNote($creditNoteId);

    echo "Credit Note Delete Status: " . $response['status'] . "\n";
} catch (ApiException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```
